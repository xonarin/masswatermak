<?
	/**
	 * Plugin Name: AddWatermark
	 * Description: Плагин для наложения водяных знаков
	 * Author: xonarin
	 * Version: 1.0
	 *
	 * @package CGB
	 */
	
	if ( !defined('ABSPATH') ) exit;
	
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	if( is_admin() ) 
	{
		wp_enqueue_script('jquery');
		
		add_action( 'admin_enqueue_scripts', function() {
			wp_enqueue_script( 'handle1', plugin_dir_url( __FILE__ ) . '/plg.js' );
		} );
	}
	