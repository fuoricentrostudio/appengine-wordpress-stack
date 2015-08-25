<?php

namespace FuoricentroStudio\WP\Themes\Piananotizie;

use \FuoricentroStudio\WP\Helpers\MetaBox;
use \FuoricentroStudio\WP\Helpers\Twitter as TwitterFeed;

function taxonomies(){
    
    $labels = array(
            'name'              => _x( 'Comuni', 'piananotizie' ),
            'singular_name'     => _x( 'Comune', 'piananotizie' ),
            'search_items'      => __( 'Search in comuni', 'piananotizie' ),
            'all_items'         => __( 'All comuni', 'piananotizie' ),
            'parent_item'       => __( 'Parents', 'piananotizie' ),
            'parent_item_colon' => __( 'Parents:' ),
            'edit_item'         => __( 'Edit Comuni', 'piananotizie' ),
            'update_item'       => __( 'Update Comune', 'piananotizie' ),
            'add_new_item'      => __( 'Add New Comune', 'piananotizie' ),
            'new_item_name'     => __( 'New name comune', 'piananotizie' ),
            'menu_name'         => __( 'Comuni', 'piananotizie' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'comune' ),
    );

    register_taxonomy( 'comune', array( 'post' ), $args );    
    
}

add_action('init', __NAMESPACE__.'\\taxonomies');


