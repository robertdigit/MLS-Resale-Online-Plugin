<?php
/**
 * Plugin Name: Resales Online MLS Plugin
 * Plugin URI: https://clarkdigital.es/resales-online-real-estate-networking-in-the-costa-del-sol/
 * Description: Seamlessly connect all your ReSales Online properties to your website. This plugin, designed for estate agents, simplifies linking your ReSales Online listings with your site..
 * Version: 1.2.3
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author: Clark Digital
 * License: GPL2
 */

// Prevent direct access to the file.
if ( !defined('ABSPATH') ) {
    exit;
}

function is_font_awesome_loaded() {
    global $wp_styles;

    if (!isset($wp_styles->registered)) {
        return false;
    }

    foreach ($wp_styles->registered as $style) {
        if (
            strpos($style->src, 'font-awesome') !== false || // Common handle
            strpos($style->src, 'fontawesome') !== false || // Alternate names
            strpos($style->src, 'cdnjs.cloudflare.com/ajax/libs/font-awesome') !== false // CDN URL
        ) {
            return true;
// 			return $style->src;
        }
    }

    return false;
}

function mls_plugin_enqueue_scripts() {
	
	$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'));
    $plugin_version = $plugin_data['Version'];
	
    wp_enqueue_style( 'style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), $plugin_version );
    wp_enqueue_style( 'responsive-style', plugin_dir_url(__FILE__) . 'assets/css/responsive.css', array(), $plugin_version );
    wp_enqueue_style( 'slick-css', plugin_dir_url(__FILE__) . 'assets/css/slick.css', array(), '1.0.0' );
    wp_enqueue_style( 'slick-theme', plugin_dir_url(__FILE__) . 'assets/css/slick-theme.css', array(), '1.0.0' );
    wp_enqueue_style( 'lightgallery-css', plugin_dir_url(__FILE__) . 'assets/css/lightgallery.css', array(), '1.0.0' );
    wp_enqueue_style( 'zoom-lg', plugin_dir_url(__FILE__) . 'assets/css/lg-zoom.css', array(), '1.0.0' );
    wp_enqueue_style( 'thumbnail-lg', plugin_dir_url(__FILE__) . 'assets/css/lg-thumbnail.css', array(), '1.0.0' );
    wp_enqueue_style( 'video-lg', plugin_dir_url(__FILE__) . 'assets/css/lg-video.css', array(), '1.0.0' );
    wp_enqueue_style( 'autoplay-lg', plugin_dir_url(__FILE__) . 'assets/css/lg-autoplay.css', array(), '1.0.0' );
    wp_enqueue_style( 'fullscreen-lg', plugin_dir_url(__FILE__) . 'assets/css/lg-fullscreen.css', array(), '1.0.0' );
    wp_enqueue_style( 'select-css', plugin_dir_url(__FILE__) . 'assets/css/easySelectStyle.css', array(), '1.0.0' );
    wp_enqueue_style( 'tagsinput-css', plugin_dir_url(__FILE__) . 'assets/css/jquery.tagsinput-revisited.css', array(), '1.0.0' );
    wp_enqueue_style( 'tellinput-css', plugin_dir_url(__FILE__) . 'assets/css/intlTelInput.min.css', array(), '1.0.0' );
    wp_enqueue_style( 'datepicker-css', plugin_dir_url(__FILE__) . 'assets/css/datepicker.min.css', array(), '1.0.0' );
    if (!is_font_awesome_loaded()) { wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', array(), '6.6.0' ); }
    wp_enqueue_style( 'jqueryui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css', array(), '1.12.1' );

    // Enqueue your custom JavaScript file
  
    wp_enqueue_script(
        'mls-plugin-ajax-script',
        plugin_dir_url(__FILE__) . 'assets/js/main.js',
        array('jquery'),
        $plugin_version,
        true
    );
    wp_enqueue_script(
        'slick-script',
        plugin_dir_url(__FILE__) . 'assets/js/slick.min.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'lightgallery-script',
        plugin_dir_url(__FILE__) . 'assets/js/lightgallery.min.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'thumbnail-script',
        plugin_dir_url(__FILE__) . 'assets/js/lg-thumbnail.umd.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'zoom-script',
        plugin_dir_url(__FILE__) . 'assets/js/lg-zoom.umd.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'autoplay-script',
        plugin_dir_url(__FILE__) . 'assets/js/lg-autoplay.umd.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'fullscreen-script',
        plugin_dir_url(__FILE__) . 'assets/js/lg-fullscreen.umd.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'video-script',
        plugin_dir_url(__FILE__) . 'assets/js/lg-video.umd.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'select-script',
        plugin_dir_url(__FILE__) . 'assets/js/easySelect.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'tellinput-script',
        plugin_dir_url(__FILE__) . 'assets/js/intlTelInput.min.js',
        array('jquery'),
        '1.0.0',
        true
    );
    if (!is_font_awesome_loaded()) { wp_enqueue_script(
        'font-awesome-script',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js',
        array('jquery'),
        '6.6.0',
        true
    ); }
    wp_enqueue_script(
        'jquery-ui-script',
        'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
        array('jquery'),
        '1.12.1',
        true
    );
    wp_enqueue_script(
        'tagsinput-script',
        plugin_dir_url(__FILE__) . 'assets/js/jquery.tagsinput-revisited.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'sidebar-script',
        plugin_dir_url(__FILE__) . 'assets/js/jquery.sticky-sidebar.min.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'datepicker-script',
        plugin_dir_url(__FILE__) . 'assets/js/datepicker.min.js',
        array('jquery'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'mls-map',
        plugin_dir_url(__FILE__) . 'assets/js/mls-map.js',
        array('jquery'),
        $plugin_version,
        true
    );

    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css', array(), '1.7.1');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array(), '1.7.1', true);

	// Get translations
    $translations = [
        'mls_plugin_prop_detailsidebaroffset' => get_option('mls_plugin_prop_detailsidebaroffset', '20'),
		'search_area' => mls_plugin_translate('placeholders','search_area'),
        'search_property_type' => mls_plugin_translate('placeholders','search_property_type'),
		'search_reference_id' => mls_plugin_translate('placeholders','search_reference_id'),
		'mls_propertdetail_form_name' => mls_plugin_translate('error','mls_propertdetail_form_name'),
		'mls_propertdetail_form_email' => mls_plugin_translate('error','mls_propertdetail_form_email'),
		'mls_propertdetail_form_valid_email' => mls_plugin_translate('error','mls_propertdetail_form_valid_email'),
		'mls_propertdetail_form_phone_number' => mls_plugin_translate('error','mls_propertdetail_form_phone_number'),
		'mls_propertdetail_form_valid_phone_number' => mls_plugin_translate('error','mls_propertdetail_form_valid_phone_number'),
		'mls_propertdetail_form_scheduledate' => mls_plugin_translate('error','mls_propertdetail_form_scheduledate'),
		'mls_propertdetail_form_submitting' => mls_plugin_translate('error','mls_propertdetail_form_submitting'),
		'mls_propertdetail_form_submitrequest' => mls_plugin_translate('error','mls_propertdetail_form_submitrequest'),
		'mls_propertdetail_form_submiterror' => mls_plugin_translate('error','mls_propertdetail_form_submiterror'),
		'mls_propertdetail_form_submitrequiredmissing' => mls_plugin_translate('error','mls_propertdetail_form_submitrequiredmissing'),
		'mls_propertdetail_form_name' => mls_plugin_translate('error','mls_propertdetail_form_name'),
    ];

    // Localize the script
    wp_localize_script('mls-plugin-ajax-script', 'mlsTranslations', $translations);
    // Localize the script with the admin-ajax URL for AJAX calls
    wp_localize_script('mls-plugin-ajax-script', 'mls_ajax_obj', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
}
add_action('wp_enqueue_scripts', 'mls_plugin_enqueue_scripts', PHP_INT_MAX);


// Hook to enqueue styles and scripts for the admin area
function mls_plugin_enqueue_admin_assets() {
	
	$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'));
    $plugin_version = $plugin_data['Version'];
	
	 // Enqueue the WordPress Media Library scripts
    wp_enqueue_media();
	
    // Enqueue admin styles
    wp_enqueue_style(
        'mls-plugin-admin-style', // Handle
        plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', // Path to the stylesheet
        array(), // Dependencies (if any)
        $plugin_version, // Version number
        'all' // Media type
    );
    wp_enqueue_style( 'mls-plugin-select-css', plugin_dir_url(__FILE__) . 'assets/css/easySelectStyle.css', array(), '1.0.0', 'all');
    wp_enqueue_style( 'mls-plugin-tagsinput-css', plugin_dir_url(__FILE__) . 'assets/css/jquery.tagsinput-revisited.css', array(), '1.0.0', 'all');
	wp_enqueue_style( 'font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', array(), NULL );

    // Enqueue admin scripts
    wp_enqueue_script(
        'mls-plugin-admin-script', // Handle
        plugin_dir_url(__FILE__) . 'assets/js/admin-script.js', // Path to the script
        array('jquery'), // Dependencies (jQuery in this case)
        $plugin_version, // Version number
        true // Load in footer
    );
    wp_enqueue_script(
        'mls-plugin-select-script', // Handle of the script
        plugin_dir_url(__FILE__) . 'assets/js/easySelect.js', // Path to the JS file
        array('jquery'), // Dependencies
        '1.0.0', // Version number (optional)
        true // Load the script in the footer
    );
    wp_enqueue_script(
        'mls-plugin-tagsinput-script', // Handle of the script
        plugin_dir_url(__FILE__) . 'assets/js/jquery.tagsinput-revisited.js', // Path to the JS file
        array('jquery'), // Dependencies
        '1.0.0', // Version number (optional)
        true // Load the script in the footer
    );
	wp_enqueue_script(
        'font-awesome-script', // Handle of the script
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js', // Path to the JS file
        array('jquery'), // Dependencies
        '6.6.0', // Version number (optional)
        true // Load the script in the footer
    );
	wp_enqueue_script('sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array('jquery'), null, true);


    // Localize script if you need to pass PHP data to JS
    wp_localize_script(
        'mls-plugin-admin-script', 
        'mlsPluginAdmin', 
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );
}
add_action('admin_enqueue_scripts', 'mls_plugin_enqueue_admin_assets');



// Hook to enqueue color picker with transparency support
add_action('admin_enqueue_scripts', 'mls_plugin_enqueue_color_picker');
function mls_plugin_enqueue_color_picker($hook_suffix) {
    // Enqueue WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Enqueue Alpha Color Picker script
    wp_enqueue_script('alpha-color-picker', plugins_url('assets/js/wp-color-picker-alpha.min.js', __FILE__), array('wp-color-picker'), '1.0', true);

    // Initialize the color picker with transparency support
    wp_add_inline_script('alpha-color-picker', '
        jQuery(document).ready(function($){
            $(".mls-color-field").each(function(){
                var defaultColor = $(this).val(); // Get the current value of the input field
                $(this).wpColorPicker({
                    defaultColor: defaultColor, // Use the value as the default color
                    palettes: true, // Allow predefined color palettes
                    clear: true,    // Allow transparency/clear button
                    mode: "hsv",
                    alpha: true // Enable alpha support for transparency
                });
            });
        });
    ');
}


function mls_add_rewrite_rules() {
	$prpdtselected_page_id = get_option('mls_plugin_property_detail_page_id', '');
    $prpdetailpage_id = $prpdtselected_page_id ? $prpdtselected_page_id : 7865;
    $prpdetailpage = get_post($prpdetailpage_id);
    if (get_option('mls_plugin_style_proplanghide')) {
$prpdetailpage_slug = get_option('mls_plugin_property_detail_page_slug');
}else{
$prpdetailpage_slug = $prpdetailpage ? $prpdetailpage->post_name : '';
}

    if ($prpdetailpage_slug) {
    add_rewrite_rule(
        $prpdetailpage_slug . '/([^/]+)/([^/]+)/?$',
        'index.php?pagename=' . $prpdetailpage_slug . '&property_title=$matches[1]&property_ref=$matches[2]',
        'top'
    );
}

}
add_action('init', 'mls_add_rewrite_rules');



function mls_query_vars($vars) {
    $vars[] = 'property_title';
    $vars[] = 'property_ref';
    return $vars;
}
add_filter('query_vars', 'mls_query_vars');

// Lead Form Database Creation
function mls_plugin_create_lead_form_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

    // Get current table columns
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name", ARRAY_A);
    $existing_columns = array_column($columns, 'Field');

    // Define the charset collation
    $charset_collate = $wpdb->get_charset_collate();

    // Check and create the table if it doesn’t exist
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        phone varchar(20) NOT NULL,
        comments text NOT NULL,
        referenceid varchar(255) NOT NULL,
        personvideo varchar(255) NOT NULL,
        lead_time varchar(255) NOT NULL,
        scheduledate varchar(255) NOT NULL,
        buyerseller varchar(255) NOT NULL,
		preferredlanguage varchar(255) NOT NULL,
        is_qualified tinyint(1) DEFAULT 0 NOT NULL,
        date_submitted datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Array of new columns to be added
    $new_columns = [
        'referenceid' => "ALTER TABLE $table_name ADD COLUMN referenceid varchar(255) NOT NULL",
        'personvideo' => "ALTER TABLE $table_name ADD COLUMN personvideo varchar(255) NOT NULL",
        'lead_time' => "ALTER TABLE $table_name ADD COLUMN lead_time varchar(255) NOT NULL",
        'scheduledate' => "ALTER TABLE $table_name ADD COLUMN scheduledate varchar(255) NOT NULL",
        'buyerseller' => "ALTER TABLE $table_name ADD COLUMN buyerseller varchar(255) NOT NULL",
        'is_qualified' => "ALTER TABLE $table_name ADD COLUMN is_qualified tinyint(1) DEFAULT 0 NOT NULL"
    ];

    // Loop through new columns and add if missing
    foreach ($new_columns as $column_name => $alter_sql) {
        if (!in_array($column_name, $existing_columns)) {
            $wpdb->query($alter_sql);
        }
    }
}


// Resale WebAPI Online API & Endpoints
define('RESALES_ONLINE_BASE_API_URL_V6', 'https://webapi.resales-online.com/V6/'); 
define('RESALES_ONLINE_API_ENDPOINTS_V6',  array(
    'getProperties' => 'SearchProperties',
    'getPropertiesTypes' => 'SearchPropertyTypes',
    'getLocations' => 'SearchLocations',
    'getProperty' => 'PropertyDetails',
    'getPropertiesFeatures' => 'SearchFeatures',
  ));

// Resale WebAPI Online Currency 
define('RESALES_ONLINE_API_CURRENCY',  array(
    'EUR' => '€',
    'GBP' => '£',
    'USD' => '$',
    'RUB' => '₽',
    'TRY' => '₺',
	'SAR' => 'ر.س',
  ));

// Validate MLS Plugin License and domain
function mls_plugin_is_license_valid() {
	 $api_url = 'https://crm.clarkdigital.es/wp-json/license/v1/validate'; // Replace with your validation server URL
	$domain = get_site_url();
	$parsed_url = wp_parse_url($domain);
	$domain = isset($parsed_url['host']) ? $parsed_url['host'] : $domain;
	$license_key = get_option('mls_plugin_license_key');
    // Prepare the request data
    $request_data = array(
        'license_key' => $license_key,
        'domain'      => $domain,
    );

    // Make the request to the validation server
    $response = wp_remote_post($api_url, array(
        'method'    => 'POST',
        'body'      => json_encode($request_data), // Send as JSON
        'timeout'   => 15,
        'headers'   => array('Content-Type' => 'application/json'),
    ));

    // Check for errors in the response
    if (is_wp_error($response)) {
        return false;
    }

    // Decode the response
    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    // Check if the validation was successful
    if (isset($data['success']) && $data['success']) {
        return $data;
    } else {
        return false;
    }
  }

// Changelog from github
function mls_plugin_fetch_changelog() {
    $url = 'https://api.github.com/repos/robertdigit/MLS-Resale-Online-Plugin/releases';

    // Fetch the data from GitHub
    $response = wp_remote_get($url, array(
        'headers' => array(
            'User-Agent' => 'WordPress/' . get_bloginfo('version'),
        ),
    ));

    if (is_wp_error($response)) {
        return 'Could not fetch changelog.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data) || !is_array($data)) {
        return 'No release data found.';
    }

    // Parse the latest releases
//     $changelog = '<h2>Changelog</h2>';
    foreach ($data as $release) {
        $changelog .= '<h3>' . esc_html($release['tag_name']) . '</h3>';
        $changelog .= '<p><strong>Release Date:</strong> ' . esc_html(date('F j, Y', strtotime($release['published_at']))) . '</p>';
        $changelog .= '<div>' . wp_kses_post(wpautop($release['body'])) . '</div>';
    }

    return $changelog;
}


// Add version control and update check
// Admin initialization hook
add_action('admin_init', 'mls_plugin_init');

function mls_plugin_init() {
    if (mls_plugin_is_license_valid()) {
        add_filter('pre_set_site_transient_update_plugins', 'mls_plugin_check_for_update');
    }else{ delete_site_transient('update_plugins'); }
}

function mls_plugin_check_for_update($transient) {
    if (!is_object($transient)) {
        return $transient;
    }
	$plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/mls-plugin/mls-plugin.php');
    $current_version = $plugin_data['Version'];
    $api_url = 'https://crm.clarkdigital.es/wp-json/updates/v1/updates';
    $plugin_slug = 'mls-plugin/mls-plugin.php'; // Correct plugin slug
    $domain = get_site_url();
    $parsed_url = wp_parse_url($domain);
    $domain = isset($parsed_url['host']) ? $parsed_url['host'] : $domain;
    $license_key = get_option('mls_plugin_license_key');

    // Send the request to the update server
    $response = wp_remote_post($api_url, array(
        'method'    => 'POST',
        'body'      => json_encode(array(
            'plugin_slug'    => $plugin_slug,
            'current_version'=> $current_version,
            'license_key'    => $license_key,
            'domain'         => $domain,
        )),
        'timeout'   => 15,
        'headers'   => array('Content-Type' => 'application/json'),
    ));

    if (is_wp_error($response)) {
        return $transient;
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    if (isset($data['new_version']) && version_compare($current_version, $data['new_version'], '<')) {
        $plugin_data = array(
            'slug'        => $plugin_slug,
            'new_version' => $data['new_version'],
            'url'         => $data['changelog_url'],
            'package'     => $data['download_url'], // Correct download URL
			'changelog'     => $data['changelog_content'],
        );
        $transient->response[$plugin_slug] = (object) $plugin_data;
    }

    return $transient;
}

// Plugin Update extract
function mls_plugin_update_information($false, $action, $arg) {
    if (isset($arg->slug) && $arg->slug === 'mls-plugin/mls-plugin.php') {
		
        $remote = wp_remote_get('https://crm.clarkdigital.es/wp-json/updates/v1/updates'); // Same API endpoint used above

        if (!is_wp_error($remote) && isset($remote['body'])) {
            $remote_body = json_decode($remote['body']);
			$tested_up_to = "6.7.1";
			$requires_php = "7.4";
			$author = 'Clark Digital';
			$author_profile = 'https://clarkdigital.es/resales-online-plugin/';
			$changelog_content = mls_plugin_fetch_changelog();
			
			global $description;
			global $installation_guide;
			global $faq_content;
			
            return (object) array(
                'slug' => $remote_body->slug,
                'new_version' => $remote_body->new_version,
				'package'      => $remote_body->download_url,
                'tested' => $tested_up_to,
				'requires_php' => $requires_php,
				'author' => $author,
				'author_profile' => $author_profile,
                'sections' => array(
                    
					'description' => wp_kses_post($description),
                    'installation' => wp_kses_post($installation_guide),
					'changelog' => wp_kses_post($changelog_content),
                    'faq'         => wp_kses_post($faq_content),
                ),
            );
        }
    }

    return $false;
}
add_filter('plugins_api', 'mls_plugin_update_information', 10, 3);

// Call database update on plugin update
add_action('upgrader_process_complete', 'mls_plugin_update_check', 10, 2);

function mls_plugin_update_check($upgrader_object, $options) {
    if ($options['action'] === 'update' && $options['type'] === 'plugin') {
        // Check if our plugin is being updated
        if (in_array(plugin_basename(__FILE__), $options['plugins'])) {
            $current_version = get_option('mls_plugin_version');

            // Get the updated plugin version
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/mls-plugin/mls-plugin.php');
            $new_version = $plugin_data['Version'];

            // Run the update if version has changed
            if (version_compare($current_version, $new_version, '<')) {
                mls_plugin_create_lead_form_table(); // Update the table with new columns
                update_option('mls_plugin_version', $new_version); // Save the new version
            }
        }
    }
}

function mls_plugin_breadcrumb() {
    // Get current page title and URL
    $current_page = get_the_title();
    $current_page_url = get_permalink();

    // Get the referring URL (previous page) and check if it's not the same as the current page
    if (isset($_SERVER['HTTP_REFERER']) && !is_same_page($_SERVER['HTTP_REFERER'], $current_page_url)) {
        $previous_page_url = $_SERVER['HTTP_REFERER'];
        // Get the title of the previous page without site name
        $previous_page_title = get_previous_page_title($previous_page_url);
    } else {
        // If no referer or invalid referer, set a default value
        $previous_page_url = home_url(); // Redirect to home if no valid referer found
        $previous_page_title = "Home";  // Or any default fallback
    }

    // Output breadcrumb structure
    echo '<div class="mls-plugin-breadcrumb">';
    if ($previous_page_title) {
        echo '<a href="' . esc_url($previous_page_url) . '">' . esc_html($previous_page_title) . '</a> <span class="bc-arw"></span> ';
    }
    echo esc_html($current_page);
    echo '</div>';
}

// Helper function to get the previous page title and remove the site name
function get_previous_page_title($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }
    $html = wp_remote_retrieve_body($response);
    preg_match('/<title>([^<]*)<\/title>/', $html, $matches);
    $page_title = $matches[1] ?? false;

    // Get the site name
    $site_name = get_bloginfo('name');

    // Remove the site name from the title if it exists
    if ($page_title && strpos($page_title, $site_name) !== false) {
        $page_title = trim(str_replace($site_name, '', $page_title));
        $page_title = trim(str_replace('-', '', $page_title)); // Remove any '-' before or after site name
    }

    return $page_title;
}


// Helper function to check if the referer is the same as the current page
function is_same_page($referrer_url, $current_url) {
    $referrer_url_parts = parse_url($referrer_url);
    $current_url_parts = parse_url($current_url);

    // Check if host and path are the same (ignoring query parameters)
    return ($referrer_url_parts['host'] === $current_url_parts['host'] && $referrer_url_parts['path'] === $current_url_parts['path']);
}

// Include the settings page file.
include(plugin_dir_path(__FILE__) . 'includes/mls-plugin-settings.php');

// Include the search functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls-plugin-search.php');
// Include the fetch properties functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls-plugin-fetch-properties.php');
if (mls_plugin_is_license_valid()) {
	// Include the lead form functionality file.
	include(plugin_dir_path(__FILE__) . 'includes/mls_plugin_lead_form.php');
}
// Include the documentation functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls_plugin_documentation.php');
// Include the license functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls_plugin_license.php');
// Include the languages functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls_plugin_languages.php');
// Include the view details in update notice functionality file.
include(plugin_dir_path(__FILE__) . 'includes/mls_plugin_updatedetail.php');

// Include the display properties functionality file.
include(plugin_dir_path(__FILE__) . 'public/mls-plugin-propertieslist.php');
// Include the display properties functionality file.
include(plugin_dir_path(__FILE__) . 'public/mls-plugin-propertiesdetail.php');
// Include the display properties map functionality file.
include(plugin_dir_path(__FILE__) . 'public/mls-plugin-map.php');


// Add body custom class for MLS
function mls_add_language_class_to_body($classes) {
    // Get the saved language option
    $mls_prop_language = get_option('mls_plugin_prop_language', '1'); // Default to '1' (English)
    // Get the saved dark option
	$dark_light_hide = get_option('mls_plugin_style_darklighthide');
    
    // If the option is set, add the class
    if ($mls_prop_language) { $classes[] = 'mls-lang-' . $mls_prop_language; }
	if ($dark_light_hide) { $classes[] = 'mls-dark-theme'; }
   
    
    
    return $classes;
}
add_filter('body_class', 'mls_add_language_class_to_body');


// Plugin activation hook
function mls_plugin_activate() {
    $created_pages = create_mls_pages();
    // Save page IDs in options for future reference
    update_option('mls_plugin_page_ids', $created_pages);
    // Flush rewrite rules to ensure our new pages are accessible
    flush_rewrite_rules();
	
    mls_plugin_create_lead_form_table();
	if (mls_plugin_is_license_valid()) {
        mls_plugin_check_for_update('');
    }
}

register_activation_hook(__FILE__, 'mls_plugin_activate');

?>