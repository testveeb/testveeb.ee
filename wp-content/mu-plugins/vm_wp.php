<?php

/*
Plugin Name: Veebimajutus.ee
Plugin URI: https://www.veebimajutus.ee
Description:  Updates siteurl if necessary in order to avoid endless redirects. Includes login option through Veebimajutus control panel.
Author: Veebimajutus.ee | Elkdata OÜ
Version: 1.7
Author URI: https://www.veebimajutus.ee
*/

namespace Elkdata\WordPress\Plugins;

class ControlPanelActionsPlugin
{
    public function __construct()
    {
        /*
         * Updates the home and siteurl on every website visit between www or non-www.
         * This avoids endless redirection conflicts when trying to configure website redirection from control panel or
         * when configuring a htaccess based redirection.
         * For example when you set the WP siteurl in WP to be www.example.com, then WP can begin to redirect non-www
         * URLs to www URLs. And when you try to configure non-www URL to be used through the control panel, then it
         * will conflict with that redirection that the WP does (redirection configured from control panel tries to
         * redirect from http://www.example.com to http://example.com and at the same time WP tries to redirect from
         * http://example.com to http://www.example.com, causing a redirection loop). This siteurl updating hack here
         * resolves this.
         * This means that some content might be saved into WP database with www prefixed URLs and some not, in case no
         * single version has been configured. This however shouldn't be a problem as long as both URLs work.
         */

        $url = set_url_scheme('http://' . $_SERVER['HTTP_HOST']);
        $siteurl = get_option('siteurl');

        $current_url = parse_url($url);
        $current_siteurl = parse_url($siteurl);

        $current_url_host = preg_replace('/^www\./', '', $current_url['host']);
        $current_siteurl_host = preg_replace('/^www\./', '', $current_siteurl['host']);

        // Only update if the domain name is the same and only using with or without www is the difference

        $compare_url_host = idn_to_ascii($current_url_host);
        $compare_siteurl_host = idn_to_ascii($current_siteurl_host);

        // Make sure that the website is viewed from the same domain that is defined in the WP settings
        if (strcasecmp($compare_url_host, $compare_siteurl_host) == 0) {
            // Check if there is a difference in the host URL (does it have www prefix difference between current
            // URL and WP URL). If there is, save the new URL to WP settings.
            if ($url != $siteurl) {
                $new_siteurl = $current_siteurl['scheme'] . '://' . $current_url['host'] .
                    (isset($current_siteurl['path']) ? $current_siteurl['path'] : '');

                update_option('siteurl', $new_siteurl);
                update_option('home', $new_siteurl);
            }
        }
    }

    public function process_login()
    {
        if (!isset($_GET['vmautologin']) || strlen($_GET['vmautologin']) == 0) {
            return false;
        }

        $transient_key = '_onetimepassword_' . base64_decode($_GET['vmautologin']);
        $username = get_transient($transient_key);
        delete_transient($transient_key);

        if (empty($username)) {
            return false;
        }

        $user = get_user_by('login', $username);
        if (!isset($user->ID)) {
            return false;
        }

        $secure_cookie = '';
        wp_set_auth_cookie($user->ID, true, $secure_cookie);
        wp_redirect(admin_url());
        exit;
    }

    /**
     * Redirect from Elementor "Getting Started" page to our custom one.
     */
    public function elementor_astra_welcome()
    {
        if (!is_user_logged_in()) {
            return false;
        }

        // Elementor currently redirects to an elementor onboarding page.
        // Set elementor "already had onboarding" option, to not be redirected to "admin.php?page=elementor-app#onboarding".
        // Currently we consider astra themes and our welcome page better for introduction than the elementor welcome page.
        if (is_plugin_active('elementor/elementor.php')) {
            update_option('elementor_onboarded', true);
        }

        // Starter themes (Astra) now has its own onboarding redirection too. Disable it.
        delete_option('st_start_onboarding');

        $already_done = get_option('veebimajutus_elementor_intro_done');

        if ($already_done && @$_GET['page'] != 'elementor-getting-started') {
            return false;
        }

        update_option('veebimajutus_elementor_intro_done', true);
        wp_redirect(admin_url('admin.php?page=veebimajutus-elementor-getting-started'));
        exit;
    }

