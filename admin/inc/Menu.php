<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       menu.php
        function:   creates the admin menu and links the menus to the functions
*/


namespace Admin;

use Common\Common;

class Menu {
    // members
    private $common = null;
    // members
    
    // construct
    public function __construct( Common $common ){
        // set common
        $this->common = $common;

    }
    // construct
    
    // create
    public function create( $dashboard, $topMenuOptions, $subMenusOptions ){
        // add top menu
        $this->addTopMenu( $dashboard, $topMenuOptions );
        // add sub menus
        $this->addSubMenus( $dashboard, $subMenusOptions );
    }
    // create
    
    // add top menu
    private function addTopMenu( $dashboard, $topMenuOptions ){
        // add top menu
        add_menu_page(  __( $topMenuOptions['page-title'], $this->common->getSetting( 'textDomain' ) ), 
                        __( $topMenuOptions['menu-title'], $this->common->getSetting( 'textDomain' ) ), 
                        $topMenuOptions['capabilities'], 
                        $topMenuOptions['slug'], 
                        array( $dashboard, $topMenuOptions['function'] ), 
                        $topMenuOptions['icon'], 
                        $topMenuOptions['position'] );	
    } 
    // add top menu
    
    // add sub menus
    private function addSubMenus( $dashboard, $subMenusOptions ){
        // loop over sub menu options
        foreach ( $subMenusOptions as $key => $options ){
            // add sub menu
            add_submenu_page(   $options['parent-slug'], 
                                __( $options['page-title'], $this->common->getSetting( 'textDomain' ) ), 
                                __( $options['menu-title'], $this->common->getSetting( 'textDomain' ) ), 
                                $options['capabilities'], 
                                $options['slug'], 
                                array( $dashboard, $options['function'] ) );
        }
        // done loop over sub menu options
    } 
    // add sub menus
    
}
