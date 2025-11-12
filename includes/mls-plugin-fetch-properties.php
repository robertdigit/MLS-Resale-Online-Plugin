<?php
// Function to fetch locations using the Resales Online API
function mls_plugin_fetch_locations() {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	$mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
    
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getLocations']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
    ), $endpoint);

    // Make the API request
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

	
    // Return the locations if available
    return $results;
}

function mls_plugin_debug_locations_shortcode( $atts = [] ) {
	$default_atts = [
		'filterid' => ''
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_debug_locations');
	$filterid = $atts['filterid'];
    // 1. Call the existing helper.
    $locations = mls_plugin_fetch_customfilterid_locations($filterid);

    // 2. If nothing came back, show a friendly notice.
    if ( empty( $locations ) ) {
        return '<pre>No locations returned (empty array).</pre>';
    }

    // 3. Pretty‑print it.
    ob_start();
    echo '<pre>';
    // Escaping JSON keeps things simple & safe in HTML.
    echo esc_html( json_encode( $locations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
    echo '</pre>';

    return ob_get_clean();
}
add_shortcode( 'mls_debug_locations', 'mls_plugin_debug_locations_shortcode' );

// Function to fetch locations for Custom Filter ID using the Resales Online API
function mls_plugin_fetch_customfilterid_locations($mls_atts_filterid) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
    
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getLocations']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $mls_atts_filterid,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
    ), $endpoint);

    // Make the API request
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

	
    // Return the locations if available
    return $results;
}

// Function to fetch currency using the Resales Online API.
function mls_plugin_fetch_currency() {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
    $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
	
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getProperties']; // Replace with actual API endpoint

    // Build the full URL with the passed parameters
    $query_args = array(
        'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
    );

    $baseurl = add_query_arg($query_args, $endpoint);

    // Make the API request.
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return "Error: " . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: Could not decode JSON response.";
    }
	
	if (is_array($results) && isset($results['Property']) && is_array($results['Property'])) {  
	$results = $results['Property'][0]['Currency'];  }
	
    return $results;
	
}

/*****Location cache - To avoid making frequent API calls, we use WordPress transients to cache the locations temporarily:****/

