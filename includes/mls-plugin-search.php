<?php

// function mls_plugin_start_session() {
//     if (!session_id()) {
//         session_start();
//     }
// }
// add_action('init', 'mls_plugin_start_session', 1);
if (mls_plugin_check_license_status()) {

add_shortcode('mls_property_search', 'mls_plugin_property_search_form');
add_shortcode('mls_property_searchformcode', 'mls_plugin_property_searchformcode');
add_shortcode('mls_search_results', 'mls_plugin_display_search_results');
add_shortcode('mls_property_list', 'mls_property_list_shortcode');
add_shortcode('mls_banner_searchform', 'mls_plugin_banner_search_form');
add_shortcode('mls_property_byrefs', 'mls_property_byrefs_shortcode');
}
// Function to display the search form.


// Function to display the search form in Search result & Propertylist shortcode.
function mls_plugin_property_searchformcode($atts = []) {
    // Define default attributes
    $default_atts = [
        'filtertype' => 'sales',
        'ownpageresult' => 'false',
        'searchtitle' => 'searchtitle' ,
        'maxthumbnail' => '',
		'bedsfilter' => 5,
        'bathsfilter' => 5,
        'min_pricefilter' => '0',
        'max_pricefilter' => '10000000',
        'includesorttype' => '1',
		'language' => '1',
        'newdevelopment' => 'include',
		'formbackgroundcolor' => '',
		'formbuttoncolor' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_searchformcode');
    $language = $atts['language'];
	
	// Update option temporarily
    update_option('mls_temp_language_code', $language);

	// Set the searchtitle after updating the option
if ($atts['searchtitle'] == 'searchtitle') {
    $atts['searchtitle'] = mls_plugin_translate('labels', 'searchtitle');
}

	$filter_type = $atts['filtertype'];
    $searchtitle = $atts['searchtitle'];
    $ownpageresult = $atts['ownpageresult'];
    $maxthumbnail = $atts['maxthumbnail'];
    $includesorttype = $atts['includesorttype'];
	
    $newdevelopment = $atts['newdevelopment'];
	$formbackgroundcolor = $atts['formbackgroundcolor'];
	$formbuttoncolor = $atts['formbuttoncolor'];
    $min_price = $atts['min_pricefilter'];
	$max_price = $atts['max_pricefilter'];
    $min_max_price = $min_price . ' to ' . $max_price;
    // $p_sorttype = $atts['p_sorttype'];
    $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $atts['p_sorttype'];
    // Fetch the property types selected in the settings & Multi-lang
    $stored_types_with_labels = get_option('mls_plugin_property_types', array());
	$property_types = mls_plugin_get_cached_propertytype_multilang($language);
	// Update option temporarily
    update_option('mls_temp_language_code', $language);
    
   // Fetch the currencies from mls-plugin-fetch-properties
$currencies = mls_plugin_get_cached_currencies(); 
	
	
   // Fetch the Location from mls-plugin-fetch-properties
$locations = mls_plugin_get_cached_locations(); 
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
        //  else{ echo 'no location'; }

    // Retrieve filter values from session
    $session_filters = isset($_SESSION['mls_search_filters']) ? $_SESSION['mls_search_filters'] : [];
    $mls_search_performed = isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1';

    // Search form HTML
    ob_start();
    ?>

<div class="mls-parent-search-wrapper proplst-result-search">
    <div class="mls-container">
    <div class="filter-form-wrapper mls-heading mls-main-content" style="background-color: <?php echo esc_attr($formbackgroundcolor); ?>;border-color: <?php echo esc_attr($formbackgroundcolor); ?>;">
    <h4><?php echo esc_attr($searchtitle); ?></h4>

    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="search_form" class="mls-proplist-search-form mls-form">
    <input type="hidden" name="action" id="mlssearch_form" value="mls_search">
  	<input type="hidden" name="query_id" value="<?php echo isset($_GET['query_id']) ? esc_attr($_GET['query_id']) : ''; ?>">
    <input type="hidden" name="page" value="<?php echo isset($_GET['page_num']) ? esc_attr($_GET['page_num']) : '1'; ?>">
<!--     <input type="hidden" name="filter_type" value="<?php echo esc_attr($filter_type); ?>"> -->
    <input type="hidden" name="ownpageresult" value="<?php echo esc_attr($ownpageresult); ?>">
    <input type="hidden" name="maxthumbnail" value="<?php echo esc_attr($maxthumbnail); ?>">
    <input type="hidden" name="p_sorttype" id="search_form_sorttype" class="search_form_sorttype" value="<?php echo esc_attr($p_sorttype); ?>">
    <input type="hidden" name="includesorttype" value="<?php echo esc_attr($includesorttype); ?>">
    <input type="hidden" name="language" value="<?php echo esc_attr($language); ?>">
    <input type="hidden" name="newdevelopment" value="<?php echo esc_attr($newdevelopment); ?>">

<!--	sales, rental & new development tab in banner search form	 -->
<?php
// Fetch settings and shortcode attributes
$selected_tabs = get_option('mls_plugin_tabs_to_display', ['sales']);
$shortcode_filtertype = isset($atts['filtertype']) ? sanitize_text_field($atts['filtertype']) : null;

// Determine the active filter type
$active_filter = (($mls_search_performed && isset($session_filters['filter_type'])) 
    ? $session_filters['filter_type'] 
    : ($filter_type ?? 'sales'));


// If only one tab is selected
if (count($selected_tabs) === 1) {
    $single_tab_value = $selected_tabs[0];

    // If shortcode attribute exists, prioritize it
    $hidden_value = $shortcode_filtertype ?: $single_tab_value;
    ?>
    <input type="hidden" name="filter_type" value="<?php echo esc_attr($hidden_value); ?>">
    <?php
} else {
    // Show the tabs
    ?>
    <div class="mls-tab-container">
		<ul class="srh-tab-nav">
        <?php foreach ($selected_tabs as $tab): ?>
			<li>
            <input type="radio" id="tab_<?php echo esc_attr($tab); ?>" name="filter_type" value="<?php echo esc_attr($tab); ?>"
                   <?php echo ($active_filter === $tab) ? 'checked' : ''; ?>>
            <label class="tab" for="tab_<?php echo esc_attr($tab); ?>">
                <?php echo mls_plugin_translate('labels', $tab); ?>
            </label>
			</li>
        <?php endforeach; ?>
		</ul>
    </div>
    <?php
}
?>

		
    <div class="mls-form-group">
        <label for="mls_search_area"><?php echo mls_plugin_translate('labels','area'); ?></label>
        <select id="mls_search_area" name="mls_search_area[]" class="mls_area_sel" multiple>
            <?php
            if (!empty($locations_array)) {
                foreach ($locations_array as $location) {
                    // $selected = in_array($location, $session_filters['area'] ?? []) ? 'selected' : '';
                    $selected = (in_array($location, $session_filters['area'] ?? []) && $mls_search_performed) ? 'selected' : '';
                    echo '<option value="' . esc_attr($location) . '" ' . esc_attr($selected) . '>' . esc_html($location) . '</option>';
                    // echo '<option value="' . esc_attr($location) . '">' . esc_html($location) . '</option>';
                }
            } else {
                echo '<option value="">'. mls_plugin_translate("labels","no_area"). '</option>';
            }
            ?>
        </select>
        
        </div>
		<div class="mls-form-group">

        <label for="mls_search_type"><?php echo mls_plugin_translate('labels','property_type'); ?></label>
        
            <?php
	if (!get_option('mls_plugin_style_proplanghide')) { //property type selected in setting
		
	echo '<select id="mls_search_type" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (!empty($stored_types_with_labels)) {
                foreach ($stored_types_with_labels as $item) {
                    $decoded_item = json_decode($item, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_item)) {
                        foreach ($decoded_item as $label => $value) {
                            // echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
                            $selected = (in_array($value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
                            echo '<option value="' . esc_attr($value) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>Error decoding option: ' . esc_html($item) . '</option>';
                    }
                }
            } else {
                echo '<option value="">'. mls_plugin_translate("labels","no_property_type"). '</option>';
            }
	 echo '</select>';
	}
	else { //multi-lang property type directly from resale
	echo '<select id="mls_search_type" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (isset($property_types['PropertyTypes']) && is_array($property_types['PropertyTypes'])) {        
        foreach ($property_types['PropertyTypes']['PropertyType'] as $type) {
            $option_value = $type['OptionValue'];  // The value attribute
            $option_label = $type['Type'];
$selected = (in_array($option_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option value="' . esc_attr($option_value) . '" ' . esc_attr($selected) . '>' . esc_html($option_label) . '</option>';
            $with_subtypes = false;

            if (isset($type['SubType']) && count($type['SubType']) > 0) {
                $with_subtypes = true;
            }

            if ($with_subtypes) { 
                foreach ($type['SubType'] as $subtype) {
                    $suboption_value = $subtype['OptionValue'];  // The value attribute
                    $suboption_label = $subtype['Type'];
$selected = (in_array($suboption_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option value="' . esc_attr($suboption_value) . '" ' . esc_attr($selected) . '>' . esc_html($suboption_label) . '</option>';
                   
                }
            }
        }
    }else { echo mls_plugin_translate("labels","no_property_type"); } 
	 echo '</select>';
	}
            ?>
        

        </div><div class="mls-form-group">

        <label for="mls_search_keyword"><?php echo mls_plugin_translate('labels','reference_id'); ?></label>
        <!-- <input type="text" id="mls_search_keyword" value="<?php echo esc_attr($session_filters['keyword'] ?? ''); ?>" name="mls_search_keyword" /> -->
        <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','search_reference_id'); ?>" id="mls_search_keyword" 
        value="<?php echo esc_attr(($mls_search_performed && !empty($session_filters['keyword'])) ? $session_filters['keyword'] : ''); ?>" 
        name="mls_search_keyword" />

        </div><div class="mls-form-group">
        
        <label for="mls_search_beds"><?php echo mls_plugin_translate('labels','beds'); ?></label>
        <select id="mls_search_beds" name="mls_search_beds" class="sel-app">
            <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
			<?php echo wp_kses( generate_bed_bath_options($atts['bedsfilter'], '+', ($mls_search_performed && !empty($session_filters['beds'])) ? $session_filters['beds'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
        </select>
        
        </div><div class="mls-form-group">
        
        <label for="mls_search_baths"><?php echo mls_plugin_translate('labels','baths'); ?></label>
        <select id="mls_search_baths" name="mls_search_baths" class="sel-app">
            <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
            <?php echo wp_kses( generate_bed_bath_options($atts['bathsfilter'], '+', ($mls_search_performed && !empty($session_filters['baths'])) ? $session_filters['baths'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
        </select>
        
        </div>
		
		<div class="mls-form-group">
			<label><?php echo mls_plugin_translate('labels','price'); ?></label>
    <input type="text" class="pricerangeResults price-range-iput-block" 
        value="<?php echo isset($session_filters['price']) && $mls_search_performed ? $session_filters['price'] : $min_max_price; ?>" readonly>
    <div class="mls-dropdown">
        <div class="mls-rrange-slider">
            <label><?php echo mls_plugin_translate('labels','price_selector'); ?></label>
            <div class="price-input">
                <div class="field">
                    <input type="number" value="<?php echo isset($session_filters['min_price']) && $mls_search_performed ? $session_filters['min_price'] : $min_price; ?>" 
                        min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" name="min_price" class="min_price price-range-field prf-min" />
                </div>
                <div class="separator">-</div>
                <div class="field">
                    <input type="number" value="<?php echo isset($session_filters['max_price']) && $mls_search_performed ? $session_filters['max_price'] : $max_price; ?>" 
                        min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" name="max_price" class="max_price price-range-field prf-max" />
                </div>
            </div>
            <div class="price-range-display">
                <?php echo mls_plugin_translate('labels','price_range'); ?>
                <input class="pricerangeDisplay price-range-display-block" 
                    value="<?php echo isset($session_filters['price']) && $mls_search_performed ? $session_filters['price'] : $min_max_price; ?>" readonly/>
            </div>
            <div class="slider-range-wrapper" 
                 data-currency="<?php echo $currencies; ?>"  data-min="<?php echo $min_price; ?>" 
                 data-max="<?php echo $max_price; ?>"> 
                <div class="slider-range price-filter-range pfr-slider-range" name="rangeInput" 
                    data-min="<?php echo isset($session_filters['min_price']) && $mls_search_performed ? $session_filters['min_price'] : $min_price; ?>" 
                    data-max="<?php echo isset($session_filters['max_price']) && $mls_search_performed ? $session_filters['max_price'] : $max_price; ?>">
                </div>
            </div>
        </div>
		<div class="pr-btn-wrapper"><button class="price-range-reset"><?php echo mls_plugin_translate('buttons','reset'); ?></button> <button class="price-range-done" onclick="return false;">Done</button></div>
    </div>
</div>

		<div class="mls-form-group">
        <input type="submit" name="mls_search_submit" value="<?php echo mls_plugin_translate('buttons','search_properties'); ?>" style="background-color: <?php echo esc_attr($formbuttoncolor); ?>;border-color: <?php echo esc_attr($formbuttoncolor); ?>;"/>
        </div>
    </form>
    </div>
    </div>
</div>
    <?php
session_write_close();
    return ob_get_clean();
}

function mls_plugin_handle_form_submission() {
    if (isset($_POST['mls_search_submit'])) {
        $area = isset($_POST['mls_search_area']) ? array_map('sanitize_text_field', $_POST['mls_search_area']) : [];
        $type = isset($_POST['mls_search_type']) ? array_map('sanitize_text_field', $_POST['mls_search_type']) : [];
        $keyword = sanitize_text_field($_POST['mls_search_keyword']);
        $beds = sanitize_text_field($_POST['mls_search_beds']);
        $baths = sanitize_text_field($_POST['mls_search_baths']);
        $min_price = sanitize_text_field($_POST['mls_search_minprice']);
        $max_price = sanitize_text_field($_POST['mls_search_maxprice']);
		$min_prices = sanitize_text_field($_POST['min_price']);
        $max_prices = sanitize_text_field($_POST['max_price']);
		$min_max_price = $min_prices . ' to ' . $max_prices;
        
//         echo $min_prices . ' - ' . $max_prices; die('test');
        
        $ownpageresult = isset($_POST['ownpageresult']) ? sanitize_text_field($_POST['ownpageresult']) : 'false';
        $filter_type = isset($_POST['filter_type']) ? sanitize_text_field($_POST['filter_type']) : '';
        $maxthumbnail = isset($_POST['maxthumbnail']) ? sanitize_text_field($_POST['maxthumbnail']) : '';
        $newdevelopment = isset($_POST['newdevelopment']) ? sanitize_text_field($_POST['newdevelopment']) : '';
        $page = isset($_POST['page_num']) ? intval($_POST['page_num']) : 1;
        // $p_sorttype = isset($_POST['p_sorttype']) ? sanitize_text_field($_POST['p_sorttype']) : '';
        $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $_POST['p_sorttype'];
        $includesorttype = isset($_POST['includesorttype']) ? intval($_POST['includesorttype']) : 1;
        $language = isset($_POST['language']) ? intval($_POST['language']) : 1;
        
        // Store filter values in session
        $_SESSION['mls_search_filters'] = array(
            'area' => $area,
            'type' => $type,
            'keyword' => $keyword,
            'beds' => $beds,
            'baths' => $baths,
            'min_price' => $min_prices,
            'max_price' => $max_prices,
			'price' => $min_max_price,
            'filter_type' => $filter_type,
            'p_sorttype' => $p_sorttype,
            'includesorttype' => $includesorttype,
            'language' => $language,
            'newdevelopment' => $newdevelopment,
            'maxthumbnail' => $maxthumbnail,
			'ownpageresult' => $ownpageresult,
            'page_num' => $page
        );
		
		if (!isset($query_id)) {
			$query_id = ''; // Default to an empty string or set a fallback value
		}
		
        $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_prices, $max_prices, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment);

        if (isset($data['QueryInfo']['QueryId'])) {
            $query_id = $data['QueryInfo']['QueryId'];
        }

        // Conditional redirect based on 'ownpageresult' value
        if ($ownpageresult === 'true') {
            // Redirect to the same page (using referer)
            wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id , 'page_num' => $page, 'p_sorttype' => $p_sorttype ), wp_get_referer()));
        } else {
            // Redirect to page ID 931 (replace with your page ID or URL)
            wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id , 'page_num' => $page , 'p_sorttype' => $p_sorttype), get_permalink(7866)));
        }
        
    }
}
add_action('admin_post_mls_search', 'mls_plugin_handle_form_submission');
add_action('admin_post_nopriv_mls_search', 'mls_plugin_handle_form_submission');


// Function to display search results with optional search form
function mls_plugin_display_search_results($atts = []) {
    // Define default attributes for the search results shortcode
    $default_atts = [
        'includesearch' => 'true',
        'filtertype' => 'sales',
        'searchtitle' => 'searchtitle' ,
        'maxthumbnail' => '',
        'bedsfilter' => 5,
        'bathsfilter' => 5,
        'min_pricefilter' => '0',
        'max_pricefilter' => '10000000',
        'includesorttype' => '1',
        'language' => '1',
        'newdevelopment' => 'include',
        'formbackgroundcolor' => '',
        'formbuttoncolor' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_search_results');

    ob_start();
	
	if (isset($_SESSION['mls_search_filters']['ownpageresult'])) { $ownpageresult = $_SESSION['mls_search_filters']['ownpageresult']; }else{$ownpageresult = 'false';}
	
    // Display the search form if 'includesearch' is true
    if ( ($atts['includesearch'] === 'true' &&  !isset($_GET['mls_search_performed'])) || ($atts['includesearch'] === 'true' &&  $ownpageresult === 'false' ) ) {
        // Pass relevant attributes to the search shortcode
       echo '<div class="mls-searchresult-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($atts['newdevelopment']) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';
    }

    // Check if search has been performed
    if (isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1') {
        if (isset($_GET['query_id']) && isset($_GET['page_num'])) {
            $query_id = $_GET['query_id'];
            $page = $_GET['page_num'];

            // Retrieve filter values from session
            if (isset($_SESSION['mls_search_filters'])) {
                $filters = $_SESSION['mls_search_filters'];
                $area = isset($filters['area']) ? $filters['area'] : [];
                $type = isset($filters['type']) ? $filters['type'] : [];
                $keyword = isset($filters['keyword']) ? $filters['keyword'] : '';
                $beds = isset($filters['beds']) ? $filters['beds'] : '';
                $baths = isset($filters['baths']) ? $filters['baths'] : '';
                $min_price = isset($filters['min_price']) ? $filters['min_price'] : '';
                $max_price = isset($filters['max_price']) ? $filters['max_price'] : '';
                $filter_type = isset($filters['filter_type']) ? $filters['filter_type'] : '';
                $maxthumbnail = isset($filters['maxthumbnail']) ? $filters['maxthumbnail'] : '';
                $includesorttype = isset($filters['includesorttype']) ? $filters['includesorttype'] : '';
                $language = isset($filters['language']) ? $filters['language'] : '';
                $newdevelopment = isset($filters['newdevelopment']) ? $filters['newdevelopment'] : '';
				// Update option temporarily
     			update_option('mls_temp_language_code', $language);
                $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $filters['p_sorttype'];
				
                // Fetch properties with the stored filters and query ID
                $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_price, $max_price, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment);

                if ($data) {
                    return '<div class="mls-search-results"><div class="mls-container">' . mls_plugin_display_propertiess($data, $maxthumbnail, $includesorttype, $p_sorttype, $language,$filter_type) . '</div></div>';
                } else {
                    return '<div class="search-not-perform"><p>'.mls_plugin_translate("error","mls_nosearchresult").'</p></div>';
                }
            } else {
                return '<div class="search-not-perform"><p>'.mls_plugin_translate("error","mls_sessionexpired").'</p></div>';
            }
        }
    }

    return ob_get_clean(); // Return output buffer contents
}

function mls_plugin_property_search_form($atts = []) {
	
    // Define default attributes for the list shortcode
    $default_atts = [
        'includesearch' => 'true',
        'filtertype' => 'sales',
        'ownpageresult' => 'false',
        'searchtitle' => 'searchtitle' ,
        'maxthumbnail' => '',
        'bedsfilter' => 5,
        'bathsfilter' => 5,
        'min_pricefilter' => '0',
        'max_pricefilter' => '10000000',
        'includesorttype' => '1',
        'language' => '1',
        'newdevelopment' => 'include',
        'formbackgroundcolor' => '',
		'formbuttoncolor' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_search');
	$ownpageresult = $atts['ownpageresult'];
    ob_start();
	

	
    // Handle the 'includesearch' attribute to show or hide the search form
//     $search_output = '';
    if ($atts['includesearch'] === 'true') {
        // Pass relevant attributes to the search shortcode
        $search_output = '<div class="mls-property-search-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" ownpageresult="' . esc_attr($atts['ownpageresult']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($atts['newdevelopment']) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';

    }
	
	if ($ownpageresult === 'true' && isset($_GET['mls_search_performed'])) {
		$searchresult = '<div class="mls-property-search-results">'.do_shortcode('[mls_search_results]').'</div>';
		
		return '<div class="mls-propertysearchform mls-property-search-performed"><div class="mls-container">' . $search_output . $searchresult . '</div></div>';	
	}else{
	return '<div class="mls-propertysearchform"><div class="mls-container">' . $search_output . '</div></div>';
	}

	
    // Return combined search and property display outputs
    return ob_get_clean();
}

// Generate options for beds and baths
function generate_bed_bath_options($max, $prefix = '', $selected_value = '') {
    $options = [];
    for ($i = 1; $i <= $max; $i++) {
        $selected = ($i == $selected_value) ? 'selected' : '';
        // Escape the value and prefix to ensure security
        $options[] = '<option value="' . esc_attr($i) . '" ' . esc_attr($selected) . '>' . esc_html($i . $prefix) . '</option>';
    }
    return implode('', $options);
}


function mls_property_list_shortcode($atts = []) {
	
    // Define default attributes for the list shortcode
    $default_atts = [
        'includesearch' => 'true',
        'filtertype' => 'sales',
        'ownpageresult' => 'true',
        'searchtitle' => 'searchtitle' ,
        'maxthumbnail' => '',
        'bedsfilter' => 5,
        'bathsfilter' => 5,
        'min_pricefilter' => '0',
        'max_pricefilter' => '10000000',
        'includesorttype' => '1',
        'language' => '1',
        'newdevelopment' => 'include',
        'formbackgroundcolor' => '',
		'formbuttoncolor' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_list');

    ob_start();
	
	$stored_types_with_labels = get_option('mls_plugin_property_types', array());
	$propertyvalues = array(); 

foreach ($stored_types_with_labels as $json_item) {
    $decoded_item = json_decode($json_item, true); 
    if (is_array($decoded_item)) {
        $propertyvalue = array_values($decoded_item);
        if (!empty($propertyvalue)) {
            $propertyvalues[] = $propertyvalue[0];
        }
    }
}

	
    // Handle the 'includesearch' attribute to show or hide the search form
//     $search_output = '';
    if ($atts['includesearch'] === 'true') {
        // Pass relevant attributes to the search shortcode
        $search_output = '<div class="mls-propertylist-search-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" ownpageresult="' . esc_attr($atts['ownpageresult']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($atts['newdevelopment']) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';

    }
	
	$mls_selected_tabs = get_option('mls_plugin_tabs_to_display', ['sales']);
	if(isset($_SESSION['mls_search_filters']['filter_type']) && $_GET['mls_search_performed'] == '1'){ $filter_type = $_SESSION['mls_search_filters']['filter_type']; }elseif(!empty($atts['filtertype']) && $atts['filtertype'] != 'sales'){ $filter_type = $atts['filtertype']; }elseif(count($mls_selected_tabs) === 1){ $filter_type = $mls_selected_tabs[0];  }else{ $filter_type = $atts['filtertype']; }
		
    $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $atts['p_sorttype'];
    $includesorttype = isset($atts['includesorttype']) ? sanitize_text_field($atts['includesorttype']) : '1';
	$language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '1';
    $newdevelopment = isset($atts['newdevelopment']) ? sanitize_text_field($atts['newdevelopment']) : '';
    $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
    $query_id = isset($_GET['query_id']) ? sanitize_text_field($_GET['query_id']) : '';
// Update option temporarily
     update_option('mls_temp_language_code', $language, true);
	
	$_SESSION['mls_search_filters'] = array( 'newdevelopment' => $newdevelopment );
	
    // Fetch the properties based on search parameters
    if (get_option('mls_plugin_style_proplanghide')) {
        $data = mls_plugin_fetch_properties([''],[''],'','','','','', $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment);
    }else{
		$data = mls_plugin_fetch_properties([''],$propertyvalues,'','','','','', $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment);
	}
    
    // Check if search was submitted and hide property list until results are shown
    if (!isset($_GET['mls_search_performed'])) {
        // Display the properties
        return '<div class="mls-propertylist"><div class="mls-container">' . $search_output .  mls_plugin_display_propertiess($data,$atts['maxthumbnail'], $includesorttype, $p_sorttype, $language,$filter_type) . '</div></div>';
    } 
	// Check if search was submitted and show property list until results are shown
	if (isset($_GET['mls_search_performed'])) {
        // Display the properties
        return '<div class="mls-propertylist mls-propertylist-search-performed"><div class="mls-container">' . $search_output .   mls_plugin_display_propertiess($data,$atts['maxthumbnail'], $includesorttype, $p_sorttype, $language,$filter_type) . '</div></div>';
    }

	
    // Return combined search and property display outputs
    return ob_get_clean();
}

function mls_property_byrefs_shortcode($atts = []) {
    // Define default attributes for the list shortcode
    $default_atts = [
        'filtertype' => 'sales',
        'maxthumbnail' => '',
        'language' => '1',
        'newdevelopment' => 'include',
        'references' => ''
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_byrefs');

    ob_start();

   
    $filter_type = isset($atts['filtertype']) ? sanitize_text_field($atts['filtertype']) : '';
    $language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '';
    $newdevelopment = isset($atts['newdevelopment']) ? sanitize_text_field($atts['newdevelopment']) : '';
   $references = (isset($atts['references']) && !empty($atts['references'])) ? sanitize_text_field($atts['references']) : '';
// Update option temporarily
    update_option('mls_temp_language_code', $language);
	
	$_SESSION['mls_search_filters'] = array( 'newdevelopment' => $newdevelopment );
	
	if($references){
    // Fetch the properties based on search parameters
    $data = mls_plugin_fetch_properties([''],[''], $references,'','','','', $filter_type, '', '', '', $language, $newdevelopment);

    // Check if search was submitted and hide property list until results are shown
    if ($data) {
    // Add the div with the desired class
    echo '<div class="mlspropetybyerference"><div class="mls-container">';
    // Display the properties
    $properties_output = mls_plugin_display_propertiess($data, $atts['maxthumbnail'], '', '', $language,$filter_type);
    echo wp_kses_post($properties_output);
    // Close the div
    echo '</div></div>';
} else {
        $properties_output = '';
    }
}else{echo mls_plugin_translate("error","prpreferr");}

    // Return combined search and property display outputs
    return ob_get_clean();
}

/**** MLS Banner Search Form****/
function mls_plugin_banner_search_form($atts = []) {
    // Define default attributes for banner search
    $default_atts = [
        'filtertype' => 'sales',
        'searchtitle' => 'searchtitle' ,
        'maxthumbnail' => '',
        'bedsfilter' => 5,
        'bathsfilter' => 5,
		'includesorttype' => '1',
        'language' => '1',
        'newdevelopment' => 'include',
        'min_pricefilter' => '0',
        'max_pricefilter' => '10000000'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_banner_search');
    $language = $atts['language'];
	
	// Update option temporarily
    update_option('mls_temp_language_code', $language);

	// Set the searchtitle after updating the option
if ($atts['searchtitle'] == 'searchtitle') {
    $atts['searchtitle'] = mls_plugin_translate('labels', 'searchtitle');
}

    $filter_type = $atts['filtertype'];
    $searchtitle = $atts['searchtitle'];
    $maxthumbnail = $atts['maxthumbnail'];
	$includesorttype = $atts['includesorttype'];
    
    $newdevelopment = $atts['newdevelopment'];
    $min_price = $atts['min_pricefilter'];
	$max_price = $atts['max_pricefilter'];
    $min_max_price = $min_price . ' to ' . $max_price;

    // Fetch the property types selected in the settings & Multi-lang
    $stored_types_with_labels = get_option('mls_plugin_property_types', array());
	$property_types = mls_plugin_get_cached_propertytype_multilang($language);
	
	
	// Fetch the currencies from mls-plugin-fetch-properties
$currencies = mls_plugin_get_cached_currencies(); 
	
	
   // Fetch the Location from mls-plugin-fetch-properties
$locations = mls_plugin_get_cached_locations(); 
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
        $locations_array =  $responseLocations['LocationData']['ProvinceArea']['Locations']['Location'];
      }
		 $locations_array = $locations_array; }
// 	else{ echo 'no location'; }



    // Search form HTML
    ob_start();
    ?>
    <section class="mls-banner-search-wrapper">
        <div class="mls-container">
            <div class="filter-form-wrapper mls-heading mls-main-content mls-banner-search">
                <h4><?php echo esc_attr($searchtitle); ?></h4>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="banner_search_form" class="mls-proplist-search-form mls-form">
                    <input type="hidden" name="action" value="mls_banner_search">
                    <input type="hidden" name="maxthumbnail" value="<?php echo esc_attr($maxthumbnail); ?>">
					<input type="hidden" name="includesorttype" value="<?php echo esc_attr($includesorttype); ?>">
                    <input type="hidden" name="language" value="<?php echo esc_attr($language); ?>">
                    <input type="hidden" name="newdevelopment" value="<?php echo esc_attr($newdevelopment); ?>">
					
<!--	sales, rental & new development tab in banner search form	 -->
<?php
// Fetch settings and shortcode attributes
$selected_tabs = get_option('mls_plugin_tabs_to_display', ['sales']);
$shortcode_filtertype = isset($atts['filtertype']) ? sanitize_text_field($atts['filtertype']) : null;

// Determine the active filter type
$active_filter = (($mls_search_performed && isset($session_filters['filter_type'])) 
    ? $session_filters['filter_type'] 
    : ($filter_type ?? 'sales'));


// If only one tab is selected
if (count($selected_tabs) === 1) {
    $single_tab_value = $selected_tabs[0];

    // If shortcode attribute exists, prioritize it
    $hidden_value = $shortcode_filtertype ?: $single_tab_value;
    ?>
    <input type="hidden" name="filter_type" value="<?php echo esc_attr($hidden_value); ?>">
    <?php
} else {
    // Show the tabs
    ?>
    <div class="mls-tab-container">
		<ul class="srh-tab-nav">
        <?php foreach ($selected_tabs as $tab): ?>
			<li>
            <input type="radio" id="tab_<?php echo esc_attr($tab); ?>" name="filter_type" value="<?php echo esc_attr($tab); ?>"
                   <?php echo ($active_filter === $tab) ? 'checked' : ''; ?>>
            <label class="tab" for="tab_<?php echo esc_attr($tab); ?>">
                <?php echo mls_plugin_translate('labels', $tab); ?>
            </label>
			</li>
        <?php endforeach; ?>
		</ul>
    </div>
    <?php
}
?>
					
                    <div class="mls-form-group">
        <label for="mls_search_area"><?php echo mls_plugin_translate('labels','area'); ?></label>
        <select id="mls_search_area_ban" name="mls_search_area[]" class="mls_area_sel" multiple>
            <?php
            if (!empty($locations_array)) {
                foreach ($locations_array as $location) {
                    // $selected = in_array($location, $session_filters['area'] ?? []) ? 'selected' : '';
                    $selected = (in_array($location, $session_filters['area'] ?? []) && $mls_search_performed) ? 'selected' : '';
                    echo '<option value="' . esc_attr($location) . '" ' . esc_attr($selected) . '>' . esc_html($location) . '</option>';
                }
            } else {
                echo '<option value="">'. mls_plugin_translate("labels","no_area"). '</option>';
            }
            ?>
        </select>
        
        </div>
		<div class="mls-form-group">

        <label for="mls_search_type"><?php echo mls_plugin_translate('labels','property_type'); ?></label>
        
            <?php
	if (!get_option('mls_plugin_style_proplanghide')) { //property type selected in setting
		
	echo '<select id="mls_search_type_ban" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (!empty($stored_types_with_labels)) {
                foreach ($stored_types_with_labels as $item) {
                    $decoded_item = json_decode($item, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_item)) {
                        foreach ($decoded_item as $label => $value) {
                            // echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
                            $selected = (in_array($value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
                            echo '<option value="' . esc_attr($value) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>Error decoding option: ' . esc_html($item) . '</option>';
                    }
                }
            } else {
                echo '<option value="">'. mls_plugin_translate("labels","no_property_type"). '</option>';
            }
		echo '</select>';
	}
	else { //multi-lang property type directly from resale
	echo '<select id="mls_search_type_ban" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (isset($property_types['PropertyTypes']) && is_array($property_types['PropertyTypes'])) {        
        foreach ($property_types['PropertyTypes']['PropertyType'] as $type) {
            $option_value = $type['OptionValue'];  // The value attribute
            $option_label = $type['Type'];
$selected = (in_array($option_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option value="' . esc_attr($option_value) . '" ' . esc_attr($selected) . '>' . esc_html($option_label) . '</option>';
            $with_subtypes = false;

            if (isset($type['SubType']) && count($type['SubType']) > 0) {
                $with_subtypes = true;
            }

            if ($with_subtypes) { 
                foreach ($type['SubType'] as $subtype) {
                    $suboption_value = $subtype['OptionValue'];  // The value attribute
                    $suboption_label = $subtype['Type'];
$selected = (in_array($suboption_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option value="' . esc_attr($suboption_value) . '" ' . esc_attr($selected) . '>' . esc_html($suboption_label) . '</option>';
                   
                }
            }
        }
    }else { echo mls_plugin_translate("labels","no_property_type"); } 
	 echo '</select>';
	}
            ?>
        

        </div><div class="mls-form-group">

        <label for="mls_search_keyword"><?php echo mls_plugin_translate('labels','reference_id'); ?></label>
        <!-- <input type="text" id="mls_search_keyword" value="<?php echo esc_attr($session_filters['keyword'] ?? ''); ?>" name="mls_search_keyword" /> -->
        <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','search_reference_id'); ?>" id="mls_search_keyword_ban" 
        value="<?php echo esc_attr(($mls_search_performed && !empty($session_filters['keyword'])) ? $session_filters['keyword'] : ''); ?>" 
        name="mls_search_keyword" />

        </div>
			
					<div class="mls-form-group">
    <label for="mls_search_beds"><?php echo mls_plugin_translate('labels','beds'); ?></label>
    <select id="mls_search_beds" name="mls_search_beds" class="sel-app">
        <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
        <?php echo wp_kses( generate_bed_bath_options($atts['bedsfilter'], '+', ($mls_search_performed && !empty($session_filters['beds'])) ? $session_filters['beds'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
    </select>
</div>

<div class="mls-form-group">
    <label for="mls_search_baths"><?php echo mls_plugin_translate('labels','baths'); ?></label>
    <select id="mls_search_baths" name="mls_search_baths" class="sel-app">
        <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
        <?php echo wp_kses( generate_bed_bath_options($atts['bathsfilter'], '+', ($mls_search_performed && !empty($session_filters['baths'])) ? $session_filters['baths'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
    </select>
</div>


<div class="mls-form-group">
			<label><?php echo mls_plugin_translate('labels','price'); ?></label>
    <input type="text" class="pricerangeResults price-range-iput-block" 
        value="<?php echo isset($session_filters['price']) && $mls_search_performed ? $session_filters['price'] : $min_max_price; ?>" readonly>
    <div class="mls-dropdown">
        <div class="mls-rrange-slider">
            <label><?php echo mls_plugin_translate('labels','price_selector'); ?></label>
            <div class="price-input">
                <div class="field">
                    <input type="number" value="<?php echo isset($session_filters['min_price']) && $mls_search_performed ? $session_filters['min_price'] : $min_price; ?>" 
                        min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" name="mls_search_minprice" class="min_price price-range-field prf-min" />
                </div>
                <div class="separator">-</div>
                <div class="field">
                    <input type="number" value="<?php echo isset($session_filters['max_price']) && $mls_search_performed ? $session_filters['max_price'] : $max_price; ?>" 
                        min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" name="mls_search_maxprice" class="max_price price-range-field prf-max" />
                </div>
            </div>
            <div class="price-range-display">
                <?php echo mls_plugin_translate('labels','price_range'); ?>
                <input class="pricerangeDisplay price-range-display-block" 
                    value="<?php echo isset($session_filters['price']) && $mls_search_performed ? $session_filters['price'] : $min_max_price; ?>" readonly/>
            </div>
            <div class="slider-range-wrapper" 
                 data-currency="<?php echo $currencies; ?>"  data-min="<?php echo $min_price; ?>" 
                 data-max="<?php echo $max_price; ?>"> 
                <div class="slider-range price-filter-range pfr-slider-range" name="rangeInput" 
                    data-min="<?php echo isset($session_filters['min_price']) && $mls_search_performed ? $session_filters['min_price'] : $min_price; ?>" 
                    data-max="<?php echo isset($session_filters['max_price']) && $mls_search_performed ? $session_filters['max_price'] : $max_price; ?>">
                </div>
            </div>
        </div>
		<div class="pr-btn-wrapper"><button class="price-range-reset"><?php echo mls_plugin_translate('buttons','reset'); ?></button> <button class="price-range-done" onclick="return false;">Done</button></div>
    </div>
</div>
					

		<div class="mls-form-group">
                        <input type="submit" name="mls_banner_search_submit" value="<?php echo mls_plugin_translate('buttons','search_properties'); ?>" />
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}


// Handle form submission for banner search
function mls_plugin_handle_banner_form_submission() {
    if (isset($_POST['mls_banner_search_submit'])) {
        // Process form data similar to the original function
        $area = isset($_POST['mls_search_area']) ? array_map('sanitize_text_field', $_POST['mls_search_area']) : [];
        $type = isset($_POST['mls_search_type']) ? array_map('sanitize_text_field', $_POST['mls_search_type']) : [];
        $keyword = sanitize_text_field($_POST['mls_search_keyword']);
        $beds = sanitize_text_field($_POST['mls_search_beds']);
        $baths = sanitize_text_field($_POST['mls_search_baths']);
        $min_prices = sanitize_text_field($_POST['mls_search_minprice']);
        $max_prices = sanitize_text_field($_POST['mls_search_maxprice']);
        $min_max_price = $min_prices . ' to ' . $max_prices;
        $filter_type = isset($_POST['filter_type']) ? sanitize_text_field($_POST['filter_type']) : '';
        $newdevelopment = isset($_POST['newdevelopment']) ? sanitize_text_field($_POST['newdevelopment']) : '';
        $maxthumbnail = isset($_POST['maxthumbnail']) ? sanitize_text_field($_POST['maxthumbnail']) : '';
		$includesorttype = isset($_POST['includesorttype']) ? intval($_POST['includesorttype']) : 1;
        $language = isset($_POST['language']) ? intval($_POST['language']) : 1;
        $page = 1; // Always start at page 1 for new searches

        // Store filter values in session
        $_SESSION['mls_search_filters'] = array(
            'area' => $area,
            'type' => $type,
            'keyword' => $keyword,
            'beds' => $beds,
            'baths' => $baths,
            'min_price' => $min_prices,
            'max_price' => $max_prices,
            'price' => $min_max_price,
            'filter_type' => $filter_type,
            'maxthumbnail' => $maxthumbnail,
			'includesorttype' => $includesorttype,
            'language' => $language,
            'newdevelopment' => $newdevelopment,
            'page_num' => $page
        );

        $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_prices, $max_prices, $filter_type, '', $page, '', $language, $newdevelopment);

        if (isset($data['QueryInfo']['QueryId'])) {
            $query_id = $data['QueryInfo']['QueryId'];
        }

        // Redirect to the search results page (replace 7866 with your actual search results page ID)
        wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id, 'page_num' => $page), get_permalink(7866)));
        exit;
    }
}
add_action('admin_post_mls_banner_search', 'mls_plugin_handle_banner_form_submission');
add_action('admin_post_nopriv_mls_banner_search', 'mls_plugin_handle_banner_form_submission');


?>