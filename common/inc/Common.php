<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       common.php
        function:   common functions and settings
*/

namespace Common;

class Common {
    
    // members
    private $settings = array(
                'textDomain'    =>  'wp-enhanced-jwt-rest-api',
                'appName'       =>  'enhanced-jwt',  
                'appTitle'      =>  'Enhanced JWT'  
            );
    // members
    
    // is admin
    public function isAdmin( ){
        
        // is wp admin
        if( defined( 'WP_ADMIN' ) || defined( 'WP_NETWORK_ADMIN' ) ){
            // is admin
            return true;
        }
        // is wp admin
        
        // ! admin
        return false;
    }	
    // is admin
    
    // get setting
    public function getSetting( $key ){
        
        return $this->settings[$key];
    }	
    // get setting
    
    // get admin link
    public function getAdminLink( ){
        
	if( !is_multisite() ){
            return admin_url( 'admin.php' );
	}
	else{
            return network_admin_url( 'admin.php' );
	}
    }	
    // get admin link
    
    // get current tab
    public function getCurrentTab( $defaultTab ){
        // get tab
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;
        
        // set default tab
        if( !$tab ){
            $tab = $defaultTab;
        }
        // set default tab
        
        // done
        return $tab;
    }
    // get current tab
    
}
