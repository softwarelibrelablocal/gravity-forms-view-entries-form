<?php
/*
Plugin Name: Frontend_GF
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: Frontend de Gravity Form
Version:     20161117
Author:      rivasciudad.es
Author URI:  https://www.rivasciudad.es/
License:     GPL2
License URI: https://www.rivasciudad.es/wordpress
Text Domain: wporg
Domain Path: /languages
*/

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL & ~E_NOTICE); 

add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function my_enqueued_assets() {
	//js para listados gravity forms
	//wp_enqueue_script( 'gflistado-bootstrap', plugin_dir_url( __FILE__ ) . '/js/bootstrap.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-datatables', plugin_dir_url( __FILE__ ) . '/js/jquery.dataTables.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-datatablesbutons', plugin_dir_url( __FILE__ ) . '/js/dataTables.buttons.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-jszip', plugin_dir_url( __FILE__ ) . '/js/jszip.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-pdfmake', plugin_dir_url( __FILE__ ) . '/js/pdfmake.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-vfs_fonts', plugin_dir_url( __FILE__ ) . '/js/vfs_fonts.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-buttonshtml5', plugin_dir_url( __FILE__ ) . '/js/buttons.html5.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'gflistado-swiper', plugin_dir_url( __FILE__ ) . '/js/swiper.min.js', array( 'jquery' ), '1.0', true );
	
	//estilos para listados gravity forms
	//wp_enqueue_style( 'gflistado-bootstrap', plugin_dir_url( __FILE__ ) . '/css/bootstrap.css', array(), '1.0' );
	wp_enqueue_style( 'gflistado-datatables', plugin_dir_url( __FILE__ ) . '/css/jquery.dataTables.min.css', array(), '1.0' );
	wp_enqueue_style( 'gflistado-datatablesbuttons', plugin_dir_url( __FILE__ ) . '/css/buttons.dataTables.min.css', array(), '1.0' );
	wp_enqueue_style( 'gflistado-frontend', plugin_dir_url( __FILE__ ) . '/css/style_frontend.css', array(), '1.0' );
	wp_enqueue_style( 'gflistado-swiper', plugin_dir_url( __FILE__ ) . '/css/swiper.min.css', array(), '1.0' );
}


add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_scripts' );

function wpdocs_enqueue_custom_admin_scripts() {
		wp_enqueue_script( 'crypt', plugin_dir_url( __FILE__ ) . 'js/crypt.js', array( 'jquery' ), '1.0', true );
        wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . 'css/style_admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
		
}


//incluimos shortcode que muestra las entradas
include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/shortcodes/gf_entries_form.php");
add_shortcode ('gf_entries_form', 'gf_entries_form');


//definimos ajax
include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/gfapi/gfapi.php");
add_action( 'wp_ajax_gf_entries_form', 'gfap_entries_form_ajax' );
add_action( 'wp_ajax_gf_names_fields', 'gfapi_names_fields' );

//ajax para editar entrada
include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/gravity/edit_entrie.php");
add_action( 'wp_ajax_gf_edit_entrie', 'gf_edit_entrie_ajax' );

include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/gfapi/gf_edit_shortcode.php");


//Creamos la pagina del admin

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page( 'Gravity Frontend', 'Gravity Frontend', 'administrator', 'frontend-gf/admin_page.php', 'admin_page', 'dashicons-tickets', 6  );
}

include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/admin/admin_page.php");

//funcion para encriptar
include ( get_home_path_() ."wp-content/plugins/frontend-gf/includes/crypt/crypt.php");

// Esta funcion esta tomada de /wp-admin/includes/file.php
function get_home_path_() {
	$home    = set_url_scheme( get_option( 'home' ), 'http' );
	$siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
	if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
		 $wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
			$pos = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
			$home_path = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
			$home_path = trailingslashit( $home_path );
	} else {
			$home_path = ABSPATH;
	}
	return str_replace( '\\', '/', $home_path );
}