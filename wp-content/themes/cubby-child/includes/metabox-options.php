<?php

/**
 * Calls the class on the post edit screen.
 */
function cubby_call_metaboxClass() {
    new Cubby_metaboxClass();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'cubby_call_metaboxClass' );
    add_action( 'load-post-new.php', 'cubby_call_metaboxClass' );
}

/** 
 * The Class.
 */
class Cubby_metaboxClass {

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
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'some_meta_box_name'
			,__( 'Cubby Metabox Options', 'cubby' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'advanced'
			,'high'
		);
            }
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
		if ( ! isset( $_POST['cubby_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['cubby_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'cubby_inner_custom_box' ) )
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
		$show_breadcrumb              = sanitize_text_field( $_POST['cubby_show_breadcrumb'] );
		$cubby_right_sidebar          = sanitize_text_field( $_POST['cubby_right_sidebar'] );

		

		// Update the meta field.
		update_post_meta( $post_id, '_cubby_show_breadcrumb', $show_breadcrumb );
		update_post_meta( $post_id, '_cubby_right_sidebar', $cubby_right_sidebar );
	
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'cubby_inner_custom_box', 'cubby_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
	    $show_breadcrumb = get_post_meta( $post->ID, '_cubby_show_breadcrumb', true );
		$top_slider      = get_post_meta( $post->ID, '_cubby_top_slider', true );
		$select_y = "";
		$select_n = "";
		if($show_breadcrumb == 1 || $show_breadcrumb == ""){$select_y = 'selected="selected"';}else{$select_n = 'selected="selected"';}

		// Display the form, using the current value.
		echo '<p class="meta-options"><label for="cubby_show_breadcrumb"  style="display: inline-block;width: 150px;">';
		_e( 'Show Breadcrumb :', 'cubby' );
		echo '</label> ';
		echo '<select name="cubby_show_breadcrumb" id="cubby_show_breadcrumb"><option '.$select_y.' value="1">Yes</option><option '.$select_n .' value="0">No</option></select></p>';
		

		
		$cubby_right_sidebar      = get_post_meta( $post->ID, '_cubby_right_sidebar', true );
		$select_y = "";
		$select_n = "";
		if($cubby_right_sidebar == 1 || $cubby_right_sidebar == ""){$select_y = 'selected="selected"';}else{$select_n = 'selected="selected"';}
		echo '<p class="meta-options"><label for="cubby_right_sidebar"  style="display: inline-block;width: 150px;">';
		_e( 'Show Right Sidebar :', 'cubby' );
		echo '</label> ';
		echo '<select name="cubby_right_sidebar" id="cubby_right_sidebar"><option '.$select_y.' value="1">Yes</option><option '.$select_n .' value="0">No</option></select></p>';
		
		
		
	}
}