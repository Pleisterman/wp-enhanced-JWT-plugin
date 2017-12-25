<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
  
        file:       Validator.php
        function:   Validate user input for admin

        status:
            26-12-2017: contains:
                            validate email adress 
                        to do: 
                            validate: positive number, phone number, Name,..

*/

namespace Admin;

use Common\Common;

class Validator {
    
    // members
    private $common = null;
    // members
        
    // construct
    public function __construct( Common $common ){
        // set common
        $this->common = $common;
    }
    // construct
    
    // validate settings field
    public function validateSettingsField( $settingsId, $field, $value ) {
    
        // ! field has validation
        if( !isset( $field['validation'] ) ){
            // no validation
            return $value;
        }
        // ! field has validation
        
        // create hasError
        $hasError = false;
        
        // validation email
        if( !$hasError && $field['validation']['type'] == 'email' ){
            // create check value
            $hasError = $this->validateSettingsFieldEmail( $settingsId, $field, $value );
        }
        // validation email
        
        // has error and error value
        if( $hasError && isset( $field['validation']['errorValue'] ) ){
            // set error value
            $value = $field['validation']['errorValue'];
        }
        // has error and error value          
        
        // return
        return $value;
        
    }
    // validate settings field
 
    private function validateSettingsFieldEmail( $settingsId, $field, $value ) {
        // check email
        if( ! is_email( $value ) ){
            
            // get textDomain
            $textDomain = $this->common->getSetting('textDomain');
            // get error text
            $errorText = __( 'Enter a valid email adress.', $textDomain );
            // add spacing
            $errorText .= '&nbsp&nbsp&nbsp';
            // add field prefix to error
            $errorText .= __( 'At:', $textDomain ); 
            // add field to error
            $errorText .= '&nbsp' . $field['args']['label']; 
            
            // add error
            add_settings_error( $settingsId, $field['id'], $errorText );
            // set error
            return true;
        }
        // check email
        
        // return
        return false;
    }
}
