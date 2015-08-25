<?php /*
  YARPP Template: Thumbnails
  Description: Requires a theme which supports post thumbnails
  Author: mitcho (Michael Yoshitaka Erlewine)
 */ ?>
<h3><?php _e('Related Post', 'piananotizie') ?></h3>
<?php if (have_posts()): ?>
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php \FuoricentroStudio\WP\Themes\Piananotizie\post_cover(); ?>
            <header class="entry-header">
                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo get_the_date(); ?></time>
                <time class="updated screen-reader-text" datetime="<?php echo esc_attr(get_the_modified_date('c')) ?>"><?php echo get_the_modified_date(); ?></time>
                <span class="author vcard"><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo get_the_author(); ?></a></span>
                <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');?>
            </header><!-- .entry-header -->
            <div class="entry-content">
                <?php the_excerpt(); ?>
            </div><!-- .entry-content -->
            <footer class="entry-footer">
                <span class="cat-links"><?php echo get_the_category_list(); ?></span>
            </footer><!-- .entry-footer -->
        </article><!-- #post-## -->
    <?php endwhile; ?>
<?php else: ?>
    <p><?php _e('No related Post.', 'piananotizie'); ?></p>
<?php endif;
