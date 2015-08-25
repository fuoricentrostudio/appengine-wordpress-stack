<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'piananotizie' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

                        <div class="masonry-container" data-masonry-options='{"itemSelector": ".hentry" }' >
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post(); ?>

				<?php
				/*
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
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
			) );

                        if ( $wp_query->max_num_pages > 1 ) : ?>
                            <div class="load-more-container">
                                <a class="load-more" href="<?php next_posts(); ?>"><?php _e('Load More Articles', 'piananotizie') ?><span class="icon icon-reload3"></span></a>
                            </div>                    
                        <?php endif; // If no content, include the "No posts found" template.                        
                        
		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
