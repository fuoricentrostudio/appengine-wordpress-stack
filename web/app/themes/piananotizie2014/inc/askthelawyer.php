<?php

namespace FuoricentroStudio\WP\AskTheLawyer;

function register(){

register_post_type('askthelawyer', array(
       'label' => 'Domande',
       'description' => '',
       'public' => true,
       'show_ui' => true,
       'show_in_menu' => true,
       'capability_type' => 'post',
       'map_meta_cap' => true,
       'hierarchical' => false,
       'rewrite' => array('slug' => 'askthelawyer', 'with_front' => true),
       'query_var' => true,
       'has_archive' => true,
       'supports' => array('title', 'editor', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'thumbnail', 'author'),
       'labels' => array(
           'name' => 'Chiedi all\'avvocato',
           'singular_name' => 'Domanda Avvocato',
           'menu_name' => 'Domande Avvocato',
           'add_new' => 'Add Domanda',
           'add_new_item' => 'Add New Domanda',
           'edit' => 'Edit',
           'edit_item' => 'Edit Domanda',
           'new_item' => 'New Domanda',
           'view' => 'View Domanda',
           'view_item' => 'View Domanda',
           'search_items' => 'Search Domande',
           'not_found' => 'No Domande Found',
           'not_found_in_trash' => 'No Domande Found in Trash',
           'parent' => 'Parent Domanda',
       )
           )
   );

}

add_action('init', __NAMESPACE__.'\\register');

/**
 * Adds Foo_Widget widget.
 */
class Widget extends \WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'askthelawyer_widget', // Base ID
			__('Ask the lawyer', 'piananotizie'), // Name
			array( 'description' => __( 'Show widget ask the lawyer', 'piananotizie' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
     	        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
                echo '<div class="left-content">';
                echo '<p class="description">'.$instance['description'].'</p>';
                echo '<a class="button arrow" href="'.home_url('askthelawyer').'">'.__('Send us your question','piananotizie').'</a></div>';
                echo '<div id="askthelawyer-answers" class="right-content"><h4>'.__('Your last question','piananotizie').'</h4><ul class="answer list">';
                foreach(get_posts(array('post_type'=>'askthelawyer')) as $post){
                    echo '<li><a href="'.post_permalink($post->ID).'">'.apply_filters('the_title', $post->post_title).'</a></li>';
                } 		               
                echo '</ul>';
                echo '<a class="button arrow" href="'.trailingslashit(home_url('askthelawyer')).'#askthelawyer-answers-list">'.__('View all questions', 'piananotizie').'</a></div>';
                '</div>';
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'piananotizie' );
		}
		if ( isset( $instance[ 'description' ] ) ) {
			$description = $instance[ 'description' ];
		}
		else {
			$description = '';
		}                
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'piananotizie' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'piananotizie' ); ?></label> 
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>                
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
                $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? $new_instance['description'] : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget

add_action( 'widgets_init', function() { register_widget( __NAMESPACE__.'\\Widget' ); } );


/**
 * Calls the class on the post edit screen.
 */
function call_box() {
    new Box();
}

if ( is_admin() ) {
    add_action( 'load-post.php', __NAMESPACE__.'\\call_box' );
    add_action( 'load-post-new.php', __NAMESPACE__.'\\call_box' );
}

/** 
 * The Class.
 */
class Box {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		add_meta_box(
			'question_author'
			,__( 'Question author', 'piananotizie' )
			,array( $this, 'render_meta_box_content' )
			,'askthelawyer'
			,'side'
			,'default'
		);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['question_author_nonce'] ) )
			return $post_id;

		$nonce = $_POST['question_author_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'question_author' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
                if(isset($_POST['question_author'])){
                    $mydata = sanitize_text_field( $_POST['question_author'] );

                    // Update the meta field.
                    update_post_meta( $post_id, 'question_author', $mydata );
                }
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'question_author', 'question_author_nonce' );

		// Display the form, using the current value.
		echo '<input type="text" id="question_author"  name="question_author" value="'.esc_attr(get_post_meta( $post->ID, 'question_author', true )).'" />';
		//echo '&nbsp;<label for="question_author">'.__( 'Autore domanda', 'piananotizie' ).'</label>';
	}
}
