<?php
/**
Plugin Name: openCPU API
Description: Add tables and figures from the openCPU API for R.
Version: 1.0
Author: Jacob Malcom
License: GPLv2 or later
Text Domain: opencpu-api
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function get_data( $url=null ) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
/* add_shortcode( "opencpu_data", "get_data" ); */

function get_function_list( $atts = [] ) {
    $a = shortcode_atts( array(
        'pkg' => 'MASS'
    ), $atts );
    $url = "http://localhost:8004/ocpu/library/" . $a['pkg'] . "/R/";
    $data = get_data( $url );
    $data = preg_split('/\s+/', $data);
    $olist = "<ul>";
    foreach( $data as $datum ) {
        if ( !($datum == "")) {        
            $olist .= "<li>{$datum}</li>";
        }
    }
    $olist .= "</ul>";
    return $olist;
}
add_shortcode( "package_functions", "get_function_list" );

function get_plot( $atts = [] ) {
    $a = shortcode_atts( array(
        'pkg' => 'open.cpu.test'
    ), $atts );
    $url = "http://localhost:8004/ocpu/library/open.cpu.test/R/a_plot/png";
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    $plt = "<img src='data:image/png;base64," . base64_encode($data) . "' />";
    return $plt;
}
add_shortcode( "get_a_plot", "get_plot" );

