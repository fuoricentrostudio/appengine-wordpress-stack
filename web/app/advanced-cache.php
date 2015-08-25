<?php

namespace FuoricentroStudio\WP\PageCache;
use google\appengine\api\taskqueue\PushTask;

class Memcacher {
    
    public $uri;
    public $page_key;
    public $prefix = '_pcache_';
    public $server_expire = 16070400; // 6 months
    public $client_expire = 3600;    
    public $keys = ['time', 'content', 'headers'];
       
    protected $mc;
    protected $headers;
    protected $status_code;


    public function __construct($page_url=''){
        
        if(defined('WP_CACHE_KEY_SALT')){
            $this->prefix .= WP_CACHE_KEY_SALT;
        }
        
        $this->set_page_url($page_url);
                
        if (!class_exists('Memcached')){
            return;
        } 
        
        $this->mc = new \Memcached;
    }
    
    public function set_page_url($page_url){
        $this->uri = $page_url;
        $this->page_key =  $this->prefix.'['.$page_url.']';
    }
    
    public function start(){
            
        $data = $this->get_cache();                
        
        if($data !== false){ //cache hit ?                   
            
            $this->headers($data); 
            
            if( $this->not_modified_since($this->data($data, 'time')) ) {
                 header('HTTP/1.1 304 Not Modified');
                 exit();
            }               
                        
            header('X-Page-Cache: hit');
            
            echo $this->data($data, 'content');
            exit;  
            
        } 
        
        $this->record_output();
    }
    
    public function get_cache(){
        
        $data = $this->mc->getMulti($this->keys());
        
        if(empty($data) || !trim($this->data($data, 'content'))){
            return false;
        }
        
        return $data;
    }
    
    public function queue(){
        $task = new PushTask( $this->uri, ['generate_cache' => 1], ['method'=>'GET', 'name'=>uniqid($this->page_key)]);
        $task_name = $task->add();
    }
    
    public function headers($data){
             
//        $cached_headers = $this->parse_headers($this->data($data, 'headers'));
//        $sent_headers = $this->parse_headers(headers_list());
        
//        foreach(array_merge($cached_headers, $sent_headers) as $key=>$value){
//            header($key.': '.$value);
//        }        
               
        $time = $this->data($data, 'time');
        
        header('Vary: Cookie', false);
        header('ETag: "'.$this->etag($time).'"');
        header('Last-Modified: '.gmdate(\DateTime::RFC2822, $time));
        header('Cache-Control: max-age=' . $this->client_expire);
        
        $headers = $this->data($data, 'headers');
        
        if(!empty($headers)){
            array_walk($headers, 'header');
        }        
                
    }
    
    public function etag($time){
        return md5($this->page_key.$time);
    }

    public function not_modified_since($time){
        
        if($time && isset($_SERVER['HTTP_IF_NONE_MATCH']) && ($_SERVER['HTTP_IF_NONE_MATCH'] === '"'.$etag.'"')) {
            return true;
        }            
        
        if($time && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (\DateTime::createFromFormat(\DateTime::RFC2822, $_SERVER['HTTP_IF_MODIFIED_SINCE'])->getTimestamp() >= $time)) {            
            return true;
        }        
        
        return false;
    }
    
    public function cache($action, $id){        
        if(is_callable(array($this->mc, $action))){
            return call_user_func_array(array($this->mc, $action), $this->key($id) + array_slice(func_get_args(), 2) );
        }
    }
    
    public function delete(){
        $this->mc->deleteMulti($this->keys());
    }
    
    public function data($data, $id){
        
        $key = $this->key($id);
        
        return isset($data[$key]) ? $data[$key] : false;
    }
    
    public function keys(){  
        return array_map(array($this, 'key'), $this->keys );
    }
    
    public function key($id){
        return $this->page_key.'_'.$id;
    }
    
    public function lock(){
        return $this->mc->add($this->key('lock'), 0);
    }
    
