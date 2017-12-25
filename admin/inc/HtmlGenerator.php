<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       HtmlGenerator.php
        function:   Generates html for admin
*/

namespace Admin;

use Common\Common;

class HtmlGenerator {
    
    // members
    private $common = null;
    // members
        
    // construct
    public function __construct( Common $common ){
        // set common
        $this->common = $common;
    }
    // construct
    
    // create title
    public function createTitle( ){
        // create title html
 	echo '<h2>' . __( $this->common->getSetting( 'appTitle' ), $this->common->getSetting( 'textDomain' ) ) . '</h2>';
    }	
    // create title
    
    // create sub title
    public function createSubTitle( $iconClass, $title ) {
        // open title 
        echo '<span style="margin-top: 2em; margin-bottom: 2em;"';
        echo ' class="' . $iconClass . '">';
            // create title text
            echo '<span style="margin-left: 0.6em;">' . __( $title, $this->common->getSetting( 'textDomain' ) ) . '</span>';
        // close title 
        echo '</span>';
    }	
    // create sub title

    // create tab content
    public function createTabs( $slug, $tabs, $currentTab ){
        // open html wrapper
        echo '<div class="wrap">';
        
            // create title
            $this->createTitle();

            // create html wrapper
            echo '<h2 class="nav-tab-wrapper enhanced-jwt-tabs">';
            
            // loop over tabs
            foreach( $tabs as $key => $options ) {
                // create tab
                $this->createTab( $slug, $currentTab, $key, $options );
            }
            // loop over tabs
            
            // close html wrapper
            echo '</h2>';
            
        // close html wrapper
        echo '</div>';
    }	
    // create tab content
    
    // create tab
    private function createTab( $slug, $currentTab, $key, $options ){
        // open link
        echo '<a href="' . $this->common->getAdminLink() . '?page=' . $slug . '&tab=' . $key; 

        // add nav=tab class
        echo '" class="nav-tab '; 

        // is current tab
        if( $currentTab == $key ) {
            echo 'nav-tab-active';
        }
        // is current tab

        // done open link
        echo '">';

            // add icon    
            echo '<span style="margin-right:0.4em;" class="' . $options['icon'] . '"></span>';

            // add tab title
            echo __( $options['title'],  $this->common->getSetting( 'textDomain' ) );

        // close link
        echo '</a>';    
    }	
    // create tab
    
    // create settings section
    public function createSettingsSection( ){
    }	
    // create settings section
    
    // create settings field
    public function createSettingsField( $args ){
        
        $name = $args['groupId'].'['. $args['fieldId'] . ']';
        $value = '';
       
        // get options of settings group
        $options = get_option( $args['groupId']  );
       
        // is array
        if( is_array( $options ) ){
            // field is defined
            if( isset( $options[$args['fieldId']] ) ){
                // get option value
                $value = esc_attr( $options[$args['fieldId']] );
            }
            // field is defined
        }
        // is array
        
        // type detection
        switch ( $args['type'] ) {
            // email
            case 'email'    :  {
                // create input
                $this->createTextInput( $name, $args, $value );
                
                // done
                break;
            }
            case 'text'     :  {
                // create input
                $this->createTextInput( $name, $args, $value );
                
                // done
                break;
            }
            // checkbox
            case 'checkbox'  :  {
                // create input
                $this->createCheckboxInput( $name, $args['fieldId'], $value );
                
                // done
                break;
            }
            // default
            default: {
                // log error
                error_log( 'createSettingsField, unknown field type: ' . $args['type'] );
            }
        }
        
    }	
    // create settings field
    
    // create text input
    private function createTextInput( $name, $args, $value ) {

        // create input
        echo '<input type="text" ';
            echo ' name="' . $name . '" ';
            echo ' id="' . $args['fieldId'] . '" ';
            echo 'value="' . $value . '" ';
            
            if( isset( $args['size'] ) ){
                echo 'size="' . $args['size'] . '"';
            }
            
        echo '>'; 
        // create input
        
    }
    // create text input
    
    // create checkbox input
    private function createCheckboxInput( $name, $fieldId, $value ) {

        // create input
        echo '<input type="checkbox" ';
            echo ' name="' . $name . '" ';
            echo ' id="' . $fieldId . '" ';
            // value exists
            if( $value ){
                // check checkbox
                echo ' checked ';
            }
            // value exists
        echo '>'; 
        // create input
        
    }
    // create checkbox input

    // create settings form
    public function createSettingsForm( $settingsId ){
        
        // ? determine method
        $method = 'post';
        // ? determine action
        $action = 'options.php';
        // get Appname
        $appName = $this->common->getSetting( 'appName' );
        // create group name
        $groupName = $appName . '-' . $settingsId;
        // create group id
        $groupId = $appName . '-' . $settingsId . '-group';
                
        // open form
        echo '<form method="' . $method . '" action="' . $action . '">';
        
            // set wordpress fields
            settings_fields( $groupId );
            // se wordpress sections
            do_settings_sections( $groupName ); 
            // add submit
            submit_button();
                
        // close form
        echo '</form>';
        
    }
}
