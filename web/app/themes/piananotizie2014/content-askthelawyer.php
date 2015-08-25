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
	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
                <span class="question-author"><?php printf(__('Inviato da %s'), get_post_meta(get_the_ID(),'question_author', true) );?></span>                
	</header><!-- .entry-header -->

	<div class="entry-content">
                      
            <?php if(is_single()): ?>
                <section id="domanda">
                    <h3 class="section-title"><?php _e('Question', 'piananotizie') ?>&nbsp;&colon;</h3>
                    <?php
                    /* translators: %s: Name of current post */              
                    the_content( sprintf(
                            __( 'Continue reading %s', 'piananotizie' ),
                            the_title( '<span class="screen-reader-text">', '</span>', false )
                    ) ); ?>
                </section>
                <section id="risposta">
                    <h3 class="section-title"><?php _e('Answer', 'piananotizie') ?>&nbsp;&colon;</h3>
                    <?php echo get_post_meta( get_the_ID(), 'expert_answer', true ); ?>
                </section>               
            <?php else: ?>
                <?php the_excerpt(); ?>
            <?php endif; ?>

            <?php 
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
		<?php //twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'piananotizie' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
