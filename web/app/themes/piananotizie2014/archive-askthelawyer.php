<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
                                <div class="taxonomy-description">
                                    <p><?php _e('Lo Studio Legale Bini-Passalacqua offre la propria competenza nella gestione di vertenze '.
                                                'giudiziali e stragiudiziali in ambito civile e penale. Tra le altre vengono trattate pratiche '.
                                                'relative a recupero del credito per aziende e privati, diritto del lavoro, diritto condominiale '.
                                                'e delle locazioni, sfratti, esecuzione forzata, risarcimento del danno, sinistri stradali, '.
                                                'successioni mortis causa, contratti, diritto commerciale e fallimentare, opposizione a '.
                                                'sanzione amministrativa. Lo Studio Legale si trova a Sesto Fiorentino (FI) in Via A. Gramsci n°399 '.
                                                '– <a href="www.lexfirenze.it" target="_blank">www.lexfirenze.it</a>', 'piananotizie'); ?></p>
                                </div>
                                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/askthelawyer.jpg" />
			</header><!-- .page-header -->

                        <div id="form-askthelawyer">
                            <div class="form-description">
                                <h3><?php _e('Fill the form and send us your request', 'piananotizie'); ?></h3>
                                <p><?php _e('You have a question about a legal matter? Then ask the lawyer! Send an email to <a href="mailto:chiedi.avvocato@gmail.com" >chiedi.avvocato@gmail.com</a> or fill out the form and we will respond lawyers Bini and Passalacqua‏', 'piananotizie'); ?></p>
                            </div>
                            <?php echo do_shortcode('[contact-form-7 id="78594" title="Modulo di contatto 1"]'); ?>
                        </div>
                        
                        <?php if ( have_posts() ) : ?>
                        <div id="askthelawyer-answers-list" class="archive-items">
                            <h3><?php _e('Your last question', 'piananotizie'); ?></h3>
                            <?php while ( have_posts() ) : the_post(); 
                                    /*
                                     * Include the Post-Format-specific template for the content.
                                     * If you want to override this in a child theme, then include a file
                                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                     */
                                    get_template_part( 'content', get_post_type() );

                            // End the loop.
                             endwhile; ?>
                        </div>
                        <?php endif; ?>
			<?php
                        // Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'piananotizie' ),
				'next_text'          => __( 'Next page', 'piananotizie' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'piananotizie' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
