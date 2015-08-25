<?php

namespace FuoricentroStudio\WP\Flickity;

add_action('wp_enqueue_scripts', __NAMESPACE__.'\\Slider::scripts');
add_shortcode( 'flickity_gallery', __NAMESPACE__.'\\Slider::shortcode' );

class Slider {
    public $id;
        
    public static $defaultQuery= array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_mime_type' => 'image/jpeg,image/png',
        );
    
    public $query = array();
    
    public static $flickity = array(
        'cellSelector'=>'.gallery-cell',
        'imagesLoaded'=>'true',
        'contain'=> true
    );
    
    public static $imageSize = 'slider-cell';
    
    public function __construct($post_id=null, $id=null){
        $this->query = self::$defaultQuery;
        $this->setPostId($post_id ?: get_the_ID());
        $this->id = $id ?: uniqid('flickity');
    }
    
    public static function create($post_id=null, $id=null){
        return new self($post_id, $id);
    }

    public function setPostId($id){
        $this->query['post_parent'] = (int)$id;
    }
    
    public static function instanceId($id){
        return $id ?: uniqid('flickity');
    }    
    
    public static function scripts() {
        wp_enqueue_style ( 'flickity-style', 'https://cdnjs.cloudflare.com/ajax/libs/flickity/1.0.2/flickity.min.css' );
        wp_enqueue_script ( 'flickity', 'https://cdnjs.cloudflare.com/ajax/libs/flickity/1.0.2/flickity.pkgd.min.js',array(),'1.0.2',true);    
    }    
        
    public function render($query_params= array(), $imageSize=null, $flickity = array() ) {
                
        $items = get_posts(wp_parse_args($query_params, $this->query));
        
        $this->renderSlider($items, $imageSize, $flickity); 
        
        return $items;
    }
    
    public static function extractId($item){
        return $item->ID;
    }
            
    public function renderSlider($images, $imageSize=null, $flickity = array()){        
        if (count($images) > 1) :?>

            <div id="<?php echo esc_attr($this->id); ?>" class="gallery js-flickity"
                 data-flickity-options='<?php echo json_encode((object)wp_parse_args($flickity, self::$flickity))  ?>'>
                    <?php
                    foreach ((array) $images as $image) : ?>
                        <div class="<?php echo esc_attr(ltrim(self::$flickity['cellSelector'], '.')); ?>">
                            <?php $this->renderImage($image, $imageSize); ?>
                        </div>
                    <?php endforeach; ?>
            </div>
            <?php
            return true; 

        elseif(count($images) == 1):?>

            <div class="featured-image">
                <?php 
                foreach ((array) $images as $image) : 
                    $this->renderImage($image, $imageSize);  
                endforeach; 
                ?>
            </div>
            <?php 
            return true;
        else:
            return false;     
        endif;         
    }
    
    public function renderImage($id, $imageSize=null){
        
        if(is_object($id)){
            $id = self::extractId($id);
        }
        
        ?>
        <a href="<?php  $attach_meta = wp_get_attachment_image_src($id, 'full');  echo $attach_meta[0]; ?>" rel="<?php echo esc_attr($this->id); ?>">
            <?php echo wp_get_attachment_image($id, $imageSize ?: self::$imageSize, false, array('title' => false)); ?>
        </a>
    <?php }   
    
    public function shortcode($atts){
        
	$atts = shortcode_atts( array(
                'size'=>self::$imageSize,
		'images' => '',
		'post_id' =>'',
                'jsparams'=> array(),
                'query_params'=> array(),
	), $atts, 'flickity_gallery' );        
        
        ob_start(); 
           
        $jsparams = wp_parse_args($atts['jsparams'], self::$flickity);
        $query_params = wp_parse_args($atts['query_params']);
        
        if(!empty($atts['images'])){
            self::create()->renderSlider(get_posts( array('post_type' => 'attachment', 'post__in'=>$atts['images']) ) , $atts['size'], $jsparams);
        }elseif(!empty($atts['post_id'])){
            self::create($atts['post_id'])->render($query_params, $atts['size'], $jsparams);
        }else {
            self::create()->render($query_params, $atts['size'], $jsparams);
        }
                
        $output = ob_get_contents();
        
        ob_end_clean();
        
        return $output;
    }
}

class PostGallery {

    public static $instance = 0;
    
    const NAME = 'Flickity';
        
    public static function setDefault(){
        add_filter( 'post_gallery', __NAMESPACE__.'\\PostGallery::render', 10, 3);
    }

    public static function unsetDefault(){
        remove_filter( 'post_gallery', __NAMESPACE__.'\\PostGallery::render');
    }    
    
    public static function register(){
        if(function_exists('Gallery_Customizer::add')){
           \Gallery_Customizer::add('flickity', __CLASS__);
        }
    }
    
    public static function render($output = '', $attr, $instance){
        
       	if(is_null($instance)){
            static $instance = 0;
            $instance++;
        }
        
	$post = get_post();

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}        
        
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'size'       => Slider::$imageSize,
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}
                       
        ob_start();
        Slider::create($id, 'flickity-gallery-'.$instance)->renderSlider($attachments, $atts['size']);
        
        $output = ob_get_contents();
        
        ob_end_clean();
        
	return $output;
    }
}

add_action('after_setup_theme', __NAMESPACE__.'\\PostGallery::register');