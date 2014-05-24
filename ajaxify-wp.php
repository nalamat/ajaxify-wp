<?php
/*
  @package Ajaxify WordPress
  
  Plugin Name: Ajaxify WordPress
  Plugin URI : http://github.com/nalamat/ajaxify-wp
  Description: Hybrid Ajaxify plugin for WordPress, load pages with Ajax while
               keeping normal functionality intact
  Version    : 1.0.0
  Author     : Nima Alamatsaz
  Author URI : http://nalamat.ir
  License    : GPLv3, Copyright 2014 Nima Alamatsaz
*/

// 'before_wp_head' is not a WordPress action, it must be
// added before wp_head() call in theme's header.php
// TODO: is this necessary? investigate and fix
add_action( 'before_wp_head', 'ajaxify_wp_head' );
add_action( 'wp_footer', 'ajaxify_wp_footer' );
add_filter( 'the_content', 'ajaxify_wp_content' );
add_filter( 'template_include', 'ajaxify_wp_template' );

function ajaxify_wp_head()
{
	wp_enqueue_style( 'ajaxify', plugins_url( 'ajaxify.css', __FILE__) );
	wp_enqueue_script( 'jquery', plugins_url( 'jquery.js', __FILE__ ) );
	wp_enqueue_script( 'ajaxify', plugins_url( 'ajaxify.js', __FILE__ ) );
}

function ajaxify_wp_footer()
{
	echo '<div id="ajaxify_wp_load"><img src="'.plugins_url( 'load.gif', __FILE__).'"></div>'."\n";
}

function ajaxify_wp_content( $content ) {
	$c = $content;
	if ( substr($c, 0, 3) == "<p>" )$c = substr($c, 3);
	if ( substr($c, strlen($c)-5, 4) == "</p>" ) $c = substr($c, 0, strlen($c)-5);
	if ( substr($c, 0, 12 ) == "__include__ " ) include ABSPATH.substr($c, 12, strlen($c)-12);
	else echo $content;
	
	return null;
}

function ajaxify_wp_template( $template )
{
	if ( !$template || is_null($_GET["ajax"]) ) return $template;
	global $post;
	
	ob_start();
	include $template;
	$output = ob_get_clean();
	$start = '<div id="content">';
	$start2 = '<div id="content" class="full">';
	$end = '</div><!-- /content -->';
	if ( ($starti=strpos($output, $start)) <= 0 )
		$starti = strpos($output, $start2)+strlen($start2);
	else
		$starti += strlen($start);
	$endi = strpos($output, $end);
	$output = substr($output, $starti, $endi-$starti);
	
	echo $output;
	
	return null;
}

function ajaxify_wp_include($filename) {
	if ( is_file($filename) ) {
		ob_start();
		include $filename;
		return ob_get_clean();
	}
	return false;
}

?>