function mls_plugin_get_cached_locations() {
    // Try to get locations from transient
    $locations = get_transient('mls_plugin_locations');
	
    if ($locations === false) {
        // If transient does not exist, fetch from API
        $locations = mls_plugin_fetch_locations();
        // Check if locations array is valid and not empty
        if (!empty($locations) && $locations['QueryInfo']['LocationCount'] > 0) {
            // Cache the results for 12 hours only if data is valid
            set_transient('mls_plugin_locations', $locations, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient('mls_plugin_locations', [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $locations;
}

function mls_plugin_get_cached_customfilterid_locations($mls_atts_filterid) {
    // Create a unique transient key based on the language
    $transient_key = 'mls_plugin_customfilterid_locations_' . sanitize_key($mls_atts_filterid);
    
    // Try to get property types from the transient
    $locations = get_transient($transient_key);
    
    if ($locations === false) {
        // If transient does not exist, fetch from API
         $locations = mls_plugin_fetch_customfilterid_locations($mls_atts_filterid);
        
        // Check if property type array is valid and not empty
        if (!empty($locations) && $locations['QueryInfo']['LocationCount'] > 0) {
            // Cache the results for 12 hours only if data is valid
            set_transient($transient_key, $locations, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient($transient_key, [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $locations;
}

function mls_plugin_get_cached_currencies() {
	$currency = get_transient('mls_plugin_currency');
	
    if ($currency === false) {
        
		$currency = mls_plugin_fetch_currency();
		// Check if currency property array is valid and not empty
        if ( !empty($currency) ) {
            // Cache the results for 12 hours only if data is valid
            set_transient('mls_plugin_currency', $currency, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient('mls_plugin_currency', [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $currency;
}

function mls_plugin_get_cached_mls_connection() {
    // Try to get locations from transient
    $mls_connection = get_transient('mls_plugin_connectionsts');
	
    if ($mls_connection === false) {
        // If transient does not exist, fetch from API
        $mls_connection = check_mls_connection();
        // Check if locations array is valid and not empty
       
			if ( !empty($mls_connection) && $mls_connection['transaction']['status'] === 'success') {
            // Cache the results for 12 hours only if data is valid
            set_transient('mls_plugin_connectionsts', $mls_connection, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient('mls_plugin_connectionsts', $mls_connection, 5 * MINUTE_IN_SECONDS);
        }
    }

    return $mls_connection;
}

add_action('mls_plugin_check_connection_cron', 'mls_plugin_monitor_connection');

function mls_plugin_monitor_connection() {
    // Check if notification is enabled
    if (get_option('mls_plugin_adminnotificationerror') !== 'yes') {
        return;
    }

    $last_status = get_option('mls_plugin_last_connection_status', '');
    $connection  = check_mls_connection(); // Fetch live connection

    // If connection data missing or malformed
    if (empty($connection) || empty($connection['transaction']) || empty($connection['transaction']['status'])) {

        // Notify admin that connection check failed
        mls_plugin_send_status_notification(
            $connection,
            $last_status,
            'unreachable'
        );

        // Update to mark as unreachable if not already
        if ($last_status !== 'unreachable') {
            update_option('mls_plugin_last_connection_status', 'unreachable');
        }

        return;
    }

    // Connection seems valid, continue normally
    $current_status = $connection['transaction']['status'];

    if ($current_status !== $last_status) {
        try {
            mls_plugin_send_status_notification($connection, $last_status, $current_status);
        } catch (Throwable $e) {
            error_log('[Resales Online Sync Plugin] Email send failed: ' . $e->getMessage());
        }

        // Always update stored status
        update_option('mls_plugin_last_connection_status', $current_status);
    }
}

function mls_plugin_send_status_notification($connection, $last, $current) {
    $admin_email = get_option('admin_email');
    $subject = "Resales Online Sync Plugin Connection Status Changed: {$current}";

    // Start message
    $message  = "Hello,\n\n";
    $message .= "The connection status of your Resales Online Sync Plugin has changed.\n\n";
    $message .= "Previous Status: {$last}\n";
    $message .= "Current Status: {$current}\n\n";

    // Handle unreachable / unknown cases
    if ($current === 'unreachable' || empty($connection)) {
        $message .= "⚠️ The system could not validate the connection to the Resales Online API.\n";
        $message .= "This may indicate an API outage, authentication problem, or server connection issue.\n\n";
        $message .= "Please log in to your WordPress dashboard and check the connection status manually.\n";
    } else {
        if (!empty($connection['transaction']['datetime'])) {
            $message .= "Date/Time: " . $connection['transaction']['datetime'] . "\n";
        }

        if (!empty($connection['transaction']['incomingIp'])) {
            $message .= "Incoming IP: " . $connection['transaction']['incomingIp'] . "\n";
        }

        if (!empty($connection['transaction']['errordescription'])) {
            $message .= "\nError Description:\n";
            foreach ($connection['transaction']['errordescription'] as $code => $msg) {
                $message .= " - {$code}: {$msg}\n";
            }
        }
    }

    // Send and handle errors safely
    $sent = wp_mail($admin_email, $subject, $message);
    if (!$sent) {
        error_log('[Resales Online Sync Plugin] Failed to send connection status email to: ' . $admin_email);
    }

    return $sent;
}


/***** Refresh Location cache through ajax call - To avoid making frequent API calls, we use WordPress transients to cache the locations temporarily:****/
add_action('wp_ajax_mls_refresh_locations', 'mls_plugin_refresh_locations');
function mls_plugin_refresh_locations() {
	$custom_deleted = mls_delete_all_transients_with_prefix('mls_plugin_');
	if ($custom_deleted) {
		$current_time = current_time('mysql');
        update_option('mls_plugin_last_cache_refresh', $current_time);
		mls_plugin_get_cached_locations();
	if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_send_json_success('Cache cleared & Resale Online data synced successfully.');
        } else {
            return true; // Success for form submission
        }
//         wp_send_json_success('Cache cleared & Resale Online data synced successfully.');
    } else {
        

	
    if (defined('DOING_AJAX') && DOING_AJAX) {
    wp_send_json_error('Failed to clear cache & sync data.' );
		 } else {
            return false; 
        }
    }
}

function mls_delete_all_transients_with_prefix($prefix = 'mls_plugin_') {
    global $wpdb;

    // Prepare patterns for option_name in wp_options table
    $like_transient = '_transient_' . $prefix . '%';
    $like_timeout = '_transient_timeout_' . $prefix . '%';

    // Delete transients
    $deleted = false;

    // Get all transient names with prefix
    $transients = $wpdb->get_col($wpdb->prepare(
        "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
        $like_transient,
        $like_timeout
    ));

    // Loop and delete each matching option
    foreach ($transients as $option_name) {
        delete_option($option_name);
        $deleted = true;
    }

    return $deleted;
}


// Property types for multi-language.
function mls_plugin_property_type_filter_callback_multilang($language) {

    // Get the API key, client ID, and other required options.
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	 $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');

    // Define the API endpoint.
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesTypes'];

    // Construct the API request URL with necessary parameters.
    $url = add_query_arg(array(
		'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_Lang' => $language ? $language : '1',
    ), $endpoint);

// 	echo $url; 
	
    // Fetch property types from the API.
    $response = wp_remote_get($url, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        echo "Error: " . esc_html($response->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $property_types = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error: Could not decode JSON response.";
        return;
    }
     return $property_types;
}

// Property types for Custom Filter ID.
function mls_plugin_property_type_filter_callback_customfilterid($mls_atts_filterid, $language) {

    // Get the API key, client ID, and other required options.
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');

    // Define the API endpoint.
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesTypes'];

    // Construct the API request URL with necessary parameters.
    $url = add_query_arg(array(
		'P_ApiId' => $mls_atts_filterid,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_Lang' => $language ? $language : '1',
    ), $endpoint);

// 	echo $url; 
	
    // Fetch property types from the API.
    $response = wp_remote_get($url, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        echo "Error: " . esc_html($response->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $property_types = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error: Could not decode JSON response.";
        return;
    }
	if (isset($property_types['PropertyTypes']) && is_array($property_types['PropertyTypes'])) { return $property_types; }
	else{ $property_types = mls_plugin_property_type_filter_callback_multilang($language); return $property_types; }
}

function mls_plugin_get_cached_propertytype_multilang($language) {
    // Create a unique transient key based on the language
    $transient_key = 'mls_plugin_propertytype_multilang_' . sanitize_key($language);
    
    // Try to get property types from the transient
    $propertytype_multilang = get_transient($transient_key);
    
    if ($propertytype_multilang === false) {
        // If transient does not exist, fetch from API
        $propertytype_multilang = mls_plugin_property_type_filter_callback_multilang($language);
        
        // Check if property type array is valid and not empty
        if (!empty($propertytype_multilang) && !empty($propertytype_multilang['PropertyTypes']['PropertyType'])) {
            // Cache the results for 12 hours only if data is valid
            set_transient($transient_key, $propertytype_multilang, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient($transient_key, [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $propertytype_multilang;
}

function mls_plugin_get_cached_propertytype_customfilterid($mls_atts_filterid, $language) {
    // Create a unique transient key based on the language
    $transient_key = 'mls_plugin_propertytype_customfilterid_lang_' . sanitize_key($language) . '_filterid_' . sanitize_key($mls_atts_filterid);
    
    // Try to get property types from the transient
    $propertytype_multilang = get_transient($transient_key);
    
    if ($propertytype_multilang === false) {
        // If transient does not exist, fetch from API
        $propertytype_multilang = mls_plugin_property_type_filter_callback_customfilterid($mls_atts_filterid, $language);
        
        // Check if property type array is valid and not empty
        if (!empty($propertytype_multilang) && !empty($propertytype_multilang['PropertyTypes']['PropertyType'])) {
            // Cache the results for 12 hours only if data is valid
            set_transient($transient_key, $propertytype_multilang, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient($transient_key, [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $propertytype_multilang;
}


// Function to check connection status using the Resales Online API.
function check_mls_connection() {
	
 	$mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	 $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');

    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getProperties']; // Replace with actual API endpoint

    // Build the full URL with the passed parameters
    $query_args = array(
        'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
    );


    $baseurl = add_query_arg($query_args, $endpoint);

//     echo 'base url' . $baseurl . '<br>';
//     die('tst-end');

    // Make the API request.
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return "Error: " . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: Could not decode JSON response.";
    }

    return $results;
	
}

// Function to fetch properties using the Resales Online API.
function mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_price, $max_price, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment, $mls_search_features_search_type, $additional_features, $mls_atts_propertytypes, $mls_atts_locations, $mls_atts_filterid, $mls_atts_pmustfeatures) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
    $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
    $mls_plugin_filter_id_short_rentals = get_option('mls_plugin_filter_id_short_rentals');
    $mls_plugin_filter_id_long_rentals = get_option('mls_plugin_filter_id_long_rentals');
    $mls_plugin_filter_id_features = get_option('mls_plugin_filter_id_features');
	$type = !empty($mls_atts_propertytypes) ? $mls_atts_propertytypes : implode(',', $type);
	$area = !empty($mls_atts_locations) ? $mls_atts_locations : implode(',', $area);
	$beds = $beds ? $beds. 'x' : '';
	$baths = $baths ? $baths. 'x' : '';
	if (!get_option('mls_plugin_style_proplanghide')) {
        $language = get_option('mls_plugin_prop_language');
    }
// 	echo '<br> mls_atts_filterid' . $mls_atts_filterid . '<br>';
	
	if ($filter_type === 'short_rentals') {
        $filter_id = $mls_plugin_filter_id_short_rentals;
    } elseif ($filter_type === 'long_rentals') {
        $filter_id = $mls_plugin_filter_id_long_rentals;
    } elseif ($filter_type === 'new_development') {
        $filter_id = $mls_plugin_filter_id_sales;
		$newdevelopment ='only';
    } elseif ($filter_type === 'featured') {
        $filter_id = $mls_plugin_filter_id_features;
    } else {
        $filter_id = $mls_plugin_filter_id_sales; // Default to sales filter ID
    }
	
// 	Page Size based on the layout
	$mls_def_prop_layout = get_option('mls_def_prop_layout');
	if($mls_def_prop_layout == 'cols5'){ $pagesize = '15'; }
	elseif($mls_def_prop_layout == 'cols4'){ $pagesize = '12'; }
	elseif($mls_def_prop_layout == 'cols3'){ $pagesize = '9'; }
	elseif($mls_def_prop_layout == 'cols2'){ $pagesize = '6'; }
	else{ $pagesize = '9'; }
	
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getProperties']; // Replace with actual API endpoint

    // Build the full URL with the passed parameters
    $query_args = array(
		'P_ApiId' => !empty($mls_atts_filterid) ? $mls_atts_filterid : $filter_id,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
        'P_Location' => $area ? urlencode($area) : '',
        'P_PropertyTypes' => $type ? urlencode($type) : '',
        'P_Beds' => $beds ? urlencode($beds) : '',
        'P_Baths' => $baths ? urlencode($baths) : '',
        'P_Min' => $min_price ? urlencode($min_price) : '',
        'P_Max' => $max_price ? urlencode($max_price) : '',
        'P_RefId' => $keyword ? urlencode($keyword) : '',
		'P_Lang' => $language,
        'P_PageSize' => $pagesize,
        'P_SortType' => isset($p_sorttype) ? $p_sorttype : '1',
		'p_new_devs' => $newdevelopment,
        'P_PageNo' => $page,
    );

    if ($query_id) {
        $query_args['P_QueryId'] = $query_id;
    }

	// For Additional Features parameter
if (!empty($additional_features) && is_array($additional_features) && !empty(array_filter($additional_features)) ) {
    $query_args['P_MustHaveFeatures'] = $mls_search_features_search_type;
    foreach ($additional_features as $feature_key => $feature_values) {
        if (!empty($feature_values) && is_array($feature_values)) {
            foreach ($feature_values as $value) {
                if (!empty($value)) { // Ensure the value is not empty
                    $query_args[$value] = '1';
                }
            }
        }
    }
}
elseif (!empty($mls_atts_pmustfeatures)) { 
    $query_args['P_MustHaveFeatures'] = '2'; // Default search type
    $preselected_features = explode(',', $mls_atts_pmustfeatures);
    
    foreach ($preselected_features as $feature_value) {
        $feature_value = trim($feature_value);
        if (!empty($feature_value)) {
            $query_args[$feature_value] = '1';
        }
    }
}

    $baseurl = add_query_arg($query_args, $endpoint);

//     echo 'base url: ' . $baseurl . '<br>';


    // Make the API request.
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return "Error: " . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: Could not decode JSON response.";
    }

    return $results;
	
}

// Function to fetch property detail page using the Resales Online API
function mls_plugin_fetch_ref($reference, $type, $language, $newdevelopment) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	$mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
	$mls_plugin_prop_language = get_option('mls_plugin_prop_language');
//     if (!get_option('mls_plugin_style_proplanghide')) {
//         $language = get_option('mls_plugin_prop_language');
//     }
// 	echo "lang: ". $language. "<br>";
	
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getProperty']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $type,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_RefId' => $reference,
		'P_Lang' => $language,
        'P_ShowGPSCoords' => 'TRUE',
		'P_VirtualTours' => '2',
		'P_show_dev_prices' => 'bottom',
		'P_shownewdevname' => 'TRUE',
		'p_new_devs' => $newdevelopment,
        // 'P_showdecree218' => 'YES',
    ), $endpoint);

    // echo 'base url' . $baseurl . '<br>';
    
    // Make the API request
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $results = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

	
    // Return the locations if available
    return $results;
}


// Function to fetch property detail page using the Resales Online API
function mls_plugin_fetch_searchfeatures($language) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	$mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
	
	if (!get_option('mls_plugin_style_proplanghide')) {
		$mls_language = get_option('mls_plugin_prop_language');
	}else{
		$mls_language = $language;
	} 
    
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesFeatures']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_Lang' => $mls_language,
    ), $endpoint);

//     echo 'base url' . $baseurl . '<br>';
    
    // Make the API request
    $response = wp_remote_get($baseurl, ['timeout' => '20','redirection' => 5]);

    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    // Return the API data
    return $data;

	
}

function mls_plugin_cached_searchfeatures_multilang($mls_language, $mls_atts_pmustfeatures) {
	
	if (!get_option('mls_plugin_style_proplanghide')) {
		$language = get_option('mls_plugin_prop_language');
	}else{
		$language = $mls_language;
	} 
	
    // Create a unique transient key based on the language
    $transient_key = 'mls_plugin_searchfeatures_multilang_' . sanitize_key($language);
    
    $searchfeatures_multilang = get_transient($transient_key);
    
    if ($searchfeatures_multilang === false) {
        // If transient does not exist, fetch from API
        $searchfeatures_multilang = mls_plugin_fetch_searchfeatures($language);
        
        if (!empty($searchfeatures_multilang) ) {
            // Cache the results for 12 hours only if data is valid
            set_transient($transient_key, $searchfeatures_multilang, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient($transient_key, [], 5 * MINUTE_IN_SECONDS);
        }
    }

    // Parse pmustfeatures and create a mapping for pre-selection
    $preselected_features = [];
    if (!empty($mls_atts_pmustfeatures)) {
        $features_array = explode(',', $mls_atts_pmustfeatures);
        foreach ($features_array as $feature) {
            $feature = trim($feature);
            if (!empty($feature)) {
                $preselected_features[] = $feature;
            }
        }
        
        // If values are present and no search performed yet, store them in session
        if (!isset($_SESSION['mls_search_filters'])) { 
            $_SESSION['mls_search_filters'] = []; 
        }
        
        if (!isset($_GET['mls_search_performed'])) {
            if (!empty($preselected_features)) { 
                // Parse preselected features and organize by category
                $organized_features = [];
                
                foreach ($preselected_features as $feature_value) {
                    // Find which category and feature name this value belongs to
                    foreach ($searchfeatures_multilang['FeaturesData']['Category'] as $index => $category) {
                        foreach ($category['Feature'] as $feature) {
                            if ($feature['ParamName'] === $feature_value) {
                                $feature_categories = [
                                    'Setting', 'Orientation', 'Condition', 'Pool', 'Climate', 'Views', 'Features', 
                                    'Furniture', 'Kitchen', 'Garden', 'Security', 'Parking', 'Utilities', 
                                    'Category', 'Rentals', 'Plots'
                                ];
                                $categorySlug = $feature_categories[$index];
                                $organized_features[$categorySlug][$feature['Name']] = $feature_value;
                                break 2; // Break both loops once found
                            }
                        }
                    }
                }
                
                $_SESSION['mls_search_filters']['additional_features'] = $organized_features;
            } else {
				echo 'no_preselected_features' ;
                $_SESSION['mls_search_filters']['additional_features'] = '';
            }
        }
    }

    // Retrieve filter values from session
    $session_filters = isset($_SESSION['mls_search_filters']) ? $_SESSION['mls_search_filters'] : [];
    $mls_search_performed = isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1';

    // Start building the accordion HTML
    $html = '<div id="mls-af-accord1" class="mls-af-accordian">';

    $feature_categories = [
        'Setting', 'Orientation', 'Condition', 'Pool', 'Climate', 'Views', 'Features', 
        'Furniture', 'Kitchen', 'Garden', 'Security', 'Parking', 'Utilities', 
        'Category', 'Rentals', 'Plots'
    ];
	
    // Loop through each category
    foreach ($searchfeatures_multilang['FeaturesData']['Category'] as $index => $category) {
        $categoryName = $category['@attributes']['Name'];
        $categorySlug = $feature_categories[$index];
        $options = '';
        $selected_labels_html = '';

        // Get selected labels for this category
        $selected_labels = [];
        if (isset($session_filters['additional_features'][$categorySlug]) && $mls_search_performed) {
            $selected_labels = $session_filters['additional_features'][$categorySlug];
        } elseif (!empty($preselected_features) && isset($session_filters['additional_features'][$categorySlug])) {
            // Use preselected features if no search performed yet
            $selected_labels = $session_filters['additional_features'][$categorySlug];
        }

        // Loop through each feature in the category
        foreach ($category['Feature'] as $feature) {
            $optionLabel = $feature['Name'];
            $optionValue = $feature['ParamName'];
            $optionId = 'mls-af-checkbox-' . sanitize_title($optionValue);
            
            // Check if the option should be checked
            $checked = '';
            if (is_array($selected_labels)) {
                $checked = in_array($optionValue, $selected_labels) ? 'checked' : '';
            }
            
            // Also check if this feature is in preselected features (for direct attribute matching)
            if (!$checked && in_array($optionValue, $preselected_features)) {
                $checked = 'checked';
            }

            // If checked, add to selected labels HTML
            if ($checked) {
                $selected_labels_html .= "
                <span class='mls-af-label-badge' data-value='$optionValue'>
                    $optionLabel
                    <span class='mls-af-close-btn'>&times;</span>
                </span>";
            }

            $options .= "
            <li>
                <input class='mls-af-checkbox' id='$optionId' type='checkbox' name='mls_search_feature_{$categorySlug}[]' value='$optionValue' $checked>
                <label for='$optionId'>$optionLabel</label>
                <input type='hidden' name='mls_search_features_labels[$optionValue]' value='$optionLabel'>
            </li>";
        }

        // Create the accordion section for the category
        $html .= "
        <div>
            <h2 class='mls-af-accodian-title'>$categoryName</h2>
            <section class='mls-af-accodian-cnts' style='display: none;'>
                <div class='mls-af-sel-wrap'>
                    <h3>" . mls_plugin_translate('general', 'selectoption') . ":</h3>
                    <div class='mls-af-selected-labels'>$selected_labels_html</div>
                    <ul class='mls-af-wrap-list'>
                        $options
                    </ul>
                </div>
            </section>
        </div>";
    }

    $html .= '</div>'; 
	
    return $html;
}

// Function to fetch google fonts using the google fonts API

function mls_plugin_google_fonts_dropdown() {
    $api_key = 'AIzaSyCDBN66lhXaKAOgQ1xYczU7jI7rAAgDUmY'; // Replace with your API Key
    $fonts = mls_plugin_fetch_google_fonts($api_key);
    $selected_font = get_option('mls_plugin_google_font', '');

    if (is_wp_error($fonts)) {
        echo '<p>Unable to fetch fonts. Please check your API key.</p>';
        return;
    }

    echo '<select name="mls_plugin_google_font">';
	echo "<option value=''>Select the font</option>";
    foreach ($fonts as $font) {
        $font_family = $font['family'];
        $selected = ($selected_font === $font_family) ? 'selected' : '';
        echo "<option value='$font_family' $selected>$font_family</option>";
    }
    echo '</select>';
}

function mls_plugin_fetch_google_fonts($api_key) {
    $response = wp_remote_get("https://www.googleapis.com/webfonts/v1/webfonts?key=$api_key");
    if (is_wp_error($response)) {
        return $response;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    return $data['items'] ?? [];
}

function mls_plugin_weblink_structure($property_title, $property_ref, $property_filter_type, $language){
$prpdtselected_page_id = get_option('mls_plugin_property_detail_page_id', '');
$prpdetailpage_id = $prpdtselected_page_id ? $prpdtselected_page_id : 7865;
$prpdetailpage = get_post($prpdetailpage_id);
if (get_option('mls_plugin_style_proplanghide')) {
$prpdetailpage_slug = get_option('mls_plugin_property_detail_page_slug');
}else{
$prpdetailpage_slug = $prpdetailpage ? $prpdetailpage->post_name : '';
}

$weblinkstructure = get_option('mls_plugin_weblink_structure', 'weblink_advanced');
if ($weblinkstructure == 'weblink_advanced') {
$view_more_url = home_url("{$prpdetailpage_slug}/{$property_title}/{$property_ref}/?type={$property_filter_type}&lang={$language}");
}else{	
	$view_more_url = home_url("{$prpdetailpage_slug}/?id={$property_ref}&lang={$language}&type={$property_filter_type}");	
}
	return $view_more_url;
}

/*function mls_render_location_group_manager() {
    $locations = mls_plugin_fetch_locations();
    if ($locations) {
		$locations_array = [];
		if (is_array($locations['LocationData']['ProvinceArea'])) {
        foreach ($locations['LocationData']['ProvinceArea'] as $province) {
          if (isset($province['Location']) && is_array($province['Location'])) {
            $locations_array = array_merge($locations_array,  $province['Location']);
          } else {
            if (isset($province['Locations'])) {
              if (is_array($province['Locations']['Location'])) {
                $locations_array = array_merge($locations_array,  $province['Locations']['Location']);
              } else { 
                $one_location[] = $province['Locations']['Location'];
                $locations_array = array_merge($locations_array,  $one_location);
              }
            } else if (isset($province['Location'])) {
              $one_location[] = $province['Location'];
              $locations_array = array_merge($locations_array,  $one_location);
            }
          }
        }
      }else {
        $locations_array =  $locations['LocationData']['ProvinceArea']['Locations']['Location'];
      }
		 $locations_array = $locations_array; }
	else{
        echo '<p>No locations returned by mls_plugin_fetch_locations().</p>';
        return;
    }

    $groups = get_option('mls_location_groups', []);
    if (empty($groups)) {
        $groups[] = [ 'parent' => '', 'children' => [] ];
    }
    ?>
<div class="mls-location-groups-table">
    <table class="widefat mls-table-theme" id="mls-lg-table">
        <thead>
        <tr>
            <th style="width:30%">Parent (single)</th>
            <th style="width:60%">Children (multi)</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($groups as $i => $row) : ?>
            <tr class="mls-lg-row">
                <td>
                    <select class="mls_location_parent" name="mls_location_groups[<?php echo $i; ?>][parent]" style="width:100%">
                        <option value="">— Select —</option>
                        <?php foreach ($locations_array as $loc) : ?>
                            <option value="<?php echo esc_attr($loc); ?>" <?php selected($row['parent'], $loc); ?>>
                                <?php echo esc_html($loc); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select multiple class="mls_location_childgroups" name="mls_location_groups[<?php echo $i; ?>][children][]" style="width:100%;min-height:120px">
                        <?php foreach ($locations_array as $loc) : ?>
                            <option value="<?php echo esc_attr($loc); ?>"
                                <?php echo in_array($loc, $row['children'], true) ? 'selected' : ''; ?>>
                                <?php echo esc_html($loc); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td style="text-align: center;"><span class="mls-lg-remove dashicons dashicons-no-alt" title="Remove row"></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <p><button type="button" class="button" id="mls-lg-add">+ Add Locations</button><button type="button" class="button" id="mls-lg-add-predefined">Add Default Malaga Locations</button></p>
    <?php
}*/

function mls_render_location_group_manager() {
    $locations = mls_plugin_fetch_locations();

    if ($locations) {
        $locations_array = [];
        if (is_array($locations['LocationData']['ProvinceArea'])) {
            foreach ($locations['LocationData']['ProvinceArea'] as $province) {
                if (isset($province['Location']) && is_array($province['Location'])) {
                    $locations_array = array_merge($locations_array, $province['Location']);
                } else {
                    if (isset($province['Locations'])) {
                        if (is_array($province['Locations']['Location'])) {
                            $locations_array = array_merge($locations_array, $province['Locations']['Location']);
                        } else {
                            $one_location[] = $province['Locations']['Location'];
                            $locations_array = array_merge($locations_array, $one_location);
                        }
                    } else if (isset($province['Location'])) {
                        $one_location[] = $province['Location'];
                        $locations_array = array_merge($locations_array, $one_location);
                    }
                }
            }
        } else {
            $locations_array = $locations['LocationData']['ProvinceArea']['Locations']['Location'];
        }
    } else {
        echo '<p>No locations returned by mls_plugin_fetch_locations().</p>';
        return;
    }

    $groups = get_option('mls_location_groups', []);
    if (empty($groups)) {
        // default one empty row
        $groups[] = [
            'parent' => '',
            'children' => [],
            'parent_type' => 'select'
        ];
    }
    ?>
    <style>
      /* small layout helpers */
      .mls-parent-toggle .mls-custom-wrap { margin-top:6px; display:block; }
      .mls-parent-toggle .mls-custom-input { display:block; margin-top:6px; width:100%; box-sizing:border-box; }
      .mls-parent-toggle .mls-select-wrap { display:block; margin-top:6px; }
      .mls-parent-toggle .mls-hidden { display:none; }
      /* adjust as needed in your admin css */
    </style>

    <div class="mls-location-groups-table">
        <table class="widefat easySel-style form-table mls-table-theme" id="mls-lg-table">
            <thead>
            <tr>
                <th style="width:30%">Parent (single)</th>
                <th style="width:60%">Children (multi)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($groups as $i => $row) :
                $parent_type = isset($row['parent_type']) ? $row['parent_type'] : 'select';
                $parent_value = isset($row['parent']) ? $row['parent'] : '';
            ?>
                <tr class="mls-lg-row">
                    <td>
                        <div class="mls-parent-toggle">
                            <!-- checkbox to switch to custom -->
                            <label style="display:block;">
                                <input type="checkbox"
                                       class="mls_parent_toggle_checkbox"
                                       data-index="<?php echo $i; ?>"
                                       <?php echo ($parent_type === 'custom') ? 'checked' : ''; ?> />
                                Use custom parent
                            </label>

                            <!-- select (existing) -->
                            <div class="mls-select-wrap" <?php echo ($parent_type === 'custom') ? 'style="display:none;"' : ''; ?> >
                                <select class="mls_location_parent" 
                                        name="mls_location_groups[<?php echo $i; ?>][parent_select]"
                                        style="width:100%;">
                                    <option value="">— Select —</option>
                                    <?php foreach ($locations_array as $loc) : ?>
                                        <option value="<?php echo esc_attr($loc); ?>"
                                            <?php selected(($parent_type === 'select' ? $parent_value : ''), $loc); ?>>
                                            <?php echo esc_html($loc); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- custom input -->
                            <div class="mls-custom-wrap" <?php echo ($parent_type === 'custom') ? '' : 'style="display:none;"'; ?> >
                                <input type="text"
                                       class="mls_location_parent_custom mls-custom-input"
                                       name="mls_location_groups[<?php echo $i; ?>][parent_custom]"
                                       placeholder="Enter custom parent name"
                                       value="<?php echo ($parent_type === 'custom') ? esc_attr($parent_value) : ''; ?>" />
                            </div>

                            <!-- hidden canonical parent (always posted) -->
                            <input type="hidden"
                                   class="mls_location_parent_hidden"
                                   name="mls_location_groups[<?php echo $i; ?>][parent]"
                                   value="<?php echo esc_attr($parent_value); ?>" />

                            <!-- hidden parent_type (always posted) -->
                            <input type="hidden"
                                   class="mls_parent_type_hidden"
                                   name="mls_location_groups[<?php echo $i; ?>][parent_type]"
                                   value="<?php echo esc_attr($parent_type); ?>" />
                        </div>
                    </td>

                    <td style="vertical-align: bottom;">
						<div class="mls-multiSelects">
                        <select multiple class="mls-multiselect mls_location_childgroups sel-app" name="mls_location_groups[<?php echo $i; ?>][children][]" style="width:100%;min-height:120px">
							<option value="" disabled>— Select Children —</option>
                            <?php foreach ($locations_array as $loc) : ?>
                                <option value="<?php echo esc_attr($loc); ?>"
                                    <?php echo in_array($loc, $row['children'], true) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($loc); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
						</div>
                    </td>

                    <td style="text-align: center;">
                        <span class="mls-lg-remove dashicons dashicons-no-alt" title="Remove row"></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div>
        <button type="button" class="button" id="mls-lg-add">+ Add Locations</button>
        <button type="button" class="button" id="mls-lg-add-predefined">Add Default Malaga Locations</button>
        <div class="mls-admin-info-wrap mt-3">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>The predefined location import feature is only intended for the Málaga province. If your account is set to a different province, please do not use this feature, as many of the predefined locations will not be available in your Resales Online data. Using it outside Málaga may cause the import to fail or appear buggy.</p>
			</div>                            
		</div>
    </div>
    <?php
}