    public function veebimajutus_admin_menu()
    {
        add_menu_page(
            __('Veebimajutuse abimaterjal'),
            __('Veebimajutuse abimaterjal'),
            'manage_options',
            'veebimajutus-elementor-getting-started',
            [$this, 'veebimajutus_admin_elementor_page'],
            'dashicons-welcome-learn-more',
            2
        );
    }

    public function veebimajutus_admin_menu_need_help()
    {
        add_submenu_page(
            // To hide from menu, use null.
            'index.php',
            __('Vajan kodulehega abi'),
            __('Vajan kodulehega abi'),
            'manage_options',
            'veebimajutus-need-help',
            [$this, 'veebimajutus_need_help_page'],
            4
        );
    }

    public function veebimajutus_admin_elementor_page()
    {
        global $wpdb;

        // Make elementor the preselected astra option
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $wpdb->options (option_name, option_value) VALUES (%s, %s)
                 ON DUPLICATE KEY UPDATE option_name = %s, option_value = %s",
                'astra_sites_settings',
                'a:1:{s:12:"page_builder";s:9:"elementor";}',
                'astra_sites_settings',
                'a:1:{s:12:"page_builder";s:9:"elementor";}'
            )
        );

        ?>
        <div class="wp-dashboard-section p-6">
            <h1 class="text-2xl font-bold mb-6 text-center">
                <?php echo __('Veebimajutuse abimaterjal'); ?>
            </h1>

            <div class="main-cta cta-card mb-6 p-6 bg-white rounded-lg shadow-sm">
                <h2 class="text-xl font-semibold mb-4 text-center">
                    <?php echo __('Vaata videost, kuidas luua veebileht või alusta kohe loomist.'); ?>
                </h2>

                <div class="video-container mb-4 rounded-lg overflow-hidden">
                    <iframe width="100%" height="100%" src="https://www.youtube-nocookie.com/embed/OmKeIdORDjc" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

                <div class="text-center">
                    <a href="<?php echo admin_url('themes.php?page=ai-builder'); ?>"
                        class="inline-block cta-button px-6 py-3 rounded-lg font-semibold text-center mx-auto text-base focus:text-white">
                            <?php echo __('Alusta veebilehe loomist'); ?>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="cta-card p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3">
                        <?php echo __('Veebilehe loomise kursus Veebikoolist'); ?>
                    </h2>
                    <p class="text-gray-600 mb-5">
                        <?php echo __('Kursusel näitame samm-sammult, kuidas luua ja kohandada oma veebilehte või e-poodi WordPressi platvormil.'); ?>
                    </p>
                    <div class="text-center">
                        <a href="https://veebikool.ee/courses/tee-endale-koduleht-voi-e-pood-valmis-tasuta-kursus-ettevotjale/#learndash-course-content"
                            target="_blank" class="inline-block cta-button-secondary px-6 py-3 rounded-lg font-medium text-center mx-auto text-base focus:text-white">
                                <?php echo __('Liitu kursusega'); ?>
                        </a>
                    </div>
                </div>

                <div class="cta-card p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3">
                        <?php echo __('Õpinädal ekspertide toe ja nõuannetega'); ?>
                    </h2>
                    <p class="text-gray-600 mb-5">
                        <?php echo __('Õpinädalal aitavad Sind veebilehe loomisel kogemustega koolitajad, toetav grupp ja kasulikud live-ülekanded.'); ?>
                    </p>
                    <div class="text-center">
                        <a href="https://veebikool.ee/opinadal-tee-endale-uus-koduleht/"
                            target="_blank" class="inline-block cta-button-secondary px-6 py-3 rounded-lg font-medium text-center mx-auto text-base focus:text-white">
                                <?php echo __('Liitu õpinädalaga'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>© <?php echo date('Y'); ?> <?php echo __('Veebimajutus - Parim valik sinu kodulehele'); ?></p>
            </div>
        </div>
        <?php
    }

    public function veebimajutus_need_help_page()
    {
        ?>
        <div id="vm-contact-form-wrapper">
            <?php echo do_shortcode('[veebimajutus-contact-form]'); ?>
        </div>
        <?php
    }

    /**
     * Hide all notices.
     */
    public function hide_notices()
    {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }

    public function vm_scripts()
    {
        echo <<<EOT
<style>
#wpadminbar #wp-admin-bar-vm-adminbar-button a {
    color: orange !important;
}

