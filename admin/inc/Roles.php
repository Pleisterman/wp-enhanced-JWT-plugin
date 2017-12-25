<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
   
        file:       roles.php
        function:   show roles settings in admin
  
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

class Roles {        
    // members
    private $common = null;
    private $htmlGenerator = null;
    private $sanitizer = null;
    private $validator = null;
    private $iconClass = 'dashicons dashicons-admin-users';
    private $settings = array(
        'id'            =>  'roles',
        'slug'          =>  'wp-enhanced-jwt-rest-api-roles',
        'sections'      =>  array(
            'section01'    => array(
                'title'     => 'Life can be fun.',
                'fields'    => array(
                    'field01'  => array(
                        'args'  =>  array(
                            'type'  =>  'checkbox',
                            'label' =>  'Do you want to have fun?'
                        )                    )
                )
            ),
            'section02'  => array(
                'title'     => 'What do you like? Tell me in one sentence:',
                'fields'    => array(
                    'field02'  => array(
                        'args'  =>  array(
                            'type'  =>  'text',
                            'size'  =>  '80',
                            'label' =>  'Tell me:'
                        )
                    ),
                    'field03'  => array(
                        'args'  =>  array(
                            'type'  =>  'email',
                            'label' =>  'Leave your work email:'
                        ),
                        'validation'  => array(
                            'type'   => 'email'
                        )
                    ),
                    'field04'  => array(
                        'args'  =>  array(
                            'type'  =>  'email',
                            'label' =>  'Or your personal email:'
                        ),
                        'validation'  => array(
                            'type'   => 'email'
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
        // register roles settings
        $dashboard->registerWordpressSettings( $this->settings, array( $this, 'sanitizeAndValidate' ) );
    }
    // register settings
    
    // show 
    public function show() {
        // open html wrapper
        echo '<div class="wrap">';
            // create title
            $this->htmlGenerator->createTitle( );
        
            // create title
            $this->htmlGenerator->createSubTitle( $this->iconClass, 'Roles' );
            
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
