<?php

/*
 * Plugin Name: Advanced Custom Fields: Autocomplete
 * Plugin URI: https://github.com/generoi/acf-autocomplete-field-type
 * Description: Add an ACF Autocomplete field type
 * Version: 1.0.1
 * Author: Shayan Abbas
 * Author URI: https://linkedin.com/in/shayanabbas
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once $composer;
}

// check if class already exists
if (!class_exists('acf_field_autocomplete')) :

    class acf_field_autocomplete
    {

        // vars
        var $settings;


        /*
        *  __construct
        *
        *  This function will setup the class functionality
        *
        *  @type    function
        *  @since   1.0.0
        *
        *  @param   void
        *  @return  void
        */

        function __construct()
        {

            // settings
            // - these will be passed into the field class.
            $this->settings = array(
            'version'   => '1.0.0',
            'url'       => plugin_dir_url(__FILE__),
            'path'      => plugin_dir_path(__FILE__)
            );


            // include field
            add_action('acf/include_field_types', array($this, 'include_field')); // v5
            add_action('acf/register_fields', array($this, 'include_field')); // v4
        }


        /*
        *  include_field
        *
        *  This function will include the field type class
        *
        *  @type    function
        *  @since   1.0.0
        *
        *  @param   $version (int) major ACF version. Defaults to false
        *  @return  void
        */

        function include_field($version = false)
        {

            // support empty $version
            if (!$version) {
                $version = 4;
            }


            // load textdomain
            load_plugin_textdomain('acf-autocomplete', false, plugin_basename(dirname(__FILE__)) . '/lang');


            // include
            include_once('fields/class-acf-field-autocomplete-v' . $version . '.php');
        }
    }


// initialize
    new acf_field_autocomplete();


// class_exists check
endif;
