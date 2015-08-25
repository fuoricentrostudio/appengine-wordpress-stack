<?php
/**
 * Template Name: Author Archive
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

		// End the loop.
		endwhile;
                
                $blogusers = get_users( array( 'blog_id'=>1, 'orderby'=>'registered', 'fields'=>'all', 'exclude'=>array(1,2)) );
                                
                // Array of WP_User objects.
                foreach ( $blogusers as $user ) : ?>
                    <div class="author-profile">
                                <div class="title-container">
                                    <?php echo get_avatar( $user->user_email, 256 ); ?>
                                    <h1 class="archive-title">
                                            <?php
                                                    printf( __( 'Autore <span>%s</span>', 'piananotizie' ), get_the_author_meta('display_name', $user->ID) );
                                            ?>
                                    </h1>
                                </div>
				<?php if ( get_the_author_meta( 'description', $user->ID ) ) : ?>
				<p class="author-description"><?php the_author_meta( 'description', $user->ID ); ?></p>
				<?php endif; ?>
                                <div id="author-social">
                                    <?php if ( get_the_author_meta( 'email', $user->ID ) ) : ?>
                                    <div class="author-email">
                                        <span class="label"><?php _e('Email', 'piananotizie'); ?></span>
                                        <a target="_blank" href="mailto:<?php echo esc_attr(get_the_author_meta( 'email', $user->ID )); ?>"><?php the_author_meta( 'email', $user->ID); ?></a>
                                    </div>
                                    <?php endif; ?> 
                                    <?php if ( get_the_author_meta( 'twitter', $user->ID ) ) : ?>
                                    <div class="author-twitter">
                                        <span class="label"><?php _e('Twitter', 'piananotizie'); ?></span>
                                        <a target="_blank" href="https://twitter.com/<?php echo esc_attr(get_the_author_meta( 'twitter', $user->ID )); ?>"><?php the_author_meta( 'twitter', $user->ID ); ?></a>
                                    </div>
                                    <?php endif; ?>                                 
                                    <?php if ( get_the_author_meta( 'instagram', $user->ID ) ) : ?>
                                    <div class="author-instagram">
                                        <span class="label"><?php _e('Instagram', 'piananotizie'); ?></span>
                                        <a target="_blank" href="https://instagram.com/<?php echo esc_attr(get_the_author_meta( 'instagram', $user->ID )); ?>"><?php the_author_meta( 'instagram', $user->ID ); ?></a>
                                    </div>
                                    <?php endif; ?>                                 
                                </div>
                    </div>
                <?php endforeach; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