function scripts(){
    wp_enqueue_style('icons', get_stylesheet_directory_uri().'/icons/style.css');
    
    wp_enqueue_style( 'piananotizie-style', get_stylesheet_uri(), array(), '1.2.3');
    wp_enqueue_style( 'piananotizie-ie', get_stylesheet_directory_uri(). '/css/ie.css', array('piananotizie-style'), '20150628');
    
    wp_enqueue_script( 'piananotizie-script', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery', 'jquery-instagram'), '1.2.2', true );
    wp_enqueue_script('jquery-instagram', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-instagram/0.3.1/instagram.min.js', array('jquery'), '0.3.1', true );
    wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '3.1.1', true ); 
    wp_enqueue_script( 'masonry', 'https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js', array( 'jquery' ), '3.3.1', true ); 
    wp_enqueue_script( 'mason', get_stylesheet_directory_uri() . '/js/mason.js', array( 'jquery', 'masonry' ), '1.2.1', true ); 
    // Load the Internet Explorer specific stylesheet.
    wp_style_add_data( 'piananotizie-ie', 'conditional', 'lt IE 9' );
    
    if(is_home()){
        wp_enqueue_script( 'postcrosstax', get_stylesheet_directory_uri() . '/js/filter.js', array( 'jquery' ), '1.1', true );    
        wp_localize_script( 'postcrosstax', 'postCrossTaxConfig', array(
                'filterTarget' => '#main .masonry-container', 
                'queryUrl' => get_permalink(get_option( 'page_for_posts' ))
               ));
    }
    
    wp_localize_script( 'piananotizie-script', 'twitterFeed', twitter_feed_url());
}

add_action('wp_enqueue_scripts', __NAMESPACE__.'\\scripts', 11, 0);

function script_manager(){
    wp_dequeue_style('genericons');
    wp_dequeue_style('twentyfifteen-fonts');
    wp_dequeue_style('twentyfifteen-ie');
    wp_dequeue_style('twentyfifteen-style');
    wp_deregister_style('twentyfifteen-style');
}

add_action('wp_enqueue_scripts', __NAMESPACE__.'\\script_manager', 11, 0);


function twitter_feed_url(){
  
    $twitterFeed = get_transient('twitter_static_feed_url');
    if(false === $twitterFeed){
        if(class_exists('\google\appengine\api\cloud_storage\CloudStorageTools') && (WP_ENV !== 'development')){
            syslog(LOG_INFO, 'Update twitter URL' );
            $twitterFeed = \google\appengine\api\cloud_storage\CloudStorageTools::getPublicUrl(TwitterFeed::paths(), (bool) get_option(\google\appengine\WordPress\Uploads\Uploads::USE_SECURE_URLS_OPTION, '' ));
        } else {
            $twitterFeed = TwitterFeed::paths('baseurl');
        }
        set_transient('twitter_static_feed_url', $twitterFeed);    
    }    
    
    return $twitterFeed;
    
}

//Funzioni per disabilitare il CSS di YARPP
function disable_yarpp_css_header() {
  wp_dequeue_style('yarppWidgetCss');
  wp_deregister_style('yarppRelatedCss');
}
add_action( 'wp_print_styles', __NAMESPACE__.'\\disable_yarpp_css_header' );

function disable_yarpp_css_footer() {
  wp_dequeue_style('yarppRelatedCss');
}
add_action( 'wp_footer', __NAMESPACE__.'\\disable_yarpp_css_footer' );

function sidebars(){

    register_sidebar(array(
      'name' => __( 'Masthead', 'piananotizie' ),
      'id' => 'masthead',
      'description' => __( 'I contenuti vengono mostrati sopra il menu', 'piananotizie' ),
      'before_title' => '<h3>',
      'after_title' => '</h3>',
      'before_widget' => '<div id="masthead-banner" class="widget %2$s banner-area">',
      'after_widget'  => '</div>',         
    ));      
    
    register_sidebar(array(
      'name' => __( 'Manchette Left' ),
      'id' => 'manchette-left',
      'description' => __( 'Contents are shown in the manchette', 'piananotizie' ),
      'before_title' => '<h3>',
      'after_title' => '</h3>',
      'before_widget' => '<div id="manchette-left" class="widget %2$s banner-area">',
      'after_widget'  => '</div>',         
    ));   
    
    register_sidebar(array(
      'name' => __( 'Manchette Right' ),
      'id' => 'manchette-right',
      'description' => __( 'Contents are shown in the right manchette', 'piananotizie' ),
      'before_title' => '<h3>',
      'after_title' => '</h3>',
      'before_widget' => '<div id="manchette-right" class="widget %2$s banner-area">',
      'after_widget'  => '</div>',         
    ));       
    
    register_sidebar(array(
      'name' => __( 'Portlet lato destra' ),
      'id' => 'home-portlet-right',
      'description' => __( 'Contents are shown in home near the slider', 'piananotizie' ),
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar(array(
      'name' => __( 'Sponsor of the day', 'piananotizie' ),
      'id' => 'daily-promoter',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',    
      'description' => __( 'Place a picture that will be shown at the end of every page', 'piananotizie' ),
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar(array(
      'name' => __( 'Sponsor Footer', 'piananotizie' ),
      'id' => 'sponsor-footer',
      'description' => __( 'Enter in this area the logos of sponsors through Widgets <Image>', 'piananotizie' ),
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar(array(
      'name' => __( 'Social', 'piananotizie' ),
      'id' => 'footer-social',
      'description' => __( 'Insert the image that will be displayed at the bottom of the footer', 'piananotizie' ),
      'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="widget-wrapper cf">',
      'after_widget'  => '</div></li>',    
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar(array(
      'name' => __( 'Siti amici', 'piananotizie' ),
      'id' => 'siti-amici',
      'description' => __( 'Insert the image that will be displayed at the bottom of the footer', 'piananotizie' ),
      'before_widget' => '<li id="%1$s" class="widget %2$s">',
      'after_widget'  => '</li>',    
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));

    register_sidebar(array(
      'name' => __( 'Widget Footer', 'piananotizie' ),
      'id' => 'widget-footer',
      'description' => __( 'Bottom page Area' ),
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget'  => '</aside>',  
      'before_title' => '<h3>',
      'after_title' => '</h3>'
    ));    
    
}

add_action( 'widgets_init', __NAMESPACE__.'\\sidebars' );



function prepend_to_main_menu($args){
    
    if('primary' == $args['theme_location']){
        $args['items_wrap'] = '<a href="'.esc_url( home_url( '/' ) ).'" class="home-link"><span class="screen-reader-text">'.__('Home', 'piananotizie').'</span></a>'.$args['items_wrap'] ;
    }
    
    return $args;
}

add_filter('wp_nav_menu_args', __NAMESPACE__.'\\prepend_to_main_menu');


function post_types() {
    register_post_type('editoriale', array(
        'label' => 'Editoriali',
        'description' => '',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'has_archive' => true,
        'rewrite' => array('slug' => 'editoriale', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'trackbacks', 'comments', 'revisions', 'author'),
        'taxonomies' => array('post_tag'),
        'labels' => array(
            'name' => 'Editoriali',
            'singular_name' => 'Editoriale',
            'menu_name' => 'Editoriali',
            'add_new' => 'Aggiungi Nuovo',
            'add_new_item' => 'Aggiungi Nuovo Editoriale',
            'edit' => 'Modifica',
            'edit_item' => 'Modifica Editoriale',
            'new_item' => 'Nuovo Editoriale',
            'view' => 'Visualizza',
            'view_item' => 'Visualizza Editoriale',
            'search_items' => 'Search Editoriali',
            'not_found' => 'No Editoriali Found',
            'not_found_in_trash' => 'No Editoriali Found in Trash',
            'parent' => 'Parent Editoriale',
        )
            )
    );

     register_post_type('curiosita', array(
         'label' => 'Curiosità',
         'description' => '',
         'public' => true,
         'show_ui' => true,
         'show_in_menu' => true,
         'capability_type' => 'post',
         'map_meta_cap' => true,
         'hierarchical' => false,
         'rewrite' => array('slug' => 'curiosita', 'with_front' => true),
         'query_var' => true,
         'has_archive' => true,
         'supports' => array('title', 'editor', 'excerpt', 'trackbacks', 'comments', 'revisions', 'thumbnail', 'author'),
         'taxonomies' => array('post_tag'),
         'labels' => array(
             'name' => 'Curiosità',
             'singular_name' => 'Curiosità',
             'menu_name' => 'Curiosità',
             'add_new' => 'Aggiungi Nuovo',
             'add_new_item' => 'Aggiungi Nuova Curiosità',
             'edit' => 'Modifica',
             'edit_item' => 'Modifica Curiosità',
             'new_item' => 'Nuova Curiosità',
             'view' => 'Visualizza',
             'view_item' => 'Visualizza Curiosità',
             'search_items' => 'Search Curiosità',
             'not_found' => 'No Curiosità Found',
             'not_found_in_trash' => 'No Curiosità Found in Trash',
             'parent' => 'Parent Curiosità',
         )
             )
     );
 }

add_action('init', __NAMESPACE__.'\\post_types');
 
function metaboxes(){
        
    new MetaBox('news_options',
            [
                'news_featured' => [ 'type'=>'checkbox', 'label'=>'News in primo piano'],
                'nascondi_autore' => [ 'type'=>'checkbox', 'label'=>'Nascondi Autore', 'default'=>'1'],
                'nome_autore' => 'Nome Autore Personalizzato',
                'email_autore' => 'Email Autore Personalizzato'
            ],
            ['title'=>'Opzioni news']
    );
    
}
 
if ( is_admin() ) {
    add_action( 'load-post.php', __NAMESPACE__.'\\metaboxes' );
    add_action( 'load-post-new.php', __NAMESPACE__.'\\metaboxes' );
}

function image_sizes(){
    add_image_size(\FuoricentroStudio\WP\Flickity\Slider::$imageSize, 720, 450 , true);
    add_image_size('wide-thumbnail', 420, 210,true);
    add_image_size('main-slider', 1000, 450, false);
}

add_action('init', __NAMESPACE__.'\\image_sizes');


function menus() {
  register_nav_menu( 'footer-links', __( 'Footer links', 'piananotizie' ) );
}

add_action( 'after_setup_theme', __NAMESPACE__.'\\menus' );


function parent_theme_override() {
    remove_filter( 'excerpt_more', 'twentyfifteen_excerpt_more' );
    remove_filter( 'get_search_form', 'twentyfifteen_search_form_modify' );
    set_post_thumbnail_size( 400, 200, false );
    load_theme_textdomain( 'piananotizie', get_stylesheet_directory() . '/languages' );    
}

add_action( 'after_setup_theme', __NAMESPACE__.'\\parent_theme_override', 99 );

function excerpt_more( $more ) {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		'<span class="screen-reader-text">' . sprintf( __( 'Continue reading %s', 'piananotizie' ), get_the_title( get_the_ID() )) . '</span>' 
		);
	return  $link;
}
add_filter( 'excerpt_more', __NAMESPACE__.'\excerpt_more' );


function body_sidebar_class($classes){
    if((is_singular() && !is_front_page()) || is_author() ){
        $classes[] = 'has-sidebar';
    }
    return $classes;
}
add_filter('body_class', __NAMESPACE__.'\body_sidebar_class');

function curiosita_excerpt_length( $length ) {
	return 38;
}

function editoriale_excerpt_length( $length ) {
	return 40;
}

function custom_excerpt_length( $length ) {
    return 25;
}
add_filter('excerpt_length', __NAMESPACE__.'\custom_excerpt_length', 99);

function init(){
    new \FuoricentroStudio\WP\Filters\PostCrossTax('post', ['comune', 'category' ], parse_url(get_permalink(get_option( 'page_for_posts' )), PHP_URL_PATH));
    \FuoricentroStudio\WP\Flickity\PostGallery::setDefault();
}

add_action( 'init', __NAMESPACE__.'\\init', 99 );

function post_cover(){ ?>
    <div class="entry-cover">
            <?php the_post_thumbnail(); ?>
            <div class="hover-box">
                <div class="table">
                    <div class="table-cell">
                        <?php social_share_link(); ?>
                        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read article', 'piananotizie'); ?></a>
                    </div>
                </div>
            </div>                    
        </div>
<?php }

function social_share_link(){ ?>
    <span class="share-label"><?php _e('Share on social', 'piananotizie'); ?></span>
    <ul class="share"> 
        <li>
            <a class="facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>" title="Condividi su Facebook">
                <span class="icon icon-facebook"></span>
                <span class="screen-reader-text">Facebook</span>
            </a>
        </li>    
        <li>
            <a class="twitter" target="_blank" href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="Condividi su Twitter">
                <span class="icon icon-twitter"></span>
                <span class="screen-reader-text">Twitter</span>
            </a>
        </li>
        <li>
            <a class="google-plus" target="_blank" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" title="Condividi su Google+">
                <span class="icon icon-google-plus"></span>
                <span class="screen-reader-text">Google+</span>
            </a>
        </li>
    </ul>
<?php }

function post_teaser($more_link_text=''){
    global $more, $pages, $page;
    if ( preg_match( '/<!--more(.*?)?-->/', $pages[$page - 1]) ) {
       $moreTmp = $more;
        $more = 0;
        remove_filter( 'the_content', 'wpautop' ); ?>
        <div class="entry-teaser"><?php the_content($more_link_text); ?></div>
        <?php
        add_filter('the_content','wpautop');
        $more = $moreTmp;
        unset($moreTmp); 
    }
}

function blog_exclude_sticky($query){
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'ignore_sticky_posts', 1 );
    }    
}
add_action( 'pre_get_posts', __NAMESPACE__.'\\blog_exclude_sticky', 99, 1);

function cookie_level($level=4){
    
    if( function_exists( '\FuoricentroStudio\WP\Policy\Cookie\Italy\cookie_allowed' ) ) {  
        return \FuoricentroStudio\WP\Policy\Cookie\Italy\cookie_allowed( $level );
    }
    
    return true;
}

function custom_author_email($value){
    $email = get_post_meta(get_the_ID(), 'email_autore', true);
    if($email){
        $value = $email;
    }
    return $value;
}

function custom_author_nicename($value){
    $nicename = get_post_meta(get_the_ID(), 'nome_autore', true);
    if($nicename){
        $value = $nicename;
    }   
    return $value;
}

function custom_author($name){
    if(get_post_meta(get_the_ID(),'nascondi_autore', true)){
        return null;
    }
    
    $custom_name = get_post_meta(get_the_ID(),'nome_autore', true);
    
    if($custom_name){
        $name = $custom_name;
    }    
    
    return $name;
}

if(!is_admin()){
    add_filter('get_the_author_user_email',__NAMESPACE__.'\custom_author_email');
    add_filter('get_the_author_user_nicename',__NAMESPACE__.'\custom_author_nicename');
    add_filter('the_author',__NAMESPACE__.'\custom_author');
}

function limit_sticky_posts($newstickies, $oldstickies){
        
    $query = array(
            'fields'=>'ids', 
            'posts_per_page' => 4,
            'post__in' => array_intersect($newstickies, $oldstickies),
            'ignore_sticky_posts' => true,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
    return array_merge(get_posts($query), array_diff($newstickies, $oldstickies));
    
}

add_filter( 'pre_update_option_sticky_posts', __NAMESPACE__.'\limit_sticky_posts', 10, 2);


function image_protection(){ ?>
<script type="text/javascript">
    (function($){
        $(function() {
            var pixelSource = 'https://lh3.googleusercontent.com/f_xyQRtlez2lp5Pioa2VG_zxtxYUtJgJHk_teodAVNWHMf7I5cuMOKvkyF67lAHwYXbIg4Hs1Qtgd52pjhzO07Z91JuzpQ=s0';
            var preload = new Image();
            preload.src = pixelSource;
            var icnt = $('<div id="ggphtprtc"></div>');
            icnt.appendTo('body');
            $('img').live('mouseenter touchstart', function(e) {
                var img = $(this);
                if (img.hasClass('ggphtprtc')) return;
                var pos = img.offset();
                var overlay = $('<img class="ggphtprtc" src="' + pixelSource + '" width="' + img.width() + '" height="' + img.height() + '" />')
                                .css({position: 'absolute', zIndex: 99, left: pos.left, top: pos.top})
                                .appendTo(icnt)
                                .bind('mouseleave', function() {
                                    setTimeout(function(){ overlay.remove(); }, 0, $(this));
                                });
                if ('ontouchstart' in window) $(document).one('touchend', function(){ setTimeout(function(){ overlay.remove(); }, 0, overlay); });
            });
        });
    })(jQuery);
</script>
<?php }

add_action( 'wp_footer', __NAMESPACE__.'\image_protection', 10, 2);

add_action( 'show_user_profile', __NAMESPACE__.'\user_social_fields' );
add_action( 'edit_user_profile', __NAMESPACE__.'\user_social_fields' );

function user_social_fields( $user ) { ?>

	<h3>Social Profiles</h3>

	<table class="form-table">

		<tr>
			<th><label for="instagram">Instagram</label></th>
			<td>
                            <input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" /><br />
                            <span class="description">Please enter your Instagram username.</span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', __NAMESPACE__.'\save_user_social_fields' );
add_action( 'edit_user_profile_update', __NAMESPACE__.'\save_user_social_fields' );

function save_user_social_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
        }

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'instagram', filter_input(INPUT_POST, 'instagram', FILTER_SANITIZE_STRING ));
}

function favicons(){
    $link = esc_attr('https://lh3.googleusercontent.com/DdCpnnJaOy6BH8fSh2cyxrQ0Qc7RYlPJVuM42X9YimUv8UkMV2M4vAJPafqvn0c_2BAcFqWvoAmDxZNqQoAn6kZGKd_Q');   ?>

    <?php foreach(array(57,60,72,76,114,120,144,152,180) as $size): ?>        
    <link rel="apple-touch-icon" sizes="<?php echo $size.'x'.$size?>" href="<?php echo $link; ?>=s<?php echo $size; ?>">
    <?php endforeach; ?>
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $link; ?>=s192">
    <?php foreach(array(16,32,96) as $size): ?>  
    <link rel="icon" type="image/png" sizes="<?php echo $size.'x'.$size?>" href="<?php echo $link; ?>=s<?php echo $size; ?>">
    <?php endforeach; ?>
    <meta name="msapplication-TileColor" content="#59afd6">
    <meta name="msapplication-TileImage" content="<?php echo $link; ?>=s144">
    <meta name="theme-color" content="#59afd6">
    
<?php }

add_action('wp_head', __NAMESPACE__.'\favicons');


/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_stylesheet_directory() . '/inc/filter.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_stylesheet_directory() . '/inc/metabox.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_stylesheet_directory() . '/inc/flickity.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_stylesheet_directory() . '/inc/askthelawyer.php';

/**
 * Twitter Stream Module.
 *
 * @since Twenty Fifteen 1.0
 */
require get_stylesheet_directory() . '/inc/twitter.php';

/**
 * Watermark Stream Module.
 *
 * @since Twenty Fifteen 1.0
 */
//require get_stylesheet_directory() . '/inc/watermark.php';

