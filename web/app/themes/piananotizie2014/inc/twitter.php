<?php

namespace FuoricentroStudio\WP\Helpers;

class Twitter {
    
    public static $queryDefaults = [
        'count' => 5,
        'trim_user' => 1,
        'screen_name' => 'Piananotizie'
    ];
       
    public static $template = '<li>%s -- <span class="time">%s</span></li>';
        
    public static function instance(){
        
        add_action('twitter_feed_update', [__CLASS__, 'update']);
        
        if(!get_option('twitter_feed_updater_registered')){
            wp_schedule_event(time(), 'hourly', 'twitter_feed_update' );
            update_option('twitter_feed_updater_registered', 1);
        }
                
        if(is_admin()){
            add_action('wp_ajax_nopriv_userTweet', [__CLASS__, 'output']);
            add_action('wp_ajax_userTweet', [__CLASS__, 'output']);
            add_action('admin_post_updateTwitter', [__CLASS__, 'update']);
        }
    }
    
    public static function paths($base='basedir'){
        
        $ds = DIRECTORY_SEPARATOR;
        
        $upload_path = wp_upload_dir();
                
        return $upload_path[$base].$ds.'twitterfeed'.$ds.'user_'.self::$queryDefaults['screen_name'].'.html';
       
    }
   
    public static function output(){
        echo implode("\n",array_map([__CLASS__, 'render'], self::user_tweets()));
        exit();        
    }    
    
    public static function update(){
                
        $path = self::paths();
        $folder = dirname($path);
        
        if(!is_dir($folder) && !mkdir($folder)){
            syslog(LOG_WARNING, 'Error creating folder '.$folder);
            return 0;
        }

        $ctx = stream_context_create(['gs' => ['Content-Type' => 'text/plain', 'acl' => 'public-read']]);        
        
        if ( ! file_put_contents( $path , implode("\n", array_map([__CLASS__, 'render'], self::user_tweets())), 0, $ctx ) ) {
            syslog(LOG_WARNING,'Error saving Twitter static feed file in '.$path);
            return 0;
        }              
      
    }
    
    public static function oauth2_token(){

        if ( false === ( $token = get_transient( 'twitter_bearerToken' ) ) ) {

            $headers = array(
                    'Authorization' => 'Basic ' . base64_encode( '1UbtZbSU8ABhvAXIRFVrlW8oP'.':'.'wumKs5qMVzQI8sr3C90q0KE0G0aMQ9ucfqVxSRaRAqCT6apg5I' ),
                    'Expect'        => ''
            );

            $remote_params = array(
                    'headers'     => $headers,
                    'body'        => ['grant_type' => 'client_credentials'],
                    'sslverify'   => false
            );

            $response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $remote_params );

            if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
                    return '';
            }         

            $reply = json_decode(wp_remote_retrieve_body($response));

            if(isset($reply->token_type) && ('bearer' == $reply->token_type)){
                $token = $reply->access_token;
                set_transient( 'twitter_bearerToken', $token, 12 * HOUR_IN_SECONDS );
            }
        }

        return $token;
    }

    public static function user_tweets(){
                    
        $headers = array(
                'Authorization' => 'Bearer ' . self::oauth2_token(),
                'Expect'        => ''
        );

        $remote_params = array(
                'headers'     => $headers,
                'sslverify'   => false
        );

        $query = '?'.http_build_query(self::$queryDefaults);                                        

        $response = wp_remote_get( 'https://api.twitter.com/1.1/statuses/user_timeline.json'.$query , $remote_params );

        if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
                return '';
        }         

        $tweets = wp_remote_retrieve_body($response);
                    
        return array_map( array(__CLASS__, 'replace_entities'), json_decode($tweets, true));
    
    }
    
    public static function replace_entities($tweet){
        
            if (!isset($tweet['text']) || !isset($tweet['entities'])) {
                return $tweet;
            }
                
            $text = &$tweet['text'];

            $offset = 0;
            
            if (isset($tweet['entities']['hashtags'])) {
                foreach ($tweet['entities']['hashtags'] as $hashtag) {
                    $replacement = '<a href="https://twitter.com/search?q=%23' . $hashtag['text'] . '" target="_blank">#' . $hashtag['text'] . '</a>';
                    self::replace_entity($text, $replacement, $hashtag['indices'], $offset);
                }
            }
            
            if (isset($tweet['entities']['symbols'])) {
                foreach ($tweet['entities']['symbols'] as $symbol) {
                    $replacement = '<a href="https://twitter.com/search?q=%24' . $symbol['text'] . '&src=ctag" target="_blank">$' . $symbol['text'] . '</a>';
                    self::replace_entity($text, $replacement, $symbol['indices'], $offset);
                }
            }            

            if (isset($tweet['entities']['user_mentions'])) {
                foreach ($tweet['entities']['user_mentions'] as $mention) {
                    $replacement = '<a href="https://twitter.com/' . $mention['screen_name'] . '" target="_blank">@' . $mention['screen_name'] . '</a>';
                    self::replace_entity($text, $replacement, $mention['indices'], $offset);
                }
            }

            if (isset($tweet['entities']['urls'])) {
                foreach ($tweet['entities']['urls'] as $url) {
                    $replacement = '<a href="' . $url['expanded_url'] . '" target="_blank">' . $url['display_url'] . '</a>';
                    self::replace_entity($text, $replacement, $url['indices'], $offset);
                }
            }

            if (isset($tweet['entities']['media'])) {
                foreach ($tweet['entities']['media'] as $media) {
                    $replacement = '<a href="' . $media['expanded_url'] . '" target="_blank"><em>Foto</em></a>';
                    self::replace_entity($text, $replacement, $media['indices'], $offset);
                }
            }
            
            return $tweet;
            
    }
       
    public static function replace_entity(&$subject, $replacement, $indices, &$offset){
        $length = $indices[1] - $indices[0];
        $subject = substr_replace($subject, $replacement, $indices[0]+$offset, $length );
        $offset +=  ( strlen($replacement) - $length );      
    }
    
    public static function parseDate($tweet) {
        
        $elapsed_time = time()-strtotime($tweet['created_at']);
              
        $output = '';
                
        $time_steps = [
            YEAR_IN_SECONDS=>['1 anno','%s anni'], 
            DAY_IN_SECONDS*30=>['1 mese','%s mesi'], 
            DAY_IN_SECONDS=>['1 giorno','%s giorni'], 
            HOUR_IN_SECONDS=>['1 ora','%s ore'], 
            MINUTE_IN_SECONDS=>['1 minuto','%s minuti']
        ];
                
        foreach($time_steps as $divider=>$name){
            $amount = round($elapsed_time/$divider);
            if($amount>=1) {
                $output .= sprintf(_n($name[0], $name[1], $amount), $amount);
                break;
            }   
        }
        
        return $output. ' fa';
        
    }
    
    public static function render($tweet){       
        return sprintf('<li>%s -- <span class="time">%s</span></li>', $tweet['text'], self::parseDate($tweet) );
    }
    
}


Twitter::instance();