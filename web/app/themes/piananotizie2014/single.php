<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', get_post_type() );
                        
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				//comments_template();
			endif;			                      
                          
		// End the loop.
		endwhile;
		?>
                <?php if ( is_active_sidebar( 'daily-promoter' ) ) : ?>
			<div id="sponsored-by">
                            <?php dynamic_sidebar( 'daily-promoter' ); ?>
			</div><!-- .widget-area -->
                <?php endif;   
                if(function_exists('yarpp_related')) {
                    yarpp_related(array(
                    'template' => 'yarpp-template-thumbnail.php',
                    'treshold' => 4,
                    'limit' => 3,
                    'order' => 'date DESC'
                ));
                } ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
