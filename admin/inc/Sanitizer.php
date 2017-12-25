<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       Sanitizer.php
        function:   Sanitize user input for admin
        info:       https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-7-validation-sanitisation-and-input--wp-25289
   
        status:
            26-12-2017: contains:
                            wordpress option sanitizing for wp-options
                            sanitize email and text
                        to do: 
                            sanitize: phone number, number, date, url,...
        
*/

namespace Admin;

use Common\Common;

class Sanitizer {
    
    // members
    private $common = null;
    // members
        
    // construct
    public function __construct( Common $common ){
        // set common
        $this->common = $common;
    }
    // construct
    
    // sanitize settings field
    public function sanitizeSettingsField( $settingsId, $field, $value ) {
        
        // has wordpress option sanitize type
        if( isset( $field['wp-sanitize-type'] ) ){
            // use wordpress option sanitize
            $value = sanitize_option( $field['wp-sanitize-type'], $value );
        }
        // has wordpress option sanitize type
                
        // type detection
        switch ( $field['args']['type'] ) {
            
            // text
            case 'text':    {
                // use wordpress sanitize 
                $value = sanitize_text_field( $value );
                
                // done
                break;
            }
            // email
            case 'email':    {
                
                // sanitize email
                $value = $this->sanitizeSettingsFieldEmail( $value );
                
                // done
                break;
            }
            // checkbox
            case 'checkbox':    {
            
                // done
                break;
            }
            // default
            default: {
                // log error
                error_log( 'sanitizeSettingsField, unknown field type: ' . $field['type'] );
            }
        }
        // type detection
        
        // return 
        return $value;
        
    }
    // sanitize settings field
  
    // sanitize settings field email
    private function sanitizeSettingsFieldEmail( $value ) {
        // sanitize email 
        $sanitizedValue = sanitize_email( $value );
        // sanitized value not empty
        if( !empty( $sanitizedValue ) ){
            // set value
            $value = $sanitizedValue;
        }
        else {
            // sanitize as text
            $value = sanitize_text_field( $value );
        }
        // sanitized value not empty
        
        // return
        return $value;
    }
    // sanitize settings field email
}
