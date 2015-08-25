<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <link rel="icon" href="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/icons/favicon.ico" />
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
                     
                <div id="mobile-header">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" ><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a>
                    <button class="menu-toggle"><span class="screen-reader-text"><?php _e( 'Menu', 'piananotizie' ); ?></span></button>
                </div>
            
                <?php dynamic_sidebar( 'masthead' ); ?>

                <div class="search-toggle">
                        <a href="#search-container" class="screen-reader-text" aria-expanded="false" aria-controls="search-container"><?php _e( 'Search', 'piananotizie' ); ?></a>
                </div>

                <nav id="primary-navigation" class="site-navigation primary-navigation stuck-target" role="navigation">
                    <div class="stuck-placeholder">
                        <a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'piananotizie' ); ?></a>
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu', 'container_id'=>'main-menu-container') ); ?>
                    </div>                        
                </nav>

                <div class="site-branding content-wrapper">

                        <?php dynamic_sidebar( 'manchette-left' ); ?>

                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></h1>   

                        <?php dynamic_sidebar( 'manchette-right' ); ?>     

                </div>		
               
		<div id="search-container" class="search-box-wrapper hide">
			<div class="search-box">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
