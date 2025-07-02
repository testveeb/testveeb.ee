<?php
/**
 * Plugin Name: OceanWP Recovery Helper
 * Description: Temporary plugin to ensure OceanWP functions are available. Delete this file once the theme is working properly.
 * Version: 1.0
 */

// Emergency function definition to prevent fatal errors
if (!function_exists('oceanwp_html_classes')) {
    function oceanwp_html_classes() {
        $classes = array('no-js');
        
        // Add browser classes
        if (wp_is_mobile()) {
            $classes[] = 'is-mobile';
        }
        
        // RTL
        if (is_rtl()) {
            $classes[] = 'rtl';
        }
        
        return implode(' ', $classes);
    }
}

// Add notice in admin
add_action('admin_notices', function() {
    if (get_option('stylesheet') !== 'oceanwp') {
        echo '<div class="notice notice-warning"><p><strong>OceanWP Recovery:</strong> OceanWP theme is not active. Please activate it from Appearance > Themes.</p></div>';
    } else {
        echo '<div class="notice notice-info"><p><strong>OceanWP Recovery:</strong> OceanWP is active. If the site is working properly, you can delete the file: <code>wp-content/mu-plugins/oceanwp-recovery.php</code></p></div>';
    }
});