/* Add margins between the rows of the contact form. */
#vm-contact-form-wrapper {
    margin: 20px 0;
    max-width: 800px;
}
</style>
EOT;
    }

    public function vm_scripts_intro()
    {
        // Some of theses styles normally come with the elementor plugin (class prefix e- instead of elk-). As we don't
        // install it anymore, we need to add our own styling rules. Also to not go into conflict with elementor
        // styles, if the plugin gets installed.
        echo <<<EOT
<style>
/* Custom styling */
.wp-dashboard-section {
    max-width: 900px;
    margin: 20px auto;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-family: 'Overpass', sans-serif;
    color: #333;
}
.cta-button {
    background-color: #4991db;
    color: white;
    transition: all 0.3s ease;
}
.cta-button:hover {
    background-color: #3776b8;
    transform: translateY(-2px);
    color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.cta-button-secondary {
    background: #f8f8f8;
}
.cta-button-secondary:focus {
    color: #333;
}
.cta-card {
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}
.cta-card:hover {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
    transform: translateY(-3px);
}
.video-container {
    background-color: #f8fafc;
    height: 350px;
}
@media only screen and (max-width: 720px) {
    .video-container {
        height: 250px;
    }
}
@media only screen and (max-width: 680px) {
    .video-container {
        height: 200px;
    }
}
@media only screen and (max-width: 480px) {
    .video-container {
        height: 150px;
    }
}

#adminmenu a.toplevel_page_veebimajutus-elementor-getting-started {
    color: orange;
}
#adminmenu li.current a.toplevel_page_veebimajutus-elementor-getting-started {
    background: orange;
}

/* ZipWP plugin styles override due to their own plugins styles conflict messing up the buttons. */
.kSmgsM, .submit-survey-btn, .view-website-btn {
    background: #2563eb !important;
    color: #fff;
}
.kSmgsM:hover, .submit-survey-btn:hover, .view-website-btn:hover {
    background: #1d4ed8 !important;
    color: #fff;
}
</style>
<script>
/*
 * Custom fix for a ridiculous astra-sites/languages/astra-sites-et.po translation.
 * msgid "Block Editor"
 * msgstr "Blokeerija"
 */

