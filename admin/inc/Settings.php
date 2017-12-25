<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       settings.php
        function:   show settings tabs in admin
*/

namespace Admin;

use Common\Common;
use Admin\HtmlGenerator;
use Admin\SettingsMainTab;
use Admin\Sanitizer;
use Admin\Validator;

class Settings {
    // members
    private $common = null;
    private $htmlGenerator = null;
    private $sanitizer = null;
    private $validator = null;
    private $tabs = array(
        'settings'      =>  array(
            'title'     =>  'Settings',
            'icon'      =>  'dashicons dashicons-dashboard',
            'class'     =>  __NAMESPACE__ . '\SettingsMainTab'
        ),
        'help'          =>  array(
            'title'     =>  'Help',
            'icon'      =>  'dashicons dashicons-editor-help',
            'class'     =>  __NAMESPACE__ . '\SettingsHelpTab'
        )
    );
    private $defaultTab = 'settings';
    // members
    
    // construct
    public function __construct( Common $common, HtmlGenerator $htmlGenerator, Sanitizer $sanitizer, Validator $validator ) {
        // set common
        $this->common = $common;
        // create html generator
        $this->htmlGenerator = $htmlGenerator;
        // set sanitizer
        $this->sanitizer = $sanitizer;
        // set validator
        $this->validator = $validator;
    }
    // construct
    
    // register settings
    public function registerSettings( $dashboard ){
        // create main tab class
        $settingsMainTab = new SettingsMainTab( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator ); 
        // register main tab settings
        $settingsMainTab->registerSettings( $dashboard );
    }
    // register settings
    
    // show 
    public function show( $slug ) {
        // get currentTab
        $currentTab = $this->common->getCurrentTab( $this->defaultTab );
        // create tab content
        $this->htmlGenerator->createTabs( $slug, $this->tabs, $currentTab );
        // create tab content
        $this->createTabContent( $currentTab );            
    }	
    // show
    
    // create tab content
    private function createTabContent( $currentTab ) {
        // create tab class
        $tab = new $this->tabs[$currentTab]['class']( $this->common, $this->htmlGenerator, $this->sanitizer, $this->validator );
        // show tab
        $tab->show();
    }
    // create tab content
        
}
