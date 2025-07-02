<?php
/**
 * Plugin Name: Auto-Activate OceanWP Theme
 * Description: Automatically activates OceanWP theme if it exists
 * Version: 1.2
 * 
 * This must-use plugin ensures OceanWP is always the active theme
 * when the code is deployed via git pull or any other method.
 */

// Only run if not in CLI mode
if (!defined('WP_CLI') || !WP_CLI) {
    
    // Hook into 'setup_theme' which runs before the theme is loaded
    add_action('setup_theme', function() {
        $desired_theme = 'oceanwp';
        $current_theme = get_option('stylesheet');
        
        // Check if we need to switch themes
        if ($current_theme !== $desired_theme) {
            $theme_root = get_theme_root();
            
            // Verify OceanWP exists
            if (file_exists($theme_root . '/' . $desired_theme . '/style.css')) {
                // Get the theme object to verify it's valid
                $theme = wp_get_theme($desired_theme);
                
                if ($theme->exists() && !$theme->errors()) {
                    // Update the theme options directly
                    update_option('stylesheet', $desired_theme);
                    update_option('template', $desired_theme);
                    
                    // Clear any theme caches
                    wp_clean_themes_cache();
                    
                    // Log the activation
                    if (function_exists('error_log')) {
                        error_log('Auto-activated OceanWP theme via must-use plugin');
                    }
                }
            }
        }
    }, 1);
}