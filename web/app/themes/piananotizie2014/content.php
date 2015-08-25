<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        
        
        <?php if(!is_single()){
                \FuoricentroStudio\WP\Themes\Piananotizie\post_cover(); 
            }
        ?>

	<header class="entry-header">
		<?php if ( is_single() ) : ?>
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php printf(__('Post %1$s at %2$s', 'piananotizie'), get_the_date(), get_the_time('G:i')); ?></time>
                        <span class="cat-links"><?php echo get_the_category_list(); ?></time>
		<?php else :?>
                        <time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
                        <time class="updated screen-reader-text" datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ) ?>"><?php echo get_the_modified_date(); ?></time>
                        <?php if (get_the_author()) :?>
                            <span class="author vcard"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo get_the_author(); ?></a></span>
                        <?php endif; ?>
                <?php        
                        the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if (is_single()) {
                    \FuoricentroStudio\WP\Themes\Piananotizie\post_teaser();
                    the_content(null , true);
                }
                else {
                    the_excerpt();
                } 
                    
                    wp_link_pages( array(
                            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'piananotizie' ) . '</span>',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'piananotizie' ) . ' </span>%',
                            'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );
		?>
	</div><!-- .entry-content -->

        <footer class="entry-footer">
            <?php if ( is_single() ) : ?>
                <?php $tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'piananotizie' ) );
                if ( $tags_list ) {
                        printf( '<div class="tags-links"><span>%1$s </span><div class="tags-list">%2$s</div></div>',
                                _x( 'Tags', 'Used before tag names.', 'piananotizie' ),
                                $tags_list
                        );
                } ?>
                <div id="share-on-social"><?php FuoricentroStudio\WP\Themes\Piananotizie\social_share_link(); ?></div>
                <?php if(get_the_author()): ?>
                <div class="author-info">
                    <div class="author-avatar" ><?php echo get_avatar( get_the_author_meta( 'user_email' )); ?></div>
                    <h1><?php _e('Author', 'piananotizie'); ?></h1>
                    <h2><?php echo get_the_author(); ?></h2>
                </div>
                <?php endif;?>
            <?php else: ?>
                    <span class="cat-links"><?php echo get_the_category_list(); ?></span>
            <?php endif; ?>
            <?php edit_post_link( __( 'Edit', 'piananotizie' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
        
</article><!-- #post-## -->
