<?php

namespace FuoricentroStudio\WP\Helpers;

 /** 
 * The Class.
 */
class MetaBox {

        public $name;

        public $args = array(
            'title'=>'',
            'post_type'=>'post',
            'position'=>'side',
            'priority'=>'default'
        );
        
        public $fields = array();
            
        public function __construct($name, $fields, $args='') {
            $this->name = $name;
            
            foreach ($fields as $name=>$options){
                $this->fields[] = new MetaBoxField($name, $options);
            }
            
            $this->args = wp_parse_args($args, $this->args);
            
            add_action( 'add_meta_boxes', array( $this, 'add' ) );
            add_action( 'save_post', array( $this, 'save' ) );
        }
        
	/**
	 * Adds the meta box container.
	 */
	public function add() {
		add_meta_box(
			$this->name
			,$this->args['title']
			,array( $this, 'render' )
			,$this->args['post_type']
			,$this->args['position']
			,$this->args['priority']
		);              
	}
        


        public static function has_permission($post_id){
		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return false;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return false;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return false;
		}            
                
                return true;
        }
        
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
                foreach($this->fields as $field){
                    if(!$field->verify_nonce() || !self::has_permission($post_id)){
                        continue;
                    }
                    
                    $field->save($post_id);
                    
                }
            
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public  function render( $post ) {
	
             foreach($this->fields as $field){

                 $field->render($post->ID);
                
             }
                
	}
}


class MetaBoxField {
    
        public $id;
        public $type = 'text';
        public $name;
        public $nonce;
        public $label;
        public $default;
        
        public function __construct($name, $args=array()){
            
            $this->name = $name;
            
            if(is_array($args)){
                foreach($args as $property=>$value){
                    if(property_exists($this, $property)){
                        $this->{$property} = $value;
                    }
                }
            } else {
                $this->label = $args;
            }

            if(empty($this->id)){
                $this->id = $this->name;
            }            
            
            if(empty($this->nonce)){
                $this->nonce = $this->name.'_nonce';
            }
        }
        
        public function verify_nonce(){
		// Verify that the nonce is valid.
		return isset( $_POST[$this->nonce]) && wp_verify_nonce( $_POST[$this->nonce], $this->name );
        }        
        
        public function save($post_id) {
           
            if('checkbox' !== $this->type){
                return update_post_meta( $post_id, $this->name, $_POST[$this->name]);                                
            } else {
                return update_post_meta( $post_id, $this->name, (int)isset($_POST[$this->name]) );                                
            }
            
        }
        
        public function render($post_id){
            
		// Add an nonce field so we can check for it later.
		wp_nonce_field( $this->name, $this->nonce );

                $values = get_post_meta( $post_id, $this->name );
                
                if(empty($values)){
                    $value = $this->default;
                } else {
                    $value = $values[0];
                }
                
                echo '<div class="meta-box-field">';
                
                if('checkbox' === $this->type){
                    $template = '<input type="%1$s" id="%4$s" name="%2$s" value="%3$s" %6$s/><label for="%4$s" >%5$s</label>';
                } elseif('textarea' === $this->type) {
                    $template = '<textarea id="%4$s" name="%2$s" %6$s/>%3$s</textarea><label for="%4$s" >%5$s</label>';
                } else { 
                   $template = '<label for="%4$s" >%5$s</label><input type="%1$s" id="%4$s" name="%2$s" value="%3$s"  />';
                }
                
                        
                printf($template, 
                        esc_attr($this->type),          //1
                        esc_attr($this->name),          //2
                        esc_attr($value),               //3
                        esc_attr($this->id),            //4
                        esc_html($this->label),         //5
                        $value?'checked="checked"':''   //6
                        );
                
                echo '<hr /></div>';
                
        }
        
        
}