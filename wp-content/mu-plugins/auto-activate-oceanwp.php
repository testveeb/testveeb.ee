<?php
/**
 * Plugin Name: Auto-Activate OceanWP Theme
 * Description: Automatically activates OceanWP theme if it exists
 * Version: 1.0
 * 
 * This must-use plugin ensures OceanWP is always the active theme
 * when the code is deployed via git pull or any other method.
 */

// Only run in the admin area and on the front-end, not during CLI operations
if (!defined('WP_CLI') || !WP_CLI) {
    add_action('after_setup_theme', function() {
        $desired_theme = 'oceanwp';
        $theme_root = get_theme_root();
        $current_theme = get_option('stylesheet');
        
        // Check if OceanWP exists and is not already active
        if ($current_theme !== $desired_theme && file_exists($theme_root . '/' . $desired_theme)) {
            // Get the theme object to verify it's valid
            $theme = wp_get_theme($desired_theme);
            
            if ($theme->exists() && !$theme->errors()) {
                // Switch to OceanWP
                switch_theme($desired_theme);
                
                // Log the activation for debugging purposes
                error_log('Auto-activated OceanWP theme via must-use plugin');
            }
        }
    }, 1); // Priority 1 to run early
}