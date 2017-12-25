<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
   
        file:       SettingsMainTab.php
        function:   handles main tab for settings page
  
        This page has a member settings with the structure:
 
        settings : array( key => value )
            required / id
            required / slug
            required, can be empty / sections
 
        sections : array( key => value )
            required / title
            required, can be empty / fields
             
        fields : array( key => value )
            required / args
            optional / validation
            optional / defaultValue    
            optional / wp-sanitize-type
 
        args : array( key => value )
            required / type
            required / label
            optional / size
            
        validation : array( key => value )
            required / type
            optional / errorValue
 
*/

namespace Admin;

use Common\Common;
use Admin\HtmlGenerator;
use Admin\Sanitizer;
use Admin\Validator;

class SettingsMainTab {        
    // members
    private $common = null;
    private $htmlGenerator = null;
    private $sanitizer = null;
    private $validator = null;
    private $iconClass = 'dashicons dashicons-dashboard';
    private $settings = array(
        'id'            =>  'settings-main',
        'slug'          =>  'wp-enhanced-jwt-rest-api-settings-main',
        'sections'      =>  array(
            'autosave'  => array(
                'title' => 'Autosave',
                'fields'  => array(
                    'autosave' => array(
                        'title' =>  'Autosave',
                        'args'  =>  array(
                            'type'  =>  'checkbox',
                            'label' =>  'Autosave JWT in secured cookie.'
                        )
                    )
                )
            ),
            'algorithm'  => array(
                'title' => 'Algorithm.',
                'fields'  => array(
                    'zomaar' => array(
                        'args'  =>  array(
                            'type'  =>  'text',
                            'label' =>  'Have some fun type something random.'
                        )
                    )
                )
            )
        )        
    );
    private $fields = array();
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
        
        // fill fields
        $this->fillFields();
        // translate settings
        $this->translateSettings();
        
    }
    // construct
    
    // fill fields: collects all fields from the settings for easy sanitation 
    private function fillFields( ) {
        
        // loop over sections
        foreach ( $this->settings['sections'] as $sectionId => $section ){
            // loop over fields
            foreach ( $section['fields'] as $fieldId => $field ){
                // add field to fields
                $this->fields[$fieldId] = $field;
                // add id
                $this->fields[$fieldId]['id'] = $fieldId;
            }
            // loop over fields
        }
        // loop over sections
        
    }
    // fill fields

    // translate settings 
    private function translateSettings( ) {
        
        // get textDomail
        $textDomain = $this->common->getSetting( 'textDomain' );
        
        // loop over sections
        foreach ( $this->settings['sections'] as $sectionId => $section ){
            
            // translate title for section
            $this->settings['sections'][$sectionId]['title'] = __( $section['title'], $textDomain );
            
            // loop over fields
            foreach ( $section['fields'] as $fieldId => $field ){
                
                // translate label for field
                $this->settings['sections'][$sectionId]['fields'][$fieldId]['args']['label'] = __( $field['args']['label'], $textDomain );
            }
            // loop over fields
        }
        // loop over sections
        
    }
    // translate settings

    // register settings
    public function registerSettings( $dashboard ){
                
        // register wordpress settings
        $dashboard->registerWordpressSettings( $this->settings, array( $this, 'sanitizeAndValidate' ) );
    }
    // register settings
    
    // show 
    public function show() {
        // open html wrapper
        echo '<div class="wrap">';
            
            // create sub title
            $this->htmlGenerator->createSubTitle( $this->iconClass, 'Settings' );
            
            // open main div
            echo '<div class="' . $this->common->getSetting( 'appName' ). '-main">';
        
                // show errors
                settings_errors();

                // add spacing
                echo '<br>';

                $this->htmlGenerator->createSettingsForm( $this->settings['id'] );
            
            // close main div
            echo '</div>';
            
        // close html wrapper
        echo '</div>';
    }	
    // show 

    // vaidate and sanitize 
    public function sanitizeAndValidate( $input ) {
        
        // Create our array for storing the validated options
        $output = array();

        // loop over input array
        foreach( $input as $key => $value ) {

            // sanitisation
            $output[$key] = $this->sanitizer->sanitizeSettingsField( $this->settings['id'], $this->fields[$key], $value );
            // validation
            $output[$key] = $this->validator->validateSettingsField( $this->settings['id'], $this->fields[$key], $output[$key] );
            
        } 
        // loop over input array
        
        // return
        return $output;
    }
    // validate and sanitize
    
}
