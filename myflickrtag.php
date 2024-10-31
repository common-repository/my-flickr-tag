<?php
/*
Plugin Name: My Flickr Tag
Description: Display images from a Flickr tag
Version: 1.0
Author: Brad Branson
Author URI: https://offseo.com
License: GPLv2
*/
function flickr_scripts() {

wp_register_style( 'prefix-style', plugins_url('flickr.css', __FILE__) );
wp_enqueue_style( 'prefix-style' );
}
add_action('wp_enqueue_scripts','flickr_scripts');


add_shortcode( 'myflickrtag', 'myflickr_tag_shortcode' );

function myflickr_tag_shortcode( $atts ) {
	extract( shortcode_atts( array(
	'tag' => ''), $atts ) );

	$string = wp_remote_fopen("https://www.flickr.com/services/feeds/photos_public.gne?tags=".$tag);
	$xml = simplexml_load_string($string);
	$json  = json_encode($xml);
	$xmlArr = json_decode($json, true);
	$output="<div class='flicontainer'><div class='fligrid'>";
	foreach($xmlArr["entry"] as $listitem) {
		foreach($listitem['link'] as $listitem2) {
			if ($listitem2['@attributes']['type']=='image/jpeg'){
				//echo $listitem2['@attributes']['href'];
				$block="<div class='flimg'><img src='".$listitem2['@attributes']['href']." alt='' class='responsive-image'></div>";
				$output.=$block;
				//echo "\n";
			}
		}
	}
	$output.="</div</div>";
	return $output;
}

