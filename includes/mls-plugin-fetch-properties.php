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
    $response = wp_remote_get($baseurl);

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
    $response = wp_remote_get($baseurl);

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

/***** Refresh Location cache through ajax call - To avoid making frequent API calls, we use WordPress transients to cache the locations temporarily:****/
add_action('wp_ajax_mls_refresh_locations', 'mls_plugin_refresh_locations');
function mls_plugin_refresh_locations() {
    
	// List of all language IDs
    $language_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14];
	
    // Clear the transient
    $location_deleted = delete_transient('mls_plugin_locations');
$currency_deleted = delete_transient('mls_plugin_currency');
$connectionsts_deleted = delete_transient('mls_plugin_connectionsts');

	// Track deletion status for language-based transients
    $propertytype_multilang_deleted = false;
    $searchfeatures_multilang_deleted = false;

    foreach ($language_ids as $lang_id) {
        if (delete_transient('mls_plugin_propertytype_multilang_' . $lang_id)) {
            $propertytype_multilang_deleted = true;
        }
        if (delete_transient('mls_plugin_searchfeatures_multilang_' . $lang_id)) {
            $searchfeatures_multilang_deleted = true;
        }
    }

if ($location_deleted || $currency_deleted || $connectionsts_deleted || $propertytype_multilang_deleted || $searchfeatures_multilang_deleted) {
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
        $error_messages = [];
    if (!$location_deleted) $error_messages[] = 'Locations';
    if (!$currency_deleted) $error_messages[] = 'Currency';
    if (!$connectionsts_deleted) $error_messages[] = 'Connection Status';
	if (!$propertytype_multilang_deleted) $error_messages[] = 'Property Types (All Languages)';
    if (!$searchfeatures_multilang_deleted) $error_messages[] = 'Search Features (All Languages)';

	
    if (defined('DOING_AJAX') && DOING_AJAX) {
    wp_send_json_error('Failed to sync data for: ' . implode(', ', $error_messages));
		 } else {
            return false; 
        }
    }
}


// Callback for the settings section.
function mls_plugin_property_type_filter_callback() {
    // Fetch the stored property types if any.
    $stored_types = get_option('mls_plugin_property_types');
//     print_r($stored_types);
    // Get the API key, client ID, and other required options.
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	 $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
	$mls_plugin_prop_language = get_option('mls_plugin_prop_language');

    // Define the API endpoint.
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesTypes'];

    // Construct the API request URL with necessary parameters.
    $url = add_query_arg(array(
		'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_Lang' => $mls_plugin_prop_language,
    ), $endpoint);

// 	echo $url; 
	
    // Fetch property types from the API.
    $response = wp_remote_get($url);

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
    // Check if the property types are available.
    if (isset($property_types['PropertyTypes']) && is_array($property_types['PropertyTypes'])) {
		echo '<input type="hidden" name="mls_plugin_currency" value="" />';
        echo '<div class="mls-multiSelects"><select name="mls_plugin_property_types[]" id="mls_property_types" multiple class="sel-app">';
        
        foreach ($property_types['PropertyTypes']['PropertyType'] as $type) {
            $option_value = $type['OptionValue'];  // The value attribute
            $option_label = $type['Type'];
            $combined_option = [$option_label => $option_value]; // Store label => value.
	$json_encoded_option = json_encode($combined_option);
$selected = is_array($stored_types) && in_array($json_encoded_option, $stored_types) ? 'selected' : '';
echo '<option value="' . esc_attr($json_encoded_option) . '" ' . esc_attr($selected) . '>' . esc_html($option_label) . '</option>';
            $with_subtypes = false;

            if (isset($type['SubType']) && count($type['SubType']) > 0) {
                $with_subtypes = true;
            }

            if ($with_subtypes) { 
                foreach ($type['SubType'] as $subtype) {
                    $suboption_value = $subtype['OptionValue'];  // The value attribute
                    $suboption_label = $subtype['Type'];
                    $combined_suboption = [$suboption_label => $suboption_value]; // Store label => value.
$json_encoded_suboption = json_encode($combined_suboption);
$selected = is_array($stored_types) && in_array($json_encoded_suboption, $stored_types) ? 'selected' : '';
echo '<option value="' . esc_attr($json_encoded_suboption) . '" ' . esc_attr($selected) . '>' . esc_html($suboption_label) . '</option>';
                   
                }
            }
        }

        echo '</select></div>';
    } else {
        echo "No property types found.";
    }
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
		'P_Lang' => $language,
    ), $endpoint);

// 	echo $url; 
	
    // Fetch property types from the API.
    $response = wp_remote_get($url);

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

/*function mls_plugin_get_cached_propertytype_multilang($language) {
    // Try to get locations from transient
    $propertytype_multilang = get_transient('mls_plugin_propertytype_multilang');
	
    if ($propertytype_multilang === false) {
        // If transient does not exist, fetch from API
        $propertytype_multilang = mls_plugin_property_type_filter_callback_multilang($language);
        // Check if locations array is valid and not empty
        if (!empty($propertytype_multilang) && $propertytype_multilang['PropertyTypes']['PropertyType'] > 0) {
            // Cache the results for 12 hours only if data is valid
            set_transient('mls_plugin_propertytype_multilang', $propertytype_multilang, 12 * HOUR_IN_SECONDS);
        } else {
            // If data is empty, set a shorter cache time (e.g., 5 minutes) to retry soon
            set_transient('mls_plugin_propertytype_multilang', [], 5 * MINUTE_IN_SECONDS);
        }
    }

    return $propertytype_multilang;
}*/


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
    $response = wp_remote_get($baseurl);

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
function mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_price, $max_price, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment, $mls_search_features_search_type, $additional_features) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
    $mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
    $mls_plugin_filter_id_short_rentals = get_option('mls_plugin_filter_id_short_rentals');
    $mls_plugin_filter_id_long_rentals = get_option('mls_plugin_filter_id_long_rentals');
    $mls_plugin_filter_id_features = get_option('mls_plugin_filter_id_features');
	$type = implode(',', $type);
	$area = implode(',', $area);
	$beds = $beds ? $beds. 'x' : '';
	$baths = $baths ? $baths. 'x' : '';
	if (!get_option('mls_plugin_style_proplanghide')) {
        $language = get_option('mls_plugin_prop_language');
    }


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
		'P_ApiId' => $filter_id,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
        'P_Location' => $area ? urlencode($area) : '',
        'P_PropertyTypes' => $type,
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


    $baseurl = add_query_arg($query_args, $endpoint);

//     echo 'base url: ' . $baseurl . '<br>';


    // Make the API request.
    $response = wp_remote_get($baseurl);

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
    $response = wp_remote_get($baseurl);

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
    $response = wp_remote_get($baseurl);

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

function mls_plugin_cached_searchfeatures_multilang($mls_language) {
	
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

        // Loop through each feature in the category
        foreach ($category['Feature'] as $feature) {
            $optionLabel = $feature['Name'];
            $optionValue = $feature['ParamName'];
            $optionId = 'mls-af-checkbox-' . sanitize_title($optionValue);
			// Get selected labels for this category
        $selected_labels = (isset($session_filters['additional_features'][$categorySlug]) && $mls_search_performed) ? $session_filters['additional_features'][$categorySlug] : [];
        
            // Check if the option should be checked
            $checked = in_array($optionValue, $selected_labels) ? 'checked' : '';

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
                    <h3>Select Options:</h3>
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
?>