setTimeout(function() {
    wp.i18n.setLocaleData({
      'Block Editor': ['Plokiredaktor']
    }, 'astra-sites');
}, 1000);
</script>
EOT;
    }

    public function vm_adminbar_button($wp_admin_bar)
    {
        $args = [
            'id' => 'vm-adminbar-button',
            'title' => 'Vajan kodulehega abi',
            'href' => admin_url('index.php?page=veebimajutus-need-help'),
            'meta' => [
                'class' => 'vm-adminbar-action'
            ]
        ];
        $wp_admin_bar->add_node($args);
    }

    /**
     * This function displays the validation messages, the success message, the container of the validation messages, and the
     * contact form.
     */
    public function display_contact_form()
    {
        $validation_messages = [];
        $success_message = '';

        if (isset($_POST['contact_form'])) {
            $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

            // Validate the data.
            if (strlen($message) === 0) {
                $validation_messages[] = esc_html__('Palun sisesta probleemi kirjeldus.');
            }

            // Send an email to Veebimajutus support if there are no validation errors.
            if (empty($validation_messages)) {
                $mail = get_option('admin_email');
                $subject = '[Kiri WordPressist] Palun abi seoses kodulehega: ' . $_SERVER['SERVER_NAME'] . ' (' . $_SERVER['SERVER_ADDR'] . ')';

                // Set a proper mail from address.
                add_filter('wp_mail_from', function ($current) { return get_option('admin_email'); });

                $sent = wp_mail('abi@veebimajutus.ee', $subject, "Mure kirjeldus:\n\n" . $message . "\n\nLehe siteurl: "
                    . get_site_url());

                if ($sent) {
                    $success_message = esc_html__('Teade Veebimajutus.ee klienditoele edastatud.');
                } else {
                    $validation_messages[] = "Kirja saatmine abi@veebimajutus.ee'le ebaõnnestus.";
                }
            }
        }

        // Display the validation errors.
        if (!empty($validation_messages)) {
            foreach ($validation_messages as $validation_message) {
                echo '<div class="notice error"><p>' . esc_html($validation_message) . '</p></div>';
            }
        }

        //Display the success message
        if (strlen($success_message) > 0) {
            echo '<div class="notice notice-success"><p>' . esc_html($success_message) . '</p></div>';
        }
        ?>

        <form id="veebimajutus-contact-form" action="<?php echo esc_url(get_permalink()); ?>" method="post">
            <input type="hidden" name="contact_form">

            <h2 class="title">Saada teade Veebimajutus.ee klienditoele.</h2>
            <p>Kirjelda allpool, millega abi oleks vaja.</p>

            <div class="form-section">
                <textarea class="large-text code" name="message"></textarea>
            </div>

            <p class="submit">
                <input type="submit" class="button button-primary" value="<?php echo esc_attr('Saada abipalve'); ?>">
            </p>
        </form>

    <?php

    }

    /**
     * Add Plausible Analytics script to website head section.
     */
    public function plausible_add_analytics_script()
    {
        $host = get_option("vm_plausible_domain_name");
        if (empty($host)) {
            $url = parse_url(site_url());
            $host = $url["host"] . (isset($url["path"]) ? $url["path"] : '');
        }
        $tracking_enabled = get_option("vm_plausible_analytics_enabled");

        if (!$tracking_enabled) {
            return false;
        }

        $disabled_admin_tracking = get_option("vm_plausible_disable_admin_analytics");

        if (($disabled_admin_tracking && !current_user_can('administrator')) || !$disabled_admin_tracking) {
            echo "<script async defer data-domain='" . $host . "' src='https://plausible.io/js/plausible.js'></script>";
        }
    }

    /**
     * Register some Plausible related WordPress settings.
     */
    public function plausible_register_settings()
    {
        $url = parse_url(site_url());
        $default_host = $url["host"] . (isset($url["path"]) ? $url["path"] : '');
        // Use the path also in URL, since we want to allow analytics per WP site, not per domain and Plausible
        // supports such domains.
        add_option('vm_plausible_domain_name', $default_host);
        // By default don't take logged in admin accounts into account for website visiting statistics.
        add_option('vm_plausible_disable_admin_analytics', 1);

        register_setting('vm_plausible_options_group', 'vm_plausible_analytics_enabled');
        register_setting('vm_plausible_options_group', 'vm_plausible_domain_name');
        register_setting('vm_plausible_options_group', 'vm_plausible_disable_admin_analytics');
    }

    /**
     * Register the Veebimajutus Plausible Analytics Settings page in WordPress.
     */
    public function plausible_register_options_page()
    {
        add_options_page('Veebimajutus.ee Plausible Analytics', 'Veebimajutus.ee Plausible Analytics', 'manage_options', 'vm_plausible', [$this, 'vm_plausible_options_page']);
    }

    /**
     * The content for the Veebimajutus Plausible Analytics Settings page.
     */
    public function vm_plausible_options_page()
    {
?>
  <div>
  <?php screen_icon(); ?>
  <h2>Veebimajutus.ee Plausible Analytics</h2>

  <form method="post" action="options.php">
  <?php settings_fields('vm_plausible_options_group'); ?>
  <label for="vm_plausible_analytics_enabled">Tracking Enabled</label>
  <input type="checkbox" id="vm_plausible_analytics_enabled" disabled="disabled" <?php echo checked(1, get_option("vm_plausible_analytics_enabled"), false); ?>>
  <strong>(Can be enabled and disabled through <a href="https://admin.veebimajutus.ee" target="_blank">Veebimajutus control panel</a>)</strong>
  <input type="hidden" name="vm_plausible_analytics_enabled" value='1' <?php echo checked(1, get_option("vm_plausible_analytics_enabled"), false); ?>>
  <br><br>
  <label for="vm_plausible_domain_name">Domain</label>
  <input type="text" id="vm_plausible_domain_name" name="vm_plausible_domain_name" value="<?php echo get_option("vm_plausible_domain_name"); ?>">
  <br><br>
  <label for="vm_plausible_disable_admin_analytics">Disable Tracking of Admin Users</label>
  <input type="checkbox" id="vm_plausible_disable_admin_analytics" name="vm_plausible_disable_admin_analytics" value='1' <?php echo checked(1, get_option("vm_plausible_disable_admin_analytics"), false); ?>>
  <?php submit_button(); ?>
  </form>
  </div>
<?php
    }

    public function modify_plugins_data($all_plugins)
    {
        foreach ($all_plugins as $plugin_file => &$plugin_data) {
            if ($plugin_file == 'patchstack/patchstack.php') {
                $plugin_data['Name'] = 'Turvakaitse Patchstack';
                $plugin_data['Description'] = '<strong>Veebimajutus.ee soovitatud kodulehe turvakaitse.</strong> Tuvastab automaatselt pluginates, teemades ja WordPressi tuumas olevad turvaaugud ning kaitseb neid pahatahtlike rünnakute eest.';
            }
        }

        return $all_plugins;
    }

    public function veebimajutus_enqueue_styles()
    {
        // Tailwind CSS.
        wp_enqueue_style(
            'vm-plugin-tailwind',
            'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
            [],
            null
        );

        // Google Fonts - Overpass.
        wp_enqueue_style(
            'vm-plugin-google-fonts',
            'https://fonts.googleapis.com/css2?family=Overpass:wght@300;400;500;600;700&display=swap',
            [],
            null
        );
    }
}

