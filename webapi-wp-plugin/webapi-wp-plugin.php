<?php
/*
Plugin Name: IMEDEA WEB API 
Plugin URI: http://imedea.uib-csic.es
Description: Get data from IMEDEA WEB API
Version: 0.2
Author: J.J.E.P.
Author URI: http://imedea.uib-csic.es

Copyright 2021  IMEDEA (UIB-CSIC)
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
*/

include_once(plugin_dir_path( __FILE__ ) . 'helpers.php');
include_once(plugin_dir_path( __FILE__ ) . 'staff.php');
include_once(plugin_dir_path( __FILE__ ) . 'publications.php');
include_once(plugin_dir_path( __FILE__ ) . 'settings.php');

add_action( 'imedea_staff_list', 'imedea_staff_list' );
add_shortcode('imedea_staff_list', 'imedea_staff_list');
add_action( 'imedea_staff_detail', 'imedea_staff_detail' );
add_shortcode('imedea_staff_detail', 'imedea_staff_detail');

add_action( 'imedea_publication_list', 'imedea_publication_list' );
add_shortcode('imedea_publication_list', 'imedea_publication_list');


add_action( 'init', 'register_shortcodes');


/*
 * Settings 
 */
 
add_action( 'admin_menu', 'iapi_add_settings_page' );
add_action( 'admin_init', 'iapi_register_settings' );

/*
 * CSS 
 */

add_action( 'wp_enqueue_scripts', 'imedea_staff_styles' );
function imedea_staff_styles(){
	wp_enqueue_style('imedea_staff_styles', plugin_dir_url( __FILE__ ) . 'css/imedea-staff.css' );
}

add_action( 'wp_enqueue_scripts', 'imedea_publications_styles' );
function imedea_publications_styles(){
	wp_enqueue_style('imedea_publications_styles', plugin_dir_url( __FILE__ ) . 'css/imedea-publications.css' );
}

/*
 * Javascript
 */
 
add_action('wp_enqueue_scripts', 'imedea_publications_js');
function imedea_publications_js(){    
	wp_enqueue_script('imedea_publications_js', plugin_dir_url( __FILE__ ) . 'js/imedea-publications.js' );
}

?>

