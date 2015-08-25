<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

use FuoricentroStudio\WP\Flickity\Slider as FeaturedSlider;

get_header(); ?>
        <div id="spotlight" class="full-band contrast-medium">
            <div class="band-content">
                <div id="featured">
                <aside class="gallery js-flickity" data-flickity-options='{ "cellSelector": ".gallery-cell", "wrapAround": true, "contain": true, "prevNextButtons": false, "pageDots": false, "autoPlay": 5000}'>
                        <?php

                        $args = array(
                            'posts_per_page' => 5,
                            'post__in'  => get_option( 'sticky_posts' )
                        );

                        $sticky_query = new WP_Query( $args );
                        $sticky_limit = 0;
                        while ($sticky_query->have_posts() && ($sticky_limit<5)): $sticky_query->the_post(); ?>
                            <?php $sticky_limit++ ?>
                            <article <?php post_class(['gallery-cell']); ?>>
                                <?php $featuredImage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'main-slider' ); ?>
                                <a href="<?php the_permalink(); ?>" <?php if($featuredImage) { printf('style="background-image: url(%s)"', $featuredImage[0]); } ?>></a>                                       
                                <div class="abstract">
                                    <ul class="post-locations"><?php the_terms( get_the_ID(), 'comune', '<li>', '</li>' ); ?></ul>
                                    <a href="<?php the_permalink(); ?>"><?php the_title('<h2>','</h2>' ); ?></a>
                                    <?php remove_filter( 'the_excerpt', 'wpautop' ); ?>
                                    <div class="slide-excerpt"><?php the_excerpt(); ?></div>
                                    <?php add_filter( 'the_excerpt', 'wpautop' ); ?>
                                    <?php the_category(); ?>             
                                </div>                                            
                            </article>                                
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                </aside>  
                    <div class="flickity-controls">
                    <button class="flickity-prev-next-button previous" type="button"></button>                        
                    <ol class="flickity-page-dots"></ol>
                    <button class="flickity-prev-next-button next" type="button"></button>
                    <div class="flickity-page-num">
                        <span class="slide-index">0</span>
                        <span class="slide-count">0</span>
                    </div>
                    <script>
                        (function($){

                            $(document).ready(function(){

                                var $container = $('#featured');

                                //Navigation
                                $('.gallery-cell', $container).each(function(){
                                    $('.flickity-page-dots', $container).append($('<li class="dot" ></li>'));
                                });

                                $('.flickity-page-num .slide-count', $container).text($('.gallery-cell', $container).size());

                                $('.flickity-page-dots', $container).on('click', '.dot', function(){
                                    $('.gallery', $container).flickity( 'select', $(this).index());
                                });

                                $('.gallery', $container).on('cellSelect', function(){
                                    var sIndex = Flickity.data('#featured .gallery').selectedIndex;
                                    $('.flickity-page-dots li', $container).removeClass('active').eq(sIndex).addClass('active');
                                    $('.flickity-page-num .slide-index', $container).text((sIndex+1));
                                });

                                //Prev Next
                                $('.flickity-prev-next-button.next').on('click', function(){
                                    $('.gallery', $container).flickity( 'next', true );
                                });

                                $('.flickity-prev-next-button.previous').on('click', function(){
                                    $('.gallery', $container).flickity( 'previous', true );
                                });
                            });
                        })(jQuery);
                    </script>
                </div>  
                </div>
                <div id="home-portlet-right">
                    <?php dynamic_sidebar('home-portlet-right'); ?> 
                </div>
            </div>
        </div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">     
                    <div id="last-news" class="full-band">
                        <div class="band-content">
                            <h1 class="band-title"><?php _e('Last news', 'piananotizie'); ?></h1>
                            <?php foreach (get_terms('comune', ['hide_empty'=>false]) as $comune): ?>
                                <section id="<?php echo $comune->slug ?>" class="column mobile-accordion">
                                    <h4 class="section-title mobile-accordion-trigger"><?php echo $comune->name; ?></h4>
                                    <a class="section-link" href="<?php echo get_term_link($comune); ?>"><?php _e('View complete archive' , 'piananotizie'); ?></a>
                                    <?php 
                                        $location_query = new WP_Query(array(
                                        'tax_query' => array(
                                                array(
                                                        'taxonomy' => 'comune',
                                                        'field'    => 'slug',
                                                        'terms'    => $comune->slug,
                                                ),
                                        ),
                                        'posts_per_page'=>4,
                                        'ignore_sticky_posts'=>1,
                                        'post__not_in' => get_option( 'sticky_posts' )
                                         ));
                                        while ( $location_query->have_posts() ) : $location_query->the_post(); 
                                            get_template_part( 'content', get_post_type() ); 
                                        endwhile;
                                    ?>
                                </section>
                            <?php endforeach; ?>   
                            <?php wp_reset_postdata(); ?>
                            <a class="link-to-archive" href="<?php echo get_permalink(get_option( 'page_for_posts' )); ?>"><?php _e('Read all news','piananotizie'); ?></a>
                        </div>
                    </div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
