<?php
/*
        @package    pleisterman/wp-enhanced-jwt-rest-api
   
        file:       SettingsHelpTab.php
        function:   handles help tab for settings page
*/

namespace Admin;

use Common\Common;
use Admin\HtmlGenerator;
use Admin\Sanitizer;
use Admin\Validator;

class SettingsHelpTab {        
    // members
    private $common = null;
    private $htmlGenerator = null;
    private $sanitizer = null;
    private $validator = null;
    private $iconClass = 'dashicons dashicons-editor-help';
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
    
    // show 
    public function show() {
        
        
        // open html wrapper
        echo '<div class="wrap">';
        
            // create sub title
            $this->htmlGenerator->createSubTitle( $this->iconClass, 'Help' );
            
            // create how to
            $this->createHowTo();
            
        // close html wrapper
        echo '</div>';
    }	
    // show 
    
    // create how to
    private function createHowTo() {
        
        // create how to title
        echo '<h2>' . __( 'How to configure the plugin:', $this->common->getSetting( 'textDomain' ) ) . '</h2>';
        // create how to text
        echo '<div style="margin-bottom: 2em;">' . __( 'When activated the plugin will add a JWT authorisation check to the wp-api', $this->common->getSetting( 'textDomain' ) ) . '</div>';
        
        // start list
        echo '<ul style="padding: 0.2em;">';
        
        // create how to text
        echo '<li style="padding: 0.2em;">' . __( 'If you check the option `Autosave JWT` the JWT will be saved in a protected cookie.', $this->common->getSetting( 'textDomain' ) ) . '</li>';
        
        // end list
        echo '</ul>';
    }
    // create how to
}
