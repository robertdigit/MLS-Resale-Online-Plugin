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

/***** Refresh Location cache through ajax call - To avoid making frequent API calls, we use WordPress transients to cache the locations temporarily:****/
add_action('wp_ajax_mls_refresh_locations', 'mls_plugin_refresh_locations');
function mls_plugin_refresh_locations() {
    
    // Clear the transient
    if (delete_transient('mls_plugin_locations')) {
		$current_time = current_time('mysql');
        update_option('mls_plugin_last_cache_refresh', $current_time);
		mls_plugin_get_cached_locations();
        wp_send_json_success('Location cache refreshed successfully.');
    } else {
        wp_send_json_error('Failed to refresh location cache.');
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

    // Define the API endpoint.
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesTypes'];

    // Construct the API request URL with necessary parameters.
    $url = add_query_arg(array(
		'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
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
        echo '<select name="mls_plugin_property_types[]" id="mls_property_types" multiple class="sel-app">';
        
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

        echo '</select>';
    } else {
        echo "No property types found.";
    }
}

// Function to fetch properties using the Resales Online API.
function mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_price, $max_price, $filter_type, $p_sorttype, $page, $query_id) {
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

// 	echo $filter_type. "<br>";
// 	echo $keyword. "<br>";

	if ($filter_type === 'short_rentals') {
        $filter_id = $mls_plugin_filter_id_short_rentals;
    } elseif ($filter_type === 'long_rentals') {
        $filter_id = $mls_plugin_filter_id_long_rentals;
    } elseif ($filter_type === 'featured') {
        $filter_id = $mls_plugin_filter_id_features;
    } else {
        $filter_id = $mls_plugin_filter_id_sales; // Default to sales filter ID
    }
	
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
        'P_PageSize' => '9',
        'P_SortType' => $p_sorttype ? urlencode($p_sorttype) : '0',
        // 'p_images' => '4',
        // 'P_IncludeRented' => 'TRUE',
        'P_PageNo' => $page,
    );

    if ($query_id) {
//         echo 'qus: ' . $query_id . "<br>";
        $query_args['P_QueryId'] = $query_id;
    }

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

// Function to fetch property detail page using the Resales Online API
function mls_plugin_fetch_ref($reference, $type) {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	$mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
    
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getProperty']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $type,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
		'P_RefId' => $reference,
        'P_ShowGPSCoords' => 'TRUE',
		'P_VirtualTours' => '2',
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
function mls_plugin_fetch_searchfeatures() {
    $mls_plugin_api_key = get_option('mls_plugin_api_key');
    $mls_plugin_client_id = get_option('mls_plugin_client_id');
	$mls_plugin_filter_id_sales = get_option('mls_plugin_filter_id_sales');
    
    $endpoint = RESALES_ONLINE_BASE_API_URL_V6 . RESALES_ONLINE_API_ENDPOINTS_V6['getPropertiesFeatures']; // Replace with the correct API endpoint for locations

    // Build the full URL with the necessary parameters
    $baseurl = add_query_arg(array(
        'P_ApiId' => $mls_plugin_filter_id_sales,
        'p1' => $mls_plugin_client_id,
        'p2' => $mls_plugin_api_key,
    ), $endpoint);

//     echo 'base url' . $baseurl . '<br>';
    
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

?>
