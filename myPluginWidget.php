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

?>
<?php
//correctly enqueuing and registering an external stylesheet plug_style.css
function scripts_plug(){

wp_register_style('plug_style', plugins_url('/plug_style.css' , __FILE__));
wp_enqueue_style('plug_style');
}
add_action( 'wp_enqueue_scripts', 'scripts_plug' );

function my_short_code_button( $atts){

extract( shortcode_atts(
	//variables taken at button shortcode creation for button color, text color and button text
	array(
		'colortxt'=>'#ffffff',
		'colorbutton'=>'#ffffff',
		'link'=>'http://google.com',
		'buttontxt'=>'Button Text',
		), $atts)
		);
//exiting php code in to html. php varables in CSS code as they are active/dynamic, other button CSS code is in the external stylesheet
?>
	<html>
			<style> 
				input[type="submit"] {
					color: <?php echo $colortxt;?>;
					background: <?php echo $colorbutton;?>;
				};						
			</style>
		<body>
<?php

	//outputting html code with variables passed on from user input at shortcode creation
	return
	'<form action="'.$link.'"><input type="submit" value="'.$buttontxt.'"></form>';
	
	?>
		</body>
	</html>
<?php
}

	add_shortcode( 'my_short_code_button', 'my_short_code_button' );


	// shortcode function
	function my_short_code_div( $atts , $content=null ) {

	//variable taken at div shortcode creation for  color,

	extract(shortcode_atts(array('coloring'=>'#ffffff',), $atts));

	//exiting php code in to html. php varables in CSS code as they are active/dynamic, other button CSS code is in the external stylesheet
	?>
	<html>
			<style>
				.my_shortcode{color:  <?php echo $coloring; ?>};
			</style>
		<body>
<?php
		//outputting html code with variables passed on from user input at shortcode creation
		return'<div class="my_shortcode">'.do_shortcode($content).'</div>';
?>
		</body>
	</html>
<?php
}
	add_shortcode( 'my_short_code_div', 'my_short_code_div' );
