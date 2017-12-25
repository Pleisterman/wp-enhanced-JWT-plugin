<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
 
        file:       wp-enhanced-jwt-rest-api.php
        function:   main plugin file
*/

/*
Plugin Name: wp-enhanced-jwt-rest-api
Plugin URI: https://pleisterman.nl/
Description: adds a jwt authorisation to the wordpress rest-api authorisation
Version: 1.0.0
Author: Rob Wolters
Author URI: https://pleisterman.nl
License: GPLv2 or later
Text Domain: wp-enhanced-jwt-rest-api
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2017-2020 Pleisterman.
*/

/*
    Project status:
        26-12-2017: Basic structure for a plugin is working.
                    Contains:   translations: Dutch
                                2 menu pages: settings and roles,
                                the settings page contains 2 tabs main and help.
                                text, email and checkbox can be displayed, edited and saved.
                                text and email kan be sanitized and validated
                                Errors are displayed with section and option information
                    To do:      Testing.
                                Multisite options.
                                More input types.
                                Ajax refresh for data.
                                JWT functions.
*/

// Make sure we don't expose any info if called directly
if ( ! defined( 'WPINC' ) ) {
	exit;
}
// Make sure we don't expose any info if called directly

// autoloader exists
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    // add autoloader
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}
// autoloader exists

use Common\Common;

// create common
$common = new Common();

// load translations
load_plugin_textdomain( $common->getSetting( 'textDomain' ), false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

// is admin 
if ( $common->isAdmin() ) {
    
    // get admin path
    $adminPath = plugin_dir_url( __FILE__ ) . '/admin/';
       
    // create htmlGenerator
    $htmlGenerator = new Admin\HtmlGenerator( $common );
    // create sanitizer
    $sanitizer = new Admin\Sanitizer( $common );
    // create validator
    $validator = new Admin\Validator( $common );
    
    // create dashboard
    $dashboard = new Admin\Dashboard( $adminPath, $common, $htmlGenerator, $sanitizer, $validator );
    
    // enqueue scripts
    add_action( 'admin_enqueue_scripts', array( $dashboard, 'enqueueScripts' ) );

    // create dashboard menu
    add_action( 'admin_menu', array( $dashboard, 'createMenu' ) );
    
    // register settings
    add_action( 'admin_init', array( $dashboard, 'registerSettings' ) );

}
else {
	
}
// is admin 