    public function unlock(){
        return $this->mc->delete($this->key('lock'));
    }    
    
    public function record_output(){
        if($this->lock()){
            ob_start(array($this, 'cache_output'));
        }
    }
    
    public function parse_headers($headers=array()){
        
        $parsed_headers = array();
        
        foreach ($headers as $header) {
            list($name, $value) = array_map('trim', explode($header, ':', 2));
            $parsed_headers[$k] = $v; 
        }
        
        return $parsed_headers;
    }
    
    
    public function cache_output($output){
        
        if(!trim($output) || ((int)$this->status_code >= 400)){
            return $output;
        }
                
        if(is_home() || is_front_page() || is_archive()){
            header('Cache-Control: max-age=' . $this->client_expire. ', must-revalidate');
        }
        
        $time = time();
        
        $cache_items = array(
            $this->key('time') => $time,
            $this->key('content') => $output,
            $this->key('headers') => headers_list()
        );
        
        $this->mc->setMulti($cache_items, $this->server_expire);
        
        header('ETag: "'.$this->etag($time).'"');
        header('Last-Modified: '.gmdate(\DateTime::RFC2822, $time));
        header('Cache-Control: max-age=' . $this->client_expire);
        
        header('X-Page-Cache: miss');

//        $options = stream_context_create(['gs'=>['acl'=>'public-read','Content-Type' => 'text/html']]);
//        file_put_contents('gs://static.piananotizie.it'. (rtrim(parse_url($this->uri, PHP_URL_PATH), '/\\') ?: 'index').'.html', $output, 0, $options);
        
        $this->unlock();
                
        return $output;
    }
    
    public function status_header($header, $code){
        
        $this->headers['status'] = $header;
        $this->status_code = $code;
        
        return $header;
    }
    
    public function cache_redirect($status, $location){
        return $status;
    }
    
}

$wp_filter['clean_post_cache'][10]['f_cache'] = array( 'function' => __NAMESPACE__.'\\update_post', 'accepted_args' => 2 );

if(cache_enabled()){
    
    $f_cache = new Memcacher(WP_SITEURL.$_SERVER['REQUEST_URI']);
    $f_cache->start();
    
    $wp_filter['status_header'][10]['f_cache'] = array( 'function' => array($f_cache, 'status_header'), 'accepted_args' => 2 );
    //$wp_filter['wp_redirect_status'][10]['f_cache'] = array( 'function' => array($f_cache, 'cache_redirect'), 'accepted_args' => 2 );    

}

function cache_enabled(){

    // Never cache API endpoints.
    if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST){
        return false;
    }

    // Never cache when POST data is present.
    if ( ! empty( $HTTP_RAW_POST_DATA ) || ! empty( $_POST ) ){
        return false;
    }

    // Never cache when cookies indicate a cache-exempt visitor.
    if ( ! empty( $_COOKIE ) && is_array($_COOKIE)) {
            foreach ( array_keys( $_COOKIE ) as $cookie ) {
                    if ( (substr( $cookie, 0, 19 ) == 'wordpress_logged_in' || substr( $cookie, 0, 14 ) == 'comment_author' ) ) {
                            return false;
                    }
            }
    }    

    if(is_admin()){
        return false;
    }        

    return true;

}

function update_post($post_id){
            
    if ( wp_is_post_revision( $post_id ) || (get_post_status($post_id) != 'publish')){
            return;
    }
    
    $cache = new Memcacher();            
    
    $reset_url = array( 
        'post'=>get_permalink($post_id),
        'home'=>home_url( '/' ), 
    );
    
    if(!empty(get_option( 'page_for_posts' ))){
        $reset_url['blog'] = get_permalink(get_option( 'page_for_posts' ));
    }
                
    foreach($reset_url as $url){
        
        $cache->set_page_url($url);
        $cache->delete();    
        
        $cache->set_page_url(trailingslashit($url));
        $cache->delete();    
    }        
    
}
