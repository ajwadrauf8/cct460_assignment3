<?php

function adoption_add_meta_box() {
	add_meta_box(
		'adoption_sectionid',
		__( 'Adoption Data Information', 'adoption-widget' ),
		'adoption_meta_box',
		'content_block',
		'side'
	);
}
add_action( 'add_meta_boxes', 'adoption_add_meta_box' );

function adoption_meta_box( $post ) {
	wp_nonce_field( 'adoption_meta_box', 'adoption_meta_box_nonce' );
	$value = get_post_meta( $post->ID, '_content_block_information', true );
	echo '<textarea id="adoption_content_block_information" cols="40" rows="4" name="adoption_content_block_information" style="height: 8em; width: 100%;">' . esc_attr( $value ) . '</textarea>';
}

function adoption_save_postdata( $post_id ) {
	if ( ! isset( $_POST['adoption_meta_box_nonce'] ) )
		return $post_id;

	$nonce = $_POST['adoption_meta_box_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'adoption_meta_box' ) )
		return $post_id;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;

	if ( 'content_block' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
	}

	$content_block_information = sanitize_text_field( $_POST['adoption_content_block_information'] );
	update_post_meta( $post_id, '_content_block_information', $content_block_information );
}
add_action( 'save_post', 'adoption_save_postdata' );

// Add Adoption Data information column to overview
function adoption_modify_material_table( $column ) {
	$column['content_block_information'] = __( 'Adoption Data Information', 'adoption-widget' );
	return $column;
}
add_filter( 'manage_edit-content_block_columns', 'adoption_modify_material_table' );

function adoption_modify_post_table_row( $column_name, $post_id ) {
	$custom_fields = get_post_custom( $post_id );
	switch ( $column_name ) {
		case 'content_block_information' :
			if ( !empty( $custom_fields['_content_block_information'][0] ) ) {
				echo $custom_fields['_content_block_information'][0];
			}
		break;
	}
}
add_action( 'manage_posts_custom_column', 'adoption_modify_post_table_row', 10, 2 );