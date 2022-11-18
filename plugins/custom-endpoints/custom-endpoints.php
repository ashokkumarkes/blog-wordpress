<?php
/*
Plugin Name: Custom Endpoints
Description: Custom endpoint for Wordpress REST APIs.
Author: Noesis Knowlegde Solutions Pvt. Ltd.
Version: 1.0
Author URI: https://noesis.tech
Plugin URI: https://noesis.tech
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

function installer()
{
    include_once plugin_dir_path(__FILE__) . "installer.php";
}
register_activation_hook(__file__, 'installer');

$file_includes = array(
    'actions.php',
    'core-functions.php',
    'filters.php',
    'rest-init.php',
    'rest-routes.php'
);

// Include files from $file_includes array.
foreach ($file_includes as $file) 
{
    include_once plugin_dir_path(__FILE__) . $file;
}
