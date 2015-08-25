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

get_header(); ?>
        <div id="filter" class="full-band contrast-medium collapsible collapsed">
            <div class="band-title collapse-trigger"><?php _e('Advanced search', 'piananotizie') ?></div>
            <div class="band-content collapse-content">    
                <?php echo do_shortcode('[render_article_filter taxonomies="category,comune"]'); ?>
            </div>
        </div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

                        <header>
                                <h1 class="page-title screen-reader-text"><?php _e('News', 'piananotizie'); ?></h1>
                        </header>

                        <div class="masonry-container">
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_type() );

			// End the loop.
			endwhile; ?>
                        </div>
                        <?php
			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'piananotizie' ),
				'next_text'          => __( 'Next page', 'piananotizie' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'piananotizie' ) . ' </span>',
			) ); ?>
                        
                        <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                            <div class="load-more-container">
                                <a class="load-more" href="<?php next_posts(); ?>"><?php _e('Load More Articles', 'piananotizie') ?><span class="icon icon-reload3"></span></a>
                            </div>                    
                        <?php endif; 
		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
