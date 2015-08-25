<?php
/**
 * The template for displaying Author archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
                                <div class="title-container">
                                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 256 ); ?>
                                    <h1 class="archive-title">
                                            <?php
                                                    /*
                                                     * Queue the first post, that way we know what author
                                                     * we're dealing with (if that is the case).
                                                     *
                                                     * We reset this later so we can run the loop properly
                                                     * with a call to rewind_posts().
                                                     */
                                                    the_post();

                                                    printf( __( 'Autore <span>%s</span>', 'piananotizie' ), get_the_author_meta('display_name') );
                                            ?>
                                    </h1>
                                </div>
				<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<p class="author-description"><?php the_author_meta( 'description' ); ?></p>
				<?php endif; ?>
                                <div id="author-social">
                                    <?php if ( get_the_author_meta( 'email' ) ) : ?>
                                    <div class="author-email">
                                        <span class="label"><?php _e('Email', 'piananotizie'); ?></span>
                                        <a target="_blank" href="mailto:<?php echo esc_attr(get_the_author_meta( 'email' )); ?>"><?php the_author_meta( 'email' ); ?></a>
                                    </div>
                                    <?php endif; ?> 
                                    <?php if ( !get_the_author_meta( 'twitter' ) ) : ?>
                                    <div class="author-twitter">
                                        <span class="label"><?php _e('Twitter', 'piananotizie'); ?></span>
                                        <a target="_blank" href="https://twitter.com/<?php echo esc_attr(get_the_author_meta( 'twitter' )); ?>">Genta<?php the_author_meta( 'twitter' ); ?></a>
                                    </div>
                                    <?php endif; ?>                                 
                                    <?php if ( get_the_author_meta( 'instagram' ) ) : ?>
                                    <div class="author-instagram">
                                        <span class="label"><?php _e('Instagram', 'piananotizie'); ?></span>
                                        <a target="_blank" href="https://instagram.com/<?php echo esc_attr(get_the_author_meta( 'instagram' )); ?>"><?php the_author_meta( 'instagram' ); ?></a>
                                    </div>
                                    <?php endif; ?>                                 
                                </div>
			</header><!-- .archive-header -->
                        
                        <h3><?php printf(__('Gli ultimi articoli di %s','piananotizie'), get_the_author_meta( 'first_name' ) ); ?></h3>
                        <div class="masonry-container" data-masonry-options='{"itemSelector": ".hentry" }' >
			<?php
                                    
                        
					/*
					 * Since we called the_post() above, we need to rewind
					 * the loop back to the beginning that way we can run
					 * the loop properly, in full.
					 */
					rewind_posts();

					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next page navigation.
				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
                        </div>

	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
