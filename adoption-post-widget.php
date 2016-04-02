<?php

/* 
 * Plugin Name: Group Plugin Assignment
 * Plugin URI: https://phoenix.sheridanc.on.ca/~ccit3427/
 * Description: Showing how  our cool  widget work.
 * Author: Yahya Al-Mashni, Ajwad Rauf, Benjamin Sin
 * Assignment 2: Custom Plugin
 * Author URI: https://phoenix.sheridanc.on.ca/~ccit3427/
 * Version: 1.0 
 */

// Add featured image support
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
}

// First create the widget for the admin panel
class adoption_post_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_adoption_post_widget', 'description' => __( 'Displays custom post content in a widget', 'adoption-widget' ) );
		parent::__construct( 'adoption_post_widget', __( 'Adoption Data', 'adoption-widget' ), $widget_ops );
	}

	function form( $instance ) {
		$custom_post_id = ''; // Initialize the variable
		if (isset($instance['custom_post_id'])) {
			$custom_post_id = esc_attr($instance['custom_post_id']);
		};
		$show_custom_post_title  = isset( $instance['show_custom_post_title'] ) ? $instance['show_custom_post_title'] : true;
		$show_featured_image  = isset( $instance['show_featured_image'] ) ? $instance['show_featured_image'] : true;
		$apply_content_filters  = isset( $instance['apply_content_filters'] ) ? $instance['apply_content_filters'] : true;
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'custom_post_id' ); ?>"> <?php echo __( 'Adoption Data to Display:', 'adoption-widget' ) ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'custom_post_id' ); ?>" name="<?php echo $this->get_field_name( 'custom_post_id' ); ?>">
				<?php
					$args = array( 'post_type' => 'content_block', 'suppress_filters' => 0, 'numberposts' => -1, 'order' => 'ASC' );
					$content_block = get_posts( $args );
					if ($content_block) {
						foreach( $content_block as $content_block ) : setup_postdata( $content_block );
							echo '<option value="' . $content_block -> ID . '"';
							if( $custom_post_id == $content_block -> ID ) {
								echo ' selected';
								$widgetExtraTitle = $content_block -> post_title;
							};
							echo '>' . $content_block -> post_title . '</option>';
						endforeach;
					} else {
						echo '<option value="">' . __( 'No Adoption Data available', 'adoption-widget' ) . '</option>';
					};
				?>
				</select>
			</label>
		</p>
		
		<input type="hidden" id="<?php echo $this -> get_field_id( 'title' ); ?>" name="<?php echo $this -> get_field_name( 'title' ); ?>" value="<?php if ( !empty( $widgetExtraTitle ) ) { echo $widgetExtraTitle; } ?>" />

		<p>
			<?php
				echo '<a href="post.php?post=' . $custom_post_id . '&action=edit">' . __( 'Edit Adoption Data', 'adoption-widget' ) . '</a>' ;
			?>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['show_custom_post_title'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_custom_post_title' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_custom_post_title' ); ?>"><?php echo __( 'Show Post Title', 'adoption-widget' ) ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['show_featured_image'] ), true ); ?> id="<?php echo $this->get_field_id( 'show_featured_image' ); ?>" name="<?php echo $this->get_field_name( 'show_featured_image' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_featured_image' ); ?>"><?php echo __( 'Show featured image', 'adoption-widget' ) ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( (bool) isset( $instance['apply_content_filters'] ), true ); ?> id="<?php echo $this->get_field_id( 'apply_content_filters' ); ?>" name="<?php echo $this->get_field_name( 'apply_content_filters' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'apply_content_filters' ); ?>"><?php echo __( 'Do not apply content filters', 'adoption-widget' ) ?></label>
		</p> <?php 
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['custom_post_id'] = strip_tags( $new_instance['custom_post_id'] );
		$instance['show_custom_post_title'] = $new_instance['show_custom_post_title'];
		$instance['show_featured_image'] = $new_instance['show_featured_image'];
		$instance['apply_content_filters'] = $new_instance['apply_content_filters'];
		return $instance;
	}

	// Display the Adoption Data content in the widget area
	function widget($args, $instance) {
		extract($args);
		$custom_post_id  = ( $instance['custom_post_id'] != '' ) ? esc_attr($instance['custom_post_id']) : __( 'Find', 'adoption-widget' );
		// Add support for WPML Plugin.
		if ( function_exists( 'icl_object_id' ) ){ 
			$custom_post_id = icl_object_id( $custom_post_id, 'content_block', true );
		}
		// Variables from the widget settings.
		$show_custom_post_title = isset( $instance['show_custom_post_title'] ) ? $instance['show_custom_post_title'] : false;
		$show_featured_image  = isset($instance['show_featured_image']) ? $instance['show_featured_image'] : false;
		$apply_content_filters  = isset($instance['apply_content_filters']) ? $instance['apply_content_filters'] : false;
		$content_post = get_post( $custom_post_id );
		$post_status = get_post_status( $custom_post_id );
		$content = $content_post->post_content;
		if ( $post_status == 'publish' ) {
			// Display custom widget frontend
			if ( $located = locate_template( 'adoption-plugin.php' ) ) {
				require $located;
				return;
			}
			if ( !$apply_content_filters ) { // Don't apply the content filter if checkbox selected
				$content = apply_filters( 'the_content', $content);
			}
			echo $before_widget;
			if ( $show_custom_post_title ) {
				echo $before_title . apply_filters( 'widget_title',$content_post->post_title) . $after_title; // This is the line that displays the title (only if show title is set) 
			}
			if ( $show_featured_image ) {
				echo get_the_post_thumbnail( $content_post -> ID );
			}
			echo do_shortcode( $content ); // This is where the actual content of the custom post is being displayed
			echo "
					<section id='content'>
		  				<h1>What is your budget?</h1>
		  				<div class='cube'>
					    <div class='a'></div>
					    <div class='b'></div>
					    <div class='c'></div>
					    <div class='d'></div>
					    <div id='slider-range-min'></div>
					  	</div>
					  	<input type='text' id='amount' />
					</section>
				";
			echo $after_widget;
		}
	}
}

