<?php 
/** 
* Plugin Name: Shortcode Plugin
* Description: Fibonacci shortcode plugin
*/

// ADD SHORTCODE
function generate_fibonacci( $length ) {
	if ($length == 1) 
		$fibonacci = [0];
	else {
		$fibonacci = [0, 1];
		for ($i = 2; $i < $length; $i++) {   
			$fibonacci [] = $fibonacci[$i-2] + $fibonacci[$i-1];
		}
	}
	return $fibonacci;
}

function fibonacci_shortcode( $atts , $content = null ) {
	// Declare attributes
	$atts = shortcode_atts(
		array(
			'length' => '0',
		),
		$atts
	);
	
	//Handle invalid length attribute
	if ($atts[length] <= 0)
		return "Please enter the length greater than 0";
	//Generate fibonacci sequence
	else {
		$fibonacci = generate_fibonacci($atts[length]);
		return '<p class="normal-sequence">' . implode(" ", $fibonacci) . '</p>';
	}
}

function fibonacci_reverse_shortcode( $atts , $content = null ) {
	// Declare attributes
	$atts = shortcode_atts(
		array(
			'length' => '0',
		),
		$atts
	);
	
	//Handle invalid length attribute
	if ($atts[length] <= 0)
		return "Please enter the length greater than 0";
	//Generate reversed fibonacci sequence
	else {
		$fibonacci = generate_fibonacci($atts[length]);
		$reversed_fibonacci = array_reverse($fibonacci);
		return '<p class="reversed-sequence">' . implode(" ", $reversed_fibonacci) . '</p>';
	}
}

add_shortcode( 'fibonacci', 'fibonacci_shortcode' );
add_shortcode( 'fibonacci-reverse', 'fibonacci_reverse_shortcode' );

// LOAD JS FILE AND LOCALIZE DATA TO JS FILE
function my_enqueue() {
    wp_enqueue_script( 'shortcode-plugin-js', plugins_url( '/shortcode-plugin.js', __FILE__ ), array('jquery') );
	wp_localize_script( 'shortcode-plugin-js', 'php_object',
		array( 
			'post_url' => get_site_url() . '/wp-json/teamit/fibonaaci-sequence',
			'post_id' => get_the_ID(),
		) 
	);
}
add_action( 'wp_enqueue_scripts', 'my_enqueue' );

// REST API: SAVE SEQUENCES TO POST META
add_action('rest_api_init', function () {
	register_rest_route( '/teamit/', '/fibonaaci-sequence/', array(
		'methods'  => 'POST',
		'callback' => 'save_to_post_meta'
	));
});

function save_to_post_meta($request) {
	$value = $_POST['value'];
	$post_id = $_POST['postID'];
	$sequenceType = $_POST['sequenceType'];
	if ($sequenceType == 'normal') {
		update_post_meta($post_id, 'fibonaaci_sequence', $value );
	} else if ($sequenceType == 'reversed'){
		update_post_meta($post_id, 'fibonacci_reversed', $value );
	}
    $current_post_meta = get_post_meta($post_id);
    return $current_post_meta;
}