// This plugin is not meant for command line executions.
// Expect Veebimajutus website system directory path for the plugin to do something.
// This is necessary so that when the website is moved to another service provider, our plugin actions would not run.
// For example we don't want the customer to contact our support when the website isn't hosted by us.
$cwd = getcwd();
if (php_sapi_name() != 'cli' && preg_match('#^/www/apache/domains/www\.#', $cwd)) {
    $elkdata = new ControlPanelActionsPlugin();
    add_action('plugins_loaded', [$elkdata, 'process_login']);

    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('astra-sites/astra-sites.php')) {
        add_action('admin_menu', [$elkdata, 'veebimajutus_admin_menu']);
        add_action('admin_init', [$elkdata, 'elementor_astra_welcome']);

        if (isset($_GET['page']) && in_array($_GET['page'], ['veebimajutus-elementor-getting-started', 'veebimajutus-need-help'])) {
            // Hide all WP notices on "Veebiehitaja tutvustus" page.
            add_action('admin_head', [$elkdata, 'hide_notices'], 1);
            add_action('admin_enqueue_scripts', [$elkdata, 'veebimajutus_enqueue_styles']);
        }
    }

    /* Contacting VM customer support related functions. */
    /*
    add_action('admin_menu', [$elkdata, 'veebimajutus_admin_menu_need_help']);
    add_action('wp_footer', [$elkdata, 'vm_scripts'], 0, 999);
    add_action('admin_footer', [$elkdata, 'vm_scripts'], 0, 999);
    add_action('admin_bar_menu', [$elkdata, 'vm_adminbar_button'], 999);

    add_shortcode('veebimajutus-contact-form', [$elkdata, 'display_contact_form']);
    */

    add_action('admin_footer', [$elkdata, 'vm_scripts_intro'], 0, 999);

    // If Plausible Analytics plugin is active on this WordPress installation, prefer this over ours.
    // When the website is moved to another hoster, our plugin won't run and the client should
    // install the Plausible Analytics plugin.
    if (!is_plugin_active('plausible-analytics/plausible-analytics.php')) {
        add_action('admin_init', [$elkdata, 'plausible_register_settings']);
        add_action('admin_menu', [$elkdata, 'plausible_register_options_page']);
        add_action('wp_head', [$elkdata, 'plausible_add_analytics_script']);
    }

    // If Plausible is in use along with WP-Rocket plugin, then make sure that plausible is excluded from
    // WP-Rocket's JS minification, because it breaks the tracking.
    $tracking_enabled = get_option("vm_plausible_analytics_enabled");
    if (($tracking_enabled || is_plugin_active('plausible-analytics/plausible-analytics.php')) && is_plugin_active('wp-rocket/wp-rocket.php')) {
        $settings = get_option('wp_rocket_settings');
        if (is_array($settings) && array_key_exists('exclude_js', $settings) && !in_array('plausible.io', $settings['exclude_js'])) {
            $settings['exclude_js'][] = 'plausible.io';
            update_option('wp_rocket_settings', $settings);
        }
    }

    add_filter('all_plugins', [$elkdata, 'modify_plugins_data']);
}
