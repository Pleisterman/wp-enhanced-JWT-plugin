<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
 
        file:       dashboard.php
        function:   handles: 
                        admin enqueue scripts 
                        dashboard create menu 
                        register wordpress options
                        open selected menu page
                        activate, 
                        deactivate
*/

namespace Admin;

use Common\Common;
use Admin\Menu;
use Admin\Settings;
use Admin\HtmlGenerator;
use Admin\Sanitizer;
use Admin\Validator;

class Dashboard {
    // members
    private $common = null;
    private $htmlGenerator = null;
    private $sanitizer = null;
    private $validator = null;
    private $menu = null;
    private $roles = null;
    private $settings = null;
    private $adminPath = '';
    private $cssFiles = array(
        'styles'
    );
    private $cssDir = 'css';
    private $jsFiles = array(
    );
    private $jsDir = 'js';
    private $topMenuOptions = array(
        'page-title'    =>  'Enhanced JWT Settings',
        'menu-title'    =>  'Enhanced JWT',
        'capabilities'  =>  'manage_options',
        'slug'          =>  'wp-enhanced-jwt-rest-api-settings',
        'function'      =>  'showSettings',
        'icon'          =>  'dashicons-hammer',
        'position'      =>  '100'
    );
    private $subMenusOptions = array(
        'settings'      => array(
            'parent-slug'   =>  'wp-enhanced-jwt-rest-api-settings',
            'page-title'    =>  'Enhanced JWT Settings',
            'menu-title'    =>  'Settings',
            'capabilities'  =>  'manage_options',
            'slug'          =>  'wp-enhanced-jwt-rest-api-settings',
            'function'      =>  'showSettings'
            ),
        'roles'         =>  array(
            'parent-slug'   =>  'wp-enhanced-jwt-rest-api-settings',
            'page-title'    =>  'Enhanced JWT Roles',
            'menu-title'    =>  'Roles',
            'capabilities'  =>  'manage_options',
            'slug'          =>  'wp-enhanced-jwt-rest-api-roles',
            'function'      =>  'showRoles'
        )
    );
    // members
    
    // construct
    public function __construct( $adminPath, Common $common, HtmlGenerator $htmlGenerator, Sanitizer $sanitizer, Validator $validator ){
        // set admin path
        $this->adminPath = $adminPath;
        // set common
        $this->common = $common;
        // set htmlGenerator
        $this->htmlGenerator = $htmlGenerator;
        // set sanitizer
        $this->sanitizer = $sanitizer;
        // set validator
        $this->validator = $validator;
        
        // translate menus
        $this->translateMenus();
        
    }
    // construct
    
    // translate menus
    private function translateMenus( ) {
        
        // get textDomail
        $textDomain = $this->common->getSetting( 'textDomain' );
        
        // translate top menu page title
        $this->topMenuOptions['page-title'] = __( $this->topMenuOptions['page-title'], $textDomain );
        // translate top menu menu title
        $this->topMenuOptions['menu-title'] = __( $this->topMenuOptions['menu-title'], $textDomain );
        
        // loop over sub menus
        foreach ( $this->subMenusOptions as $subMenuId => $submenu ){
            
            // translate sub menu page title
            $this->subMenusOptions[$subMenuId]['page-title'] = __( $submenu['page-title'], $textDomain );
            // translate top menu menu title
            $this->subMenusOptions[$subMenuId]['menu-title'] = __( $submenu['menu-title'], $textDomain );
            
        }
        // loop over sub menus
        
    }
    // translate menus
    
    // enqueue scripts 
    public function enqueueScripts() {
        // add css
        $this->addCssFiles( );
        // add js
        $this->addJsFiles( );
    }	
    // enqueue scripts 

    // add css files 
    private function addCssFiles() {
        
        // loop over css files
        foreach ( $this->cssFiles as $key ){
            // create handle
            $handle = $this->common->getSetting( 'appName' ) . '_' . $key;
            // register script
            wp_register_style( $handle, $this->adminPath . $this->cssDir . '/' . $key . '.css' );
            // enqueue script
            wp_enqueue_style( $handle );
        }        
        // done loop over css files        
    }	
    // add css files
    
    // add js files 
    private function addJsFiles() {
        
    }	
    // add js files
    
    // register settings
    public function registerSettings( ){

        // ! settings exists
        if( !isset( $this->settings )){
            // create settings class
            $this->settings = new Settings( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator ); 
        }
        // ! settings exists

        // register settings settings
        $this->settings->registerSettings( $this );
        
        // ! roles exists
        if( !isset( $this->roles )){
            // create roles class
            $this->roles = new Roles( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator ); 
        }
        // ! roles exist
        
        // register roles settings
        $this->roles->registerSettings( $this );
    }
    // register settings
    
    // register Wordpress settings
    public function registerWordpressSettings( $settings, $sanitizeCallback  ){
        
        // get app name
        $appName = $this->common->getSetting( 'appName' );
        // get group name
        $groupId = $appName . '-' . $settings['id'] . '-group';
        // get group name
        $groupName = $appName . '-' . $settings['id'];
        
        // add settings group
	register_setting( $groupId, $groupName, $sanitizeCallback );
        
        // loop over sections
        foreach ( $settings['sections'] as $sectionId => $section ){
            
            // register section
            add_settings_section( $sectionId, 
                                  $section['title'], 
                                  array( $this->htmlGenerator, 'createSettingsSection' ),
                                  $groupName );
            // register fields
            $this->registerWordpressSettingsFields( $groupName, $sectionId, $section['fields'] );
        }
        // loop over sections
        
    }
    // register Wordpress settings
    
    // register Wordpress settings fields
    private function registerWordpressSettingsFields( $groupName, $sectionId, $fields  ){

        // loop over fields
        foreach ( $fields as $fieldId => $field ){
            
            // add fieldId to args
            $field['args']['fieldId'] = $fieldId;
            $field['args']['groupId'] = $groupName;

            // has default value
            if( isset( $field['defaultValue'] ) ){
                // add default value
                $field['args']['defaultValue'] = $field['defaultValue'];
            }
            // has default value
            
            // add field
            add_settings_field( $fieldId,
                                $field['args']['label'],
                                array( $this->htmlGenerator, 'createSettingsField' ),
                                $groupName,
                                $sectionId,
                                $field['args'] );
        }
        // loop over fields
        
    }
    // register Wordpress settings fields
    
    // create menu
    public function createMenu() {

        // ! menu exists
        if( !isset( $this->menu )){
            // create menu class
            $this->menu = new Menu( $this->common ); 
        }
        // ! menu exists
        
        // create menu
        $this->menu->create( $this, $this->topMenuOptions, $this->subMenusOptions );
    }	
    // create menu

    // show settings
    public function showSettings(){
        
        // ! settings exists
        if( !isset( $this->settings )){
            // create settings class
            $this->settings = new Settings( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator ); 
        }
        // ! settings exists
        
        // show settings
        $this->settings->show( $this->subMenusOptions['settings']['slug'] );
    }
    // show settings

    // show roles
    public function showRoles( ){
        
        // ! roles exists
        if( !isset( $this->roles ) ){
            // create roles class
            $this->roles = new Roles( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator ); 
        }
        // ! roles exists
        
        // show roles
        $this->roles->show( );
            
    }
    // show roles
    
    // activate
    public function activate() {
    }
    // activate

    // deActivate
    public static function deActivate() {
    }
    // deActivate
}