// Create the Adoption Data custom post type
function adoption_post_type_init() {
	$labels = array(
		'name' => _x( 'Adoption Data', 'post type general name', 'adoption-widget' ),
		'singular_name' => _x( 'Adoption Data', 'post type singular name', 'adoption-widget' ),
		'plural_name' => _x( 'Adoption Data', 'post type plural name', 'adoption-widget' ),
		'add_new' => _x( 'Add Adoption Data', 'block', 'adoption-widget' ),
		'add_new_item' => __( 'Add New Adoption Data', 'adoption-widget' ),
		'edit_item' => __( 'Edit Adoption Data', 'adoption-widget' ),
		'new_item' => __( 'New Adoption Data', 'adoption-widget' ),
		'view_item' => __( 'View Adoption Data', 'adoption-widget' ),
		'search_items' => __( 'Search Adoption Data', 'adoption-widget' ),
		'not_found' =>  __( 'No Adoption Data Found', 'adoption-widget' ),
		'not_found_in_trash' => __( 'No Adoption Data found in Trash', 'adoption-widget' )
	);
	$content_block_public = false; // added to make this a filterable option
	$options = array(
		'labels' => $labels,
		'public' => apply_filters( 'content_block_post_type', $content_block_public ),
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array( 'title','editor','revisions','thumbnail','author' )
	);
	register_post_type( 'content_block',$options );
}
add_action( 'init', 'adoption_post_type_init' );

function content_block_messages( $messages ) {
	$messages['content_block'] = array(
		0 => '', 
		1 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Adoption Data updated. <a href="%s">Manage Widgets</a>', 'adoption-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Adoption Data updated.', 'adoption-widget' ), esc_url( 'widgets.php' ) ),
		2 => __( 'Custom field updated.', 'adoption-widget' ),
		3 => __( 'Custom field deleted.', 'adoption-widget' ),
		4 => __( 'Adoption Data updated.', 'adoption-widget' ),
		5 => isset($_GET['revision']) ? sprintf( __( 'Adoption Data restored to revision from %s', 'adoption-widget' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Adoption Data published. <a href="%s">Manage Widgets</a>', 'adoption-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Adoption Data published.', 'adoption-widget' ), esc_url( 'widgets.php' ) ),
		7 => __( 'Block saved.', 'adoption-widget' ),
		8 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Adoption Data submitted. <a href="%s">Manage Widgets</a>', 'adoption-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Adoption Data submitted.', 'adoption-widget' ), esc_url( 'widgets.php' ) ),
		9 => sprintf( __( 'Adoption Data scheduled for: <strong>%1$s</strong>.', 'adoption-widget' ), date_i18n( __( 'M j, Y @ G:i' , 'adoption-widget' ), strtotime(isset($post->post_date) ? $post->post_date : null) ), esc_url( 'widgets.php' ) ),
		10 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Adoption Data draft updated. <a href="%s">Manage Widgets</a>', 'adoption-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Adoption Data draft updated.', 'adoption-widget' ), esc_url( 'widgets.php' ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'content_block_messages' );

// Add the ability to display the Adoption Data in a reqular post using a shortcode
function adoption_post_widget_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '',
		'slug' => '',
		'class' => 'content_block',
		'suppress_content_filters' => 'no',
        'title' => 'no',
        'title_tag' => 'h3'
	), $atts ) );

	if ( $slug ) {
		$block = get_page_by_path( $slug, OBJECT, 'content_block' );
		if ( $block ) {
			$id = $block->ID;
		}
	}

	$content = "";
	
	if( $id != "" ) {
		$args = array(
			'post__in' => array( $id ),
			'post_type' => 'content_block',
		);

		$content_post = get_posts( $args );

		foreach( $content_post as $post ) :
			$content .= '<div class="'. esc_attr($class) .'" id="adoption_post_widget-' . $id . '">';
			if ( $title === 'yes' ) {
				$content .= '<' . esc_attr( $title_tag ) . '>' . $post->post_title . '</' . esc_attr( $title_tag ) . '>'; 
			}
			if ( $suppress_content_filters === 'no' ) {
				$content .= apply_filters( 'the_content', $post->post_content);
			} else {
				$content .= $post->post_content; 
			}
			$content .= '</div>';
		endforeach;
	}

	return $content;
}
add_shortcode( 'content_block', 'adoption_post_widget_shortcode' );

// Only add content_block icon above posts and pages
function adoption_add_content_block_button() {
	global $current_screen;
    if ( ( 'content_block' != $current_screen -> post_type ) && ( 'toplevel_page_revslider' != $current_screen -> id ) ) {
		add_action( 'media_buttons', 'add_content_block_icon' );
		add_action( 'admin_footer', 'add_content_block_popup' );
	}
}
add_action( 'admin_head', 'adoption_add_content_block_button' );