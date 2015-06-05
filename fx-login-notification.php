<?php
/**
 * Plugin Name: f(x) Login Notification
 * Plugin URI: http://fx-plugins.com/plugins/fx-login-notification/
 * Description: Simple plugin for user login notification via email.
 * Version: 0.1.1
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @author David Chandra Purnama <david@shellcreeper.com>
 * @copyright Copyright (c) 2015, David Chandra Purnama
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/* Constants
------------------------------------------ */

/* Set the version constant. */
define( 'FX_LOGIN_NF_VERSION', '0.1.0' );

/* Set the constant path to the plugin path. */
define( 'FX_LOGIN_NF_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'FX_LOGIN_NF_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );


/* Plugins Loaded
------------------------------------------ */

/* Load plugins file */
add_action( 'plugins_loaded', 'fx_login_nf_plugins_loaded' );

/**
 * Load plugins file
 * @since 0.1.0
 */
function fx_login_nf_plugins_loaded(){

	/* Language */
	load_plugin_textdomain( 'fx-login-notification', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/* Load Functions */
	require_once( FX_LOGIN_NF_PATH . 'includes/functions.php' );

	/* Load Settings */
	if( is_admin() ){
		require_once( FX_LOGIN_NF_PATH . 'includes/settings.php' );
		$fx_login_nf_settings = new fx_Login_Nf_Settings();
	}
}

/* AutoHosted Updater
------------------------------------------ */

/* hook updater to init */
add_action( 'init', 'fx_login_nf_updater_init' );

/**
 * Load and Activate Plugin Updater Class.
 * @since 0.1.0
 */
function fx_login_nf_updater_init() {

	/* Load Plugin Updater */
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/plugin-updater.php' );

	/* Updater Config */
	$config = array(
		'base'      => plugin_basename( __FILE__ ), //required
		'repo_uri'  => 'http://repo.shellcreeper.com/',  //required
		'repo_slug' => 'fx-login-notification',  //required
	);

	/* Load Updater Class */
	new fx_Login_Nf_Plugin_Updater( $config );
}

