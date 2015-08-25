<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

        <div id="tertiary" role="complementary">
            
            <?php if ( is_active_sidebar( 'sponsor-footer' ) ) : ?>
            <section id="sponsors" class="full-band" >
                    <h3 class="band-title"><?php _e('Reclame', 'piananotizie'); ?></h3>
                    <ul class="band-content">
                        <?php dynamic_sidebar( 'manchette-left' ); ?>
                        <?php dynamic_sidebar( 'sponsor-footer' ); ?>                 
                    </ul>
            </section>
            <?php endif; ?>
            
            <?php if(is_front_page()): ?>
                <div class="full-band contrast-light">
                    <div class="band-content">
                    <?php $the_query = new WP_Query( array('post_type'=>'editoriale','posts_per_page'=>1) ); ?>
                    <?php if ( $the_query->have_posts() ) : ?>
                        <?php add_filter( 'excerpt_length', '\FuoricentroStudio\WP\Themes\Piananotizie\editoriale_excerpt_length', 999 ); ?>
                        <section id="editoriale">
                            <h3>Editoriale</h3>  
                              <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                <article <?php post_class() ?> > 
                                    <footer>
                                        <div class="entry-author">
                                            <div class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?> </div>
                                            <span>di</span>
                                            <span class="display_name"><?php the_author_meta('display_name') ?></span>    
                                        </div>
                                    </footer>
                                    <header>
                                        <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                    </header>
                                    <div class="entry-summary">
                                        <?php the_excerpt(); ?>
                                        <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Continue reading','piananotizie'); ?></a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                            <a class="link-to-archive" href="<?php echo get_post_type_archive_link('editoriale'); ?>"><?php _e('Read all editorials','piananotizie') ?></a>
                            <?php wp_reset_postdata(); ?>
                        </section>  
                        <?php remove_filter('excerpt_length', '\FuoricentroStudio\WP\Themes\Piananotizie\editoriale_excerpt_length'); ?>
                    <?php endif; ?>    

                    <?php $the_query = new WP_Query( array('post_type'=>'curiosita','posts_per_page'=>2) ); ?>
                    <?php if ( $the_query->have_posts() ) : ?>
                        <?php add_filter( 'excerpt_length', '\FuoricentroStudio\WP\Themes\Piananotizie\curiosita_excerpt_length', 999 ); ?>
                        <section id="ultime-curiosita">
                            <h3>C'era una volta la piana...</h3>
                            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                            <article <?php post_class() ?> > 
                                <header>
                                    <a class="post-thumbnail" href="<?php the_permalink() ?>"><?php the_post_thumbnail('wide-thumbnail'); ?></a>
                                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                </header>
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                    <a class="readmore" href="<?php the_permalink(); ?>"><span class="screen-reader-text"><?php _e('Continue reading','piananotizie'); ?></span></a>
                                </div>
                            </article>
                            <?php endwhile; ?>
                            <a class="link-to-archive" href="<?php echo get_post_type_archive_link('curiosita'); ?>"><?php _e('Read all articles','piananotizie') ?></a>                 
                            <?php wp_reset_postdata(); ?>
                        </section>     
                        <?php remove_filter('excerpt_length', '\FuoricentroStudio\WP\Themes\Piananotizie\curiosita_excerpt_length'); ?>
                    <?php endif; ?>

                    </div>
                </div>
                <?php if( FuoricentroStudio\WP\Themes\Piananotizie\cookie_level(2) ): ?>
                <div id="stream-social" class="full-band">
                    <h3 class="band-title"><?php _e('Piananotizie 2.0','piananotizie'); ?></h3>
                    <div class="band-content">
                        <div class="float-wrapper">
                            <aside id="twitter-timeline">
                                <h4><?php _e('Twitter updates', 'piananotizie'); ?></h4>
                                <ul class="twitter-timeline-js"><span class="loading"><?php _e('Loading...','piananotizie'); ?></span></ul>
                                <div class="twitter-follow">Segui<a href="http://www.twitter.com/Piananotizie"><?php _e('Piananotizie','piananotizie'); ?></a></div>
                            </aside>
                        </div>
                        <div class="float-wrapper">
                            <aside id="facebook-timeline">
                                <h4><?php _e('Facebook', 'piananotizie'); ?></h4>
                                <div class="fb-page" data-href="https://www.facebook.com/pages/Piananotizie/374799959225296" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/pages/Piananotizie/374799959225296"><a href="https://www.facebook.com/pages/Piananotizie/374799959225296"><?php _e('Piananotizie','piananotizie'); ?></a></blockquote></div></div>
                            </aside>
                        </div>
                        <div class="float-wrapper">
                            <aside id="instagram-timeline">
                                <h4><?php _e('Instagram', 'piananotizie'); ?></h4>
                                <div class="jquery-instagram instagram-photos-st" id="instagram-feed" data-hash="piananotizie" data-clientId="a861378a283446738fc93a81d28c80c5" data-count="9"></div>
                            </aside>
                        </div>   
                        <div class="mobile-social-link">
                            <a class="twitter-link" href="https://twitter.com/Piananotizie">Twitter</a>
                            <a class="facebook-link" href="https://www.facebook.com/pages/Piananotizie/374799959225296">Facebook</a>
                            <a class="instagram-link" href="https://instagram.com/piananotizie/">Instagram</a>
                        </div>
                    </div>
                </div>            
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'widget-footer' ) ) : ?>
                <div id="widget-footer" class="full-band contrast-light" >
                    <div class="band-content">
                        <?php dynamic_sidebar( 'widget-footer' ); ?>                 
                    </div>
                </div>
                <?php endif; ?>               

                <div id="askthelawyer" class="full-band widget">
                    <div class="band-content">
                        <h3><?php _e('Ask the lawyer','piananotizie'); ?></h3>
                        <div class="left-content">
                            <p class="description">
                                <span><?php _e('Hai un dubbio su una questione legale? Allora chiedilo all\'avvocato!','piananotizie'); ?></span>
                                <span><?php _e('Send an email to ','piananotizie')?><a href="mailto:chiedilo.avvocato@gmail.com">chiedilo.avvocato@gmail.com</a><?php _e('e ti risponderanno gli avvocati Bini e Passalacqua.','piananotizie'); ?></span>
                            </p>           
                            <a class="link-to-archive" href="<?php echo get_post_type_archive_link('askthelawyer'); ?>"><?php _e('Send your answer', 'piananotizie') ?></a>
                        </div>
                        <div id="askthelawyer-answers" class="right-content">
                            <h4><?php _e('Yours last questions', 'piananotizie'); ?></h4>
                            <ul class="answer list">
                            <?php foreach(get_posts(array('post_type'=>'askthelawyer')) as $post) : ?>
                                <li><a href="<?php the_permalink($post->ID) ?>"><?php echo apply_filters('the_title', $post->post_title); ?></a></li>
                            <?php endforeach; ?>	               
                            </ul>
                            <a class="link-to-archive" href="<?php echo get_post_type_archive_link('askthelawyer'); ?>#askthelawyer-answers-list"><?php _e('View the questions', 'piananotizie'); ?></a>
                        </div>
                    </div>
                </div>        

                <?php if ( is_active_sidebar( 'siti-amici' ) ) : ?>
                <div id="friend-sites" class="full-band" >
                    <h3 class="band-title">Siti Amici</h3>
                    <ul class="band-content">
                        <?php dynamic_sidebar( 'siti-amici' ); ?>
                    </ul>
                </div>
                <?php endif; ?>            
            
            <?php endif; ?>
            
        </div>
        
	<footer id="colophon" class="site-footer full-band contrast-medium" role="contentinfo">
		<div class="site-info content-wrapper band-content">
                    <div id="info-redazione">
                        <p>&copy; 2015 - Settepuntozero SRL - P.IVA 06308920484</p>
                        <p>Via delle Robinie, 22 - 50019 - Sesto Fiorentino - FI</p>
                        <p>Telefono: +39 055.4254478</p>
                    </div>
                    <div id="info-testata">
                        <p>Piananotizie.it</p>
                        <p>Testata registrata al Tribunale di Firenze, n. 5906/2013</p>
                        <p>Email: redazione@piananotizie.it</p>
                    </div>
                    <div id="social">
                        <h3>Piananotizie 2.0</h3>
                        <?php wp_nav_menu( array( 'theme_location' => 'social', 'link_before' => '<span class="screen-reader-text">', 'link_after' => '</span>', ) ); ?>
                    </div>
		</div><!-- .site-info -->
                <div class="full-band contrast-heavy">
                    <div class="band-content content-wrapper">
                        <div class="align-left">
                            <?php wp_nav_menu( array( 'theme_location' => 'footer-links', 'menu_class' => 'nav-menu' ) ); ?>
                            <script type="text/javascript"> (function () { var c = document.createElement('link'); c.type = 'text/css'; c.rel = 'stylesheet'; c.href = 'http://images.dmca.com/badges/dmca.css?ID=04c3df64-419f-4490-b127-5daadbf691c0'; var h = document.getElementsByTagName("head")[0]; h.appendChild(c); })();</script>
                            <div id="DMCA-badge">
                                <div class="dm-1 dm-1-b" style="left: 0px; background-color: rgb(89, 174, 214); border-color: rgb(89, 174, 214);">
                                    <a href="http://www.dmca.com/" title="DMCA">DMCA</a>
                                </div>
                                <div class="dm-2 dm-2-b" style="background-color: rgb(255, 255, 255); border-color: rgb(255, 255, 255);">
                                    <a href="http://www.dmca.com/Protection/Status.aspx?ID=04c3df64-419f-4490-b127-5daadbf691c0" title="DMCA" style="color: rgb(89, 174, 214);">PROTECTED</a>
                                </div>
                            </div>
                        </div>
                        <div id="credits" class="align-right">
                            <span>Made by <a href="http://www.fuoricentrostudio.com" target="_blank" class="fuoricentro">fuoricentrostudio</a></span>
                        </div>
                    </div>
                </div> 
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
