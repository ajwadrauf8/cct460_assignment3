<?php

/* Display a notice that can be dismissed */

function adoption_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	$screen = get_current_screen();
	if ( ! get_user_meta($user_id, 'adoption_notice_hidden') && ( $screen->id == 'edit-content_block' || $screen->id == 'content_block' ) ) {
		echo '<div class="updated" style="border-color: #00b1ff;"><p>'; 
		printf(__('You are using the Adoption Widget plugin.<a href="%1$s" style="float:right;">Hide Notice</a>'), '?post_type=content_block&adoption_hide_notice=yes');
		echo "</p></div>";
	}
}
add_action('admin_init', 'adoption_hide_notice');

function adoption_hide_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['adoption_hide_notice']) && 'yes' == $_GET['adoption_hide_notice'] ) {
		add_user_meta($user_id, 'adoption_notice_hidden', 'yes', true);
	}
}
add_action('admin_notices', 'adoption_admin_notice');