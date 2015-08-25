<?php
/*
 * Fuoricentro Article Cross Filtering Main Class
 * 
 * Version 1.0 - Copyright 2013 Brando Meniconi (b.meniconi[at]fuoricentostudio.com)
 * 
 */

namespace FuoricentroStudio\WP\Filters;

class PostCrossTax {

    protected $taxonomies = array();
    protected $post_type = array();
    protected $current_options = array();
    protected $url_prefix = array();


    const TAX_SEPARATOR = ' ';

    public function __construct($post_type='post', $taxonomies=array('category'), $url_prefix='') {
        $this->taxonomies = $taxonomies;
        $this->post_type = $post_type;
        $this->url_prefix = trim( $url_prefix ?: $this->post_type , '/');    
        
        $this->init();
    }

    public function init() {

        add_shortcode('render_article_filter', array($this,'render_shortcode'));        
        add_action( 'pre_get_posts', array($this,'explode_filters'));
        
        $this->add_rewrite_rules();        
        
    }
    
    public function add_rewrite_rules(){
        
        $rules = array_merge( 
                $this->rewrite_rules_maker( $this->taxonomies, '^'.$this->url_prefix, '?(?:/?page/(\d+)/?)?$', 'index.php?post_type='.$this->post_type ),
                $this->rewrite_rules_maker( array_reverse($this->taxonomies), '^'.$this->url_prefix, '?(?:/?page/(\d+)/?)?$', 'index.php?post_type='.$this->post_type)
        );
        
        foreach($rules as $regex=>$redirect){
            add_rewrite_rule ($regex, $redirect,'top');
        }
        
    }
    
    public function rewrite_rules_maker($taxonomies, $re_prefix = '', $re_suffix = '', $rd_prefix = '', $rd_suffix = '', $depth = 1 )
     {
         
        $newrules = array();
        
        //Taxonomy array trail 
        $next_level_tax = $taxonomies;

        foreach( $taxonomies  as $taxonomy) {
            //Insert in the rules array the new rule :D
            $newrules[$re_prefix.'/'.$this->rewrite_rule_regex($taxonomy).'/'.$re_suffix] = $rd_prefix.'&'.$this->rewrite_rule_redirect($taxonomy, $depth).'&paged=$matches['.($depth+1).']'.$rd_suffix;
            
            $this->add_rewrite_tag($taxonomy);
            
            //Remove first element of the trail
            array_shift($next_level_tax);

            //If there are any remaining taxonomies left, go with recursion! (so cool)
            if(count($next_level_tax) > 0){
                $newrules = array_merge(
                        $newrules,
                        $this->rewrite_rules_maker(
                                $next_level_tax,
                                $re_prefix.'/'.$this->rewrite_rule_regex($taxonomy),
                                $re_suffix,
                                $rd_prefix.'&'.$this->rewrite_rule_redirect($taxonomy, $depth),
                                '',
                                ($depth+1)
                                )
                        );
            } 
        }
        
        return $newrules;
        
    }
    
    public function add_rewrite_tag($base){
            add_rewrite_tag('%'.$this->tax_to_alias($base).'%','([^&]+)');
    }    
    
    public function rewrite_rule_regex($base){
            return $base.'/([^/]*)';
    }
    
    public function rewrite_rule_redirect($base,$match_idx){
            return $this->tax_to_alias($base).'=$matches['.$match_idx.']';
    }
    
    public function tax_to_alias($taxonomy){
        return '_'.$taxonomy;
    }
    
    public function alias_to_tax($taxonomy){
        return ltrim ($taxonomy,'_');
    }
    
    public function explode_filters($wp_query){

        if(!$wp_query->is_main_query() || is_admin())
            return;
               
        foreach ($this->taxonomies as $tax_name) {
                
                $tax_param = $this->tax_to_alias($tax_name);
                
                if(!isset($wp_query->query_vars[$tax_param]) ||($wp_query->query_vars[$tax_param] == '')) { continue; }
                
                $wp_query->query_vars['tax_query']['relation'] = 'AND';
                
                $wp_query->query_vars['tax_query'][] = array(
                    'taxonomy'=>$tax_name,
                    'field'=>'slug',
                    'terms' => $this->current_options[$tax_name] = explode(self::TAX_SEPARATOR, urldecode($wp_query->query_vars[$tax_param]))
                    );

                unset($wp_query->query_vars[$tax_param]);
        }
        
        //var_dump($wp_query->query_vars);
        

    }

    public function render_shortcode($atts){
        
        ob_start();
        
        $params = shortcode_atts( array('taxonomies' => 'categories'), $atts );
        $this->render_filter(explode(',',$params['taxonomies']));
        
        $output = ob_get_contents();
            
        ob_end_clean();
        
        if($output)
            return $output;
        else 
            return NULL;
        
    }

    public function beforeFilter(){
        
    }

    public function render_filter($taxonomies = array('categories')){
        
        ?>
        <form id="article-multifilter" action="" method="get">
                <?php $this->beforeFilter($taxonomies); ?>
                <?php do_action('before_article_filter', $taxonomies); ?>
                <?php 
                foreach ((array)$taxonomies as $taxonomy)
                    $this->render_filter_options ($taxonomy);
                ?>
                <?php $this->afterFilter($taxonomies); ?>
                <?php do_action('after_article_filter', $taxonomies); ?>
                <input type="submit" value="Filtra"/>           
        </form>
        <?php
    }

    public function afterFilter(){
        
    }
    
    public function get_param_value($taxonomy){
        
        if (isset($this->current_options[$taxonomy]))
            return $this->current_options[$taxonomy];
        else 
            return false;
    }
    
    public function render_filter_options($taxonomy = 'categories'){
        ?>
        <div id="filter-<?php echo esc_attr($taxonomy)?>" class="filters-group group-<?php echo esc_attr($taxonomy)?>">
            <?php printf(apply_filters('cross_filter_group_title', '<div class="filter-group-title">%s</div>'), get_taxonomy($taxonomy)->labels->name); ?>
            <ul class="">
            <?php do_action('before_article_filter_group', $taxonomy); ?>
            <?php foreach((array)apply_filters('cross_filter_items',get_terms($taxonomy, ['hide_empty'=>false])) as $term): ?>
                <li ><input class="filter-option <?php echo esc_attr($taxonomy); ?>_<?php echo esc_attr($term->slug); ?>" type="checkbox" id="f-<?php echo esc_attr($taxonomy); ?>_<?php echo esc_attr($term->slug); ?>"  name="<?php echo esc_attr($taxonomy); ?>" value="<?php echo esc_attr($term->slug); ?>" <?php echo ($this->is_checked($taxonomy,$term->slug)?'checked="checked"':''); ?> /><label class="iconset" for="f-<?php echo esc_attr($taxonomy); ?>_<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></label></li>
            <?php endforeach;?>
            </ul>
        </div>
        <?php

    }
    
    public function is_checked($taxonomy,$term){
        
        return (isset($this->current_options[$taxonomy]) 
                    && (
                        ($this->current_options[$taxonomy] === $term)
                        ||
                        in_array($term,$this->current_options[$taxonomy])
                       )
                );
            
    }
    
    public function term_name($term, $taxonomy){   
                
        $term = get_term_by('slug',$term, $taxonomy);
        
        if($term)
            return $term->name;
    }
}

?>