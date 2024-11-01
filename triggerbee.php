<?php
   /*
   Plugin Name: Triggerbee
   Plugin URI: https://triggerbee.com/
   Description: Triggerbee tracking script
   Author: Triggerbee
   License: Apache
   Version: 2.0  */

$triggerbee_version = "2.0";

	require_once( dirname( __FILE__ ) . '/options.php' );

	add_action('init', 		'triggerbee_init');
	function triggerbee_init()
	{
	    global $triggerbee_version;
	     	    
	    wp_register_script('triggerbee_js',       plugins_url('src/triggerbee.js',       __FILE__), array(),                $triggerbee_version, true);
	    wp_register_script('triggerbee_addons_js', plugins_url('src/triggerbee_addons.js', __FILE__), array('triggerbee_js'), $triggerbee_version, true);
	}
	
	add_action ( 'wp_head', 'triggerbee_head' );
    function triggerbee_head()
    { 
        global $triggerbee_default_options;
        
        $settings = wp_parse_args( get_option( 'triggerbee', $triggerbee_default_options ), $triggerbee_default_options );
        
        $tracking_id = $settings['tracking_id'];
        
        echo '<script type="text/javascript">';
        echo 'var mtr_site_id = '.json_encode($tracking_id).';';
        
        echo 'var triggerbee_options = '.json_encode($settings).';';
        
        echo '</script>'; 
    }
	
	add_action( 'wp_enqueue_scripts', 'triggerbee_enqueue_scripts' );
	function triggerbee_enqueue_scripts()
	{
	   wp_enqueue_script('triggerbee_js');
	   wp_enqueue_script('triggerbee_addons_js');
	}	
?>