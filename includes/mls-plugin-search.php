<?php
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
		'propertytypes' => '',
		'locations' => '',
		'filterid' => '',
        'pmustfeatures' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_searchformcode');
    
	if(!get_option('mls_plugin_style_proplanghide') ){ $language = get_option('mls_plugin_prop_language'); }
	else{ $language = $atts['language']; }
	
	// Update option temporarily
    mls_localize_translations_for_language($language);

	// Set the searchtitle after updating the option
if ($atts['searchtitle'] == 'searchtitle') {
    $atts['searchtitle'] = mls_plugin_translate('labels', 'searchtitle');
}

	$filter_type = $atts['filtertype'];
    $searchtitle = $atts['searchtitle'];
    $ownpageresult = $atts['ownpageresult'];
    $maxthumbnail = $atts['maxthumbnail'];
    $includesorttype = $atts['includesorttype'];
	
	$mls_atts_filterid = isset($atts['filterid']) ? $atts['filterid'] : '';
    $mls_atts_propertytypes = ( isset($atts['propertytypes']) && empty($mls_atts_filterid) ) ? $atts['propertytypes'] : '';
    $mls_atts_locations = ( isset($atts['locations']) && empty($mls_atts_filterid) ) ? $atts['locations'] : '';
    $mls_atts_pmustfeatures = ( isset($atts['pmustfeatures']) && empty($mls_atts_filterid) ) ? $atts['pmustfeatures'] : '';

    // If values are present, store them in session
    if (!isset($_SESSION['mls_search_filters'])) { $_SESSION['mls_search_filters'] = []; }
    if (!isset($_GET['mls_search_performed'])) {
    if (!empty($mls_atts_propertytypes)) { $mls_atts_propertytypes = array_map('trim', explode(',', $mls_atts_propertytypes)); $_SESSION['mls_search_filters']['type'] = $mls_atts_propertytypes; }else{ $_SESSION['mls_search_filters']['type'] = []; }
    if (!empty($mls_atts_locations)) { $mls_atts_locations = array_map('trim', explode(',', $mls_atts_locations)); $_SESSION['mls_search_filters']['area'] = $mls_atts_locations; }else{ $_SESSION['mls_search_filters']['area'] = []; }
    if (!empty($mls_atts_filterid)) { $_SESSION['mls_search_filters']['filterid'] = $mls_atts_filterid; }else{ $_SESSION['mls_search_filters']['filterid'] = ''; }
    }
    
	$newdevelopment = $atts['newdevelopment'];
	$formbackgroundcolor = $atts['formbackgroundcolor'];
	$formbuttoncolor = $atts['formbuttoncolor'];
    $min_price = $atts['min_pricefilter'];
	$max_price = $atts['max_pricefilter'];
    $min_max_price = $min_price . ' to ' . $max_price;
    // $p_sorttype = $atts['p_sorttype'];
    $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $atts['p_sorttype'];
    // Fetch the property types selected in the settings & Multi-lang
    
	if($mls_atts_filterid){ $property_types = mls_plugin_get_cached_propertytype_customfilterid($mls_atts_filterid, $language);  }
	else{ $property_types = mls_plugin_get_cached_propertytype_multilang($language);  }

	// Update option temporarily
    mls_localize_translations_for_language($language);
    
   // Fetch the currencies from mls-plugin-fetch-properties
$currencies = mls_plugin_get_cached_currencies(); 
	
// 	print_r($_SESSION['mls_search_filters']);
    
// Fetch the Location from mls-plugin-fetch-properties
if($mls_atts_filterid){ $locations = mls_plugin_get_cached_customfilterid_locations($mls_atts_filterid); }
else{ $locations = mls_plugin_get_cached_locations(); }
	if ($locations) {
//  return print_r($locations, true);
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

	// Check if grouping is enabled and has groups
$grouping_enabled = get_option('mls_plugin_custom_locationgrouping', '0') === '1';
$groups = get_option('mls_location_groups', []);
$locationgrouping = ($grouping_enabled && !empty($groups) && !$mls_atts_filterid && !$mls_atts_locations ) ? 'locationgroupingenabled' : 'locationgroupingdisabled';

    // Search form HTML
    ob_start();
    ?>

<div class="mls-parent-search-wrapper proplst-result-search">
    <div class="mls-container">
    <div class="filter-form-wrapper mls-heading mls-main-content <?php echo esc_attr($locationgrouping); ?>" style="background-color: <?php echo esc_attr($formbackgroundcolor); ?>;border-color: <?php echo esc_attr($formbackgroundcolor); ?>;">
    <h4><?php echo esc_attr($searchtitle); ?></h4>

    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="search_form" class="mls-proplist-search-form mls-form">
    <input type="hidden" name="action" id="mlssearch_form" value="mls_search">
  	<input type="hidden" name="query_id" value="<?php echo isset($_GET['query_id']) ? esc_attr($_GET['query_id']) : ''; ?>">
    <input type="hidden" name="page" value="<?php echo isset($_GET['page_num']) ? esc_attr($_GET['page_num']) : '1'; ?>">
    <input type="hidden" name="ownpageresult" value="<?php echo esc_attr($ownpageresult); ?>">
    <input type="hidden" name="maxthumbnail" value="<?php echo esc_attr($maxthumbnail); ?>">
    <input type="hidden" name="p_sorttype" id="search_form_sorttype" class="search_form_sorttype" value="<?php echo esc_attr($p_sorttype); ?>">
    <input type="hidden" name="includesorttype" value="<?php echo esc_attr($includesorttype); ?>">
    <input type="hidden" name="language" value="<?php echo esc_attr($language); ?>">
    <input type="hidden" name="newdevelopment" value="<?php echo esc_attr($newdevelopment); ?>">
	<input type="hidden" name="defaultminprice" value="<?php echo esc_attr($min_price); ?>">
	<input type="hidden" name="defaultmaxprice" value="<?php echo esc_attr($max_price); ?>">
    <input type="hidden" name="mls_atts_filterid" value="<?php echo esc_attr($mls_atts_filterid); ?>">
	<input type="hidden" name="wpml_lang" value="<?php echo apply_filters('wpml_current_language', null); ?>">

<!-- 	Custom filterID - hide Tab	 -->
	<?php if(empty($mls_atts_filterid)){ ?>	
		
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
    <div class="mls-tab-container mls-f-order1">
		<ul id="mls-navtabs1" class="scroll_tabs_theme_light custom-mls-tab">
        <?php foreach ($selected_tabs as $tab): ?>
			<li class="custom-mls-tab-list">
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
	}else{ ?>
	<input type="hidden" name="filter_type" value="<?php echo esc_attr($filter_type); ?>">	
<?php } ?>

		
<!-- 	Parent & child locations	 -->
		
		<?php
// Get current selections from session
$selected_parent = ($mls_search_performed && $session_filters['parent_area']) ? $session_filters['parent_area'] : '';
$selected_children = ($mls_search_performed && $session_filters['child_area']) ? $session_filters['child_area'] : [];

if ($grouping_enabled && !empty($groups) && !$mls_atts_filterid && !$mls_atts_locations ): ?>
    <div class="mls-form-group mls-f-order2 mls-location-group-container"
         data-groups='<?php echo wp_json_encode($groups); ?>'
         data-selected-parent='<?php echo esc_attr($selected_parent); ?>'
         data-selected-children='<?php echo wp_json_encode($selected_children); ?>'
         data-translate-select-parent='<?php echo esc_attr(mls_plugin_translate("labels","select_parent_area")); ?>'
         data-translate-select-child='<?php echo esc_attr(mls_plugin_translate("labels","select_child_area")); ?>'
         data-translate-please-select='<?php echo esc_attr(mls_plugin_translate("labels","please_select_child_area")); ?>'>
        <div>
        <label for="mls_search_parent_area"><?php echo mls_plugin_translate('labels','area'); ?></label>
        <select id="mls_search_parent_area" name="mls_search_parent_area" class="mls_parent_area_sel sel-app">
            <option value=""><?php echo mls_plugin_translate('placeholders','search_area'); ?></option>
            <?php
            foreach ($groups as $group) {
                $parent = $group['parent'];
                $selected = ($selected_parent === $parent) ? 'selected' : '';
                echo '<option value="' . esc_attr($parent) . '" ' . esc_attr($selected) . '>' . esc_html($parent) . '</option>';
            }
            ?>
        </select>
        </div>
        <div>
        <label for="mls_search_child_area"><?php echo mls_plugin_translate('labels','child_area'); ?></label>
        <select id="mls_search_child_area" name="mls_search_child_area[]" class="mls_child_area_sel mls-multiselect mls-ms-arw" multiple>
            <option value=""><?php echo mls_plugin_translate('placeholders','all_child_area'); ?></option>
            <?php
            // Pre-populate child options if parent is already selected
            if ($selected_parent) {
                foreach ($groups as $group) {
                    if ($group['parent'] === $selected_parent) {
                        foreach ($group['children'] as $child) {
                            $child_selected = in_array($child, $selected_children) ? 'selected' : '';
                            echo '<option value="' . esc_attr($child) . '" ' . esc_attr($child_selected) . '>' . esc_html($child) . '</option>';
                        }
                        break;
                    }
                }
            }
            ?>
        </select>
        </div>
    </div>

    <!-- Hidden field to store the original area values for form submission -->
    <input type="hidden" id="mls_search_area_hidden" name="mls_search_area[]" value="<?php echo esc_attr(implode(',', (array) $selected_children)); ?>">
<?php else: ?>
    <!-- Original single select field -->
    <div class="mls-form-group mls-f-order2">
        <label for="mls_search_area"><?php echo mls_plugin_translate('labels','area'); ?></label>
        <select id="mls_search_area" name="mls_search_area[]" class="mls_area_sel" multiple>
            <?php
            if (!empty($locations_array)) {
                foreach ($locations_array as $location) {
                    $selected = (in_array($location, $session_filters['area'] ?? []) && ($mls_search_performed || !empty($mls_atts_locations))) ? 'selected' : '';
                    echo '<option value="' . esc_attr($location) . '" ' . esc_attr($selected) . '>' . esc_html($location) . '</option>';
                }
            } else {
                echo '<option value="">'. mls_plugin_translate("labels","no_area"). '</option>';
            }
            ?>
        </select>
    </div>
<?php endif; ?>
		
		<div class="mls-form-group mls-f-order3">

        <label for="mls_search_type"><?php echo mls_plugin_translate('labels','property_type'); ?></label>
        
            <?php
	
		
$property_types_data = $property_types['PropertyTypes']['PropertyType'] ?? [];
if (isset($property_types_data['OptionValue'])) {
    $property_types_data = [$property_types_data]; // wrap single type into array
}
	echo '<select id="mls_search_type" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (!empty($property_types_data)) {
    foreach ($property_types_data as $type) {
        if (!is_array($type)) continue;
            $option_value = $type['OptionValue'];  // The value attribute
            $option_label = $type['Type'];
$selected = (in_array($option_value, $session_filters['type'] ?? []) && ($mls_search_performed || !empty($mls_atts_propertytypes))) ? 'selected' : '';
echo '<option value="' . esc_attr($option_value) . '" ' . esc_attr($selected) . ' data-parent="" >' . esc_html($option_label) . '</option>';
            $with_subtypes = false;

            if (isset($type['SubType']) && count($type['SubType']) > 0) {
                $with_subtypes = true;
				$subtypes = $type['SubType'];

            if (isset($subtypes['OptionValue'])) {
                $subtypes = [$subtypes]; // Wrap single subtype
            }
            }

            if ($with_subtypes) { 
                foreach ($subtypes as $subtype) {
                if (!is_array($subtype)) continue;
                    $suboption_value = $subtype['OptionValue'];  // The value attribute
                    $suboption_label = $subtype['Type'];
$selected = (in_array($suboption_value, $session_filters['type'] ?? []) && ($mls_search_performed || !empty($mls_atts_propertytypes))) ? 'selected' : '';
echo '<option data-parent="' . esc_attr($option_label) . '" value="' . esc_attr($suboption_value) . '" ' . esc_attr($selected) . ' >' . esc_html($suboption_label) . '</option>';
                   
                }
            }
        }
    }else { echo mls_plugin_translate("labels","no_property_type"); } 
	 echo '</select>';
	
            ?>
        

        </div>

		
		<div class="mls-form-group mls-f-order4">

        <label for="mls_search_keyword"><?php echo mls_plugin_translate('labels','reference_id'); ?></label>
        <!-- <input type="text" id="mls_search_keyword" value="<?php echo esc_attr($session_filters['keyword'] ?? ''); ?>" name="mls_search_keyword" /> -->
        <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','search_reference_id'); ?>" id="mls_search_keyword" 
        value="<?php echo esc_attr(($mls_search_performed && !empty($session_filters['keyword'])) ? $session_filters['keyword'] : ''); ?>" 
        name="mls_search_keyword" />

        </div><div class="mls-form-group mls-f-order6">
        
        <label for="mls_search_beds"><?php echo mls_plugin_translate('labels','beds'); ?></label>
        <select id="mls_search_beds" name="mls_search_beds" class="sel-app">
            <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
			<?php echo wp_kses( generate_bed_bath_options($atts['bedsfilter'], '+', ($mls_search_performed && !empty($session_filters['beds'])) ? $session_filters['beds'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
        </select>
        
        </div><div class="mls-form-group mls-f-order7">
        
        <label for="mls_search_baths"><?php echo mls_plugin_translate('labels','baths'); ?></label>
        <select id="mls_search_baths" name="mls_search_baths" class="sel-app">
            <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
            <?php echo wp_kses( generate_bed_bath_options($atts['bathsfilter'], '+', ($mls_search_performed && !empty($session_filters['baths'])) ? $session_filters['baths'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
        </select>
        
        </div>
		
		<div class="mls-form-group mls-f-order8">
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
		<div class="pr-btn-wrapper"><button class="price-range-reset"><?php echo mls_plugin_translate('buttons','reset'); ?></button> <button class="price-range-done" onclick="return false;"><?php echo mls_plugin_translate('buttons','done'); ?></button></div>
    </div>
</div>
<div class="mls-search-features-dropdown-bg"></div>
<div class="mls-search-features-dropdown">
    <div class="mls-feat-dropdown-top"><h4><?php echo mls_plugin_translate('general','additionalfeatures'); ?></h4><span class="mls-af-close">×</span></div>
    <div class="mls-feat-top-search">
	<div class="mls-feat-top-search-col1">
	

    <div class="mls-form-group mls-fts-sel">
        <select id="mls_search_features_search_type" name="mls_search_features_search_type" class="sel-app">
            <option disabled><?php echo mls_plugin_translate('labels','feature_search_type'); ?></option>
            <option value="2" <?php echo ($mls_search_performed && isset($session_filters['mls_search_features_search_type']) && $session_filters['mls_search_features_search_type'] == '2') ? 'selected' : ''; ?>>
                <?php echo mls_plugin_translate('options','musthavefeatures2'); ?>
            </option>
            <option value="1" <?php echo ($mls_search_performed && isset($session_filters['mls_search_features_search_type']) && $session_filters['mls_search_features_search_type'] == '1') ? 'selected' : ''; ?>>
                <?php echo mls_plugin_translate('options','musthavefeatures1'); ?>
            </option>            
        </select>
    </div>
		</div>
	<div class="mls-feat-top-search-col2">
		<div class="mls-form-group">
        <input type="submit" name="mls_search_features_resetbtn" value="<?php echo mls_plugin_translate('buttons','reset'); ?>" >
		<input type="submit" class="done-btn"  name="mls_search_features_donebtn" value="<?php echo mls_plugin_translate('buttons','done'); ?>" >
        </div>
		</div>
		
    </div>

	<?php echo mls_plugin_cached_searchfeatures_multilang($language, $mls_atts_pmustfeatures); ?>
</div>
		<div class="mls-form-group mls-f-order9">
        <input type="submit" name="mls_search_submit" value="<?php echo mls_plugin_translate('buttons','search_properties'); ?>" style="background-color: <?php echo esc_attr($formbuttoncolor); ?>;border-color: <?php echo esc_attr($formbuttoncolor); ?>;"/>
        </div>
		<div class="mls-form-group mls-add-feat-btn-wrapper mls-f-order5">
          <button type="button" class="mls-add-feat-btn"><img src="<?php echo esc_url( plugins_url('assets/images/filter_light.png', __DIR__) ); ?>" alt="" /> <?php echo mls_plugin_translate('buttons','additionalfeatures'); ?></button>
</div>

		
		
		
    </form>
		
		<div class="mls-selected-labels">
			<div class="mls_selected_features">
				<?php if( ($mls_search_performed && isset($session_filters['additional_features'])) || ($mls_atts_pmustfeatures && isset($_SESSION['mls_search_filters']) && $_SESSION['mls_search_filters']['additional_features'] ) ){  
				foreach($_SESSION['mls_search_filters']['additional_features'] as $feature_key => $feature_values){
					if (!empty($feature_values) && is_array($feature_values)) {
            foreach ($feature_values as $key => $value) {
                if (!empty($key)) { // Ensure the value is not empty
                    echo '<span>'. $key .'</span>';
                }
            }
        }
				}
			} ?>
			</div>
		</div>
		
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
        $parent_area = isset($_POST['mls_search_parent_area']) ? sanitize_text_field($_POST['mls_search_parent_area']) : '';
		$child_area = isset($_POST['mls_search_child_area']) ? array_map('sanitize_text_field', $_POST['mls_search_child_area']) : [];
		$keyword = sanitize_text_field($_POST['mls_search_keyword']);
        $beds = sanitize_text_field($_POST['mls_search_beds']);
        $baths = sanitize_text_field($_POST['mls_search_baths']);
		$defaultminprice = sanitize_text_field($_POST['defaultminprice']);
		$defaultmaxprice = sanitize_text_field($_POST['defaultmaxprice']);
		$min_prices = sanitize_text_field($_POST['min_price']);
		$max_prices = sanitize_text_field($_POST['max_price']);

		// Set min and max price to empty if they match the default values
		$min_prices = ($min_prices === $defaultminprice) ? '' : $min_prices;
		$max_prices = ($max_prices === $defaultmaxprice) ? '' : $max_prices;

		// Determine min_max_price
		if ($min_prices === '' && $max_prices === '') {
			$min_max_price = 'Select a price range';  // Both values are empty
		} elseif ($min_prices === '') {
			$min_max_price = ' Upto ' . $max_prices; // Only max price is set
		} elseif ($max_prices === '') {
			$min_max_price = ' Starts from ' . $min_prices; // Only min price is set
		} else {
			$min_max_price = $min_prices . ' to ' . $max_prices; // Both values are set
		}

        
        $ownpageresult = isset($_POST['ownpageresult']) ? sanitize_text_field($_POST['ownpageresult']) : 'false';
        $filter_type = isset($_POST['filter_type']) ? sanitize_text_field($_POST['filter_type']) : '';
        $mls_atts_filterid = isset($_POST['mls_atts_filterid']) ? sanitize_text_field($_POST['mls_atts_filterid']) : '';
        $maxthumbnail = isset($_POST['maxthumbnail']) ? sanitize_text_field($_POST['maxthumbnail']) : '';
        $newdevelopment = isset($_POST['newdevelopment']) ? sanitize_text_field($_POST['newdevelopment']) : '';
        $page = isset($_POST['page_num']) ? intval($_POST['page_num']) : 1;
        // $p_sorttype = isset($_POST['p_sorttype']) ? sanitize_text_field($_POST['p_sorttype']) : '';
        $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $_POST['p_sorttype'];
        $includesorttype = isset($_POST['includesorttype']) ? intval($_POST['includesorttype']) : 1;
        $language = isset($_POST['language']) ? intval($_POST['language']) : 1;
		  $mls_search_features_search_type = sanitize_text_field($_POST['mls_search_features_search_type']);
		
        
		$feature_categories = [
    'Setting', 'Orientation', 'Condition', 'Pool', 'Climate', 'Views', 'Features', 
    'Furniture', 'Kitchen', 'Garden', 'Security', 'Parking', 'Utilities', 
    'Category', 'Rentals', 'Plots'
];

$mls_search_features = [];

foreach ($feature_categories as $category) {
    $key = "mls_search_feature_{$category}";
    if (isset($_POST[$key])) {
        foreach ((array)$_POST[$key] as $feature_value) {
            // Extract the label from the HTML structure
            $label = '';
            if (isset($_POST['mls_search_features_labels'][$feature_value])) {
                $label = sanitize_text_field($_POST['mls_search_features_labels'][$feature_value]);
            }
            
            if (!empty($label)) {
                $mls_search_features[$category][$label] = sanitize_text_field($feature_value);
            } else {
                // Fallback if label isn't available
                $mls_search_features[$category][] = sanitize_text_field($feature_value);
            }
        }
    }
}
		
//         print_r($mls_search_features); 
        // Store filter values in session
        $_SESSION['mls_search_filters'] = array(
            'area' => $area,
            'type' => $type,
            'parent_area' => $parent_area,
			'child_area' => $child_area,
			'keyword' => $keyword,
            'beds' => $beds,
            'baths' => $baths,
            'min_price' => $min_prices,
            'max_price' => $max_prices,
			'price' => $min_max_price,
            'filter_type' => $filter_type,
            'filterid' => $mls_atts_filterid,
            'p_sorttype' => $p_sorttype,
            'includesorttype' => $includesorttype,
            'language' => $language,
            'newdevelopment' => $newdevelopment,
            'maxthumbnail' => $maxthumbnail,
			'ownpageresult' => $ownpageresult,
            'page_num' => $page,
			'mls_search_features_search_type' => $mls_search_features_search_type, 
			'additional_features' => $mls_search_features 
        );
		
		if (!isset($query_id)) {
			$query_id = ''; // Default to an empty string or set a fallback value
		}
		
        $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_prices, $max_prices, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment, $mls_search_features_search_type, $mls_search_features,'','',$mls_atts_filterid,'');

        if (isset($data['QueryInfo']['QueryId'])) {
            $query_id = $data['QueryInfo']['QueryId'];
        }

// Detect WPML language directly from the form
$current_lang = isset($_POST['wpml_lang']) ? sanitize_text_field($_POST['wpml_lang']) : 'en';

// Check if WPML is active
if (defined('ICL_SITEPRESS_VERSION') && get_option('mls_plugin_style_proplanghide')) {

    if (function_exists('icl_object_id')) {
        $search_page_id = icl_object_id(7866, 'page', true, $current_lang);
    } else {
        $search_page_id = ($current_lang === 'es') ? 19713 : 7866;
    }

} else {
    $search_page_id = 7866; 
}
		
        // Conditional redirect based on 'ownpageresult' value
        if ($ownpageresult === 'true') {
            // Redirect to the same page (using referer)
            wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id , 'page_num' => $page, 'p_sorttype' => $p_sorttype ), wp_get_referer()));
        } else {
            // Redirect to page ID 931 (replace with your page ID or URL)
            wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id , 'page_num' => $page , 'p_sorttype' => $p_sorttype), get_permalink($search_page_id)));
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
        'propertytypes' => '',
		    'locations' => '',
		    'filterid' => '',
            'pmustfeatures' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_search_results');

    ob_start();
	
	if (isset($_SESSION['mls_search_filters']['ownpageresult'])) { $ownpageresult = $_SESSION['mls_search_filters']['ownpageresult']; }else{$ownpageresult = 'false';}
	
    // Display the search form if 'includesearch' is true
    if ( ($atts['includesearch'] === 'true' &&  !isset($_GET['mls_search_performed'])) || ($atts['includesearch'] === 'true' &&  $ownpageresult === 'false' ) ) {
		
		$newdevelopment = isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1' && isset($_SESSION['mls_search_filters']['newdevelopment']) && $_SESSION['mls_search_filters']['newdevelopment'] !== ''
    ? $_SESSION['mls_search_filters']['newdevelopment']
    : $atts['newdevelopment'];

		
        // Pass relevant attributes to the search shortcode
       $search_output = '<div class="mls-searchresult-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($newdevelopment) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" propertytypes="' . esc_attr($atts['propertytypes']) . '" locations="' . esc_attr($atts['locations']) . '" filterid="' . esc_attr($atts['filterid']) . '" pmustfeatures="' . esc_attr($atts['pmustfeatures']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';
		if($atts['includesearch'] === 'true' &&  !isset($_GET['mls_search_performed'])) { return $search_output; }
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
				$mls_atts_filterid = isset($filters['filterid']) ? $filters['filterid'] : '';
                $maxthumbnail = isset($filters['maxthumbnail']) ? $filters['maxthumbnail'] : '';
                $includesorttype = isset($filters['includesorttype']) ? $filters['includesorttype'] : '';
                $language = isset($filters['language']) ? $filters['language'] : '';
                $newdevelopment = isset($filters['newdevelopment']) ? $filters['newdevelopment'] : '';
				
				if(!get_option('mls_plugin_style_proplanghide') ){ $language = get_option('mls_plugin_prop_language'); }
				else{ $language = isset($filters['language']) ? $filters['language'] : ''; }
				
				// Update option temporarily
     			mls_localize_translations_for_language($language);
                $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $filters['p_sorttype'];
				
                $mls_search_features = isset($filters['additional_features']) ? $filters['additional_features'] : [];
                
                $mls_search_features_search_type = isset($filters['mls_search_features_search_type']) ? $filters['mls_search_features_search_type'] : '';

                // Fetch properties with the stored filters and query ID
                $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_price, $max_price, $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment, $mls_search_features_search_type, $mls_search_features,'','',$mls_atts_filterid,'');

                if ($data) {
                    return '<div class="mls-search-results"><div class="mls-container">' .$search_output . mls_plugin_display_propertiess($data, $maxthumbnail, $includesorttype, $p_sorttype, $language,$filter_type) . '</div></div>';
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
    'propertytypes' => '',
		'locations' => '',
		'filterid' => '',
        'pmustfeatures' => '',
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
        $search_output = '<div class="mls-property-search-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" ownpageresult="' . esc_attr($atts['ownpageresult']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($atts['newdevelopment']) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" propertytypes="' . esc_attr($atts['propertytypes']) . '" locations="' . esc_attr($atts['locations']) . '" filterid="' . esc_attr($atts['filterid']) . '" pmustfeatures="' . esc_attr($atts['pmustfeatures']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';

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
    'propertytypes' => '',
		'locations' => '',
		'filterid' => '',
        'pmustfeatures' => '',
        'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_property_list');

    ob_start();
	

	
    // Handle the 'includesearch' attribute to show or hide the search form
//     $search_output = '';
    if ($atts['includesearch'] === 'true') {
        // Pass relevant attributes to the search shortcode
        $search_output = '<div class="mls-propertylist-search-form">'.do_shortcode('[mls_property_searchformcode filtertype="' . esc_attr($atts['filtertype']) . '" searchtitle="' . esc_attr($atts['searchtitle']) . '" ownpageresult="' . esc_attr($atts['ownpageresult']) . '" maxthumbnail="' . esc_attr($atts['maxthumbnail']) . '" bedsfilter="' . esc_attr($atts['bedsfilter']) . '" bathsfilter="' . esc_attr($atts['bathsfilter']) . '" min_pricefilter="' . esc_attr($atts['min_pricefilter']) . '" max_pricefilter="' . esc_attr($atts['max_pricefilter']) . '" includesorttype="' . esc_attr($atts['includesorttype']) . '" language="' . esc_attr($atts['language']) . '" newdevelopment="' . esc_attr($atts['newdevelopment']) . '" formbackgroundcolor="' . esc_attr($atts['formbackgroundcolor']) . '" formbuttoncolor="' . esc_attr($atts['formbuttoncolor']) . '" propertytypes="' . esc_attr($atts['propertytypes']) . '" locations="' . esc_attr($atts['locations']) . '" filterid="' . esc_attr($atts['filterid']) . '" pmustfeatures="' . esc_attr($atts['pmustfeatures']) . '" p_sorttype="' . esc_attr($atts['p_sorttype']) . '"]').'</div>';

    }
	
	$mls_selected_tabs = get_option('mls_plugin_tabs_to_display', ['sales']);
	if(isset($_SESSION['mls_search_filters']['filter_type']) && isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1'){ $filter_type = $_SESSION['mls_search_filters']['filter_type']; }elseif(!empty($atts['filtertype']) && $atts['filtertype'] != 'sales'){ $filter_type = $atts['filtertype']; }elseif(count($mls_selected_tabs) === 1){ $filter_type = $mls_selected_tabs[0];  }else{ $filter_type = $atts['filtertype']; }
		
    $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $atts['p_sorttype'];
    $includesorttype = isset($atts['includesorttype']) ? sanitize_text_field($atts['includesorttype']) : '1';
	$language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '1';
    $newdevelopment = isset($atts['newdevelopment']) ? sanitize_text_field($atts['newdevelopment']) : '';
    $page = isset($_GET['page_num']) ? intval($_GET['page_num']) : 1;
    $query_id = isset($_GET['query_id']) ? sanitize_text_field($_GET['query_id']) : '';

    $mls_atts_filterid = isset($atts['filterid']) ? $atts['filterid'] : '';
	$mls_atts_propertytypes = ( isset($atts['propertytypes']) && empty($mls_atts_filterid) ) ? $atts['propertytypes'] : '';
    $mls_atts_locations = ( isset($atts['locations']) && empty($mls_atts_filterid) ) ? $atts['locations'] : '';
    $mls_atts_pmustfeatures = ( isset($atts['pmustfeatures']) && empty($mls_atts_filterid) && !isset($_GET['mls_search_performed']) ) ? $atts['pmustfeatures'] : '';

	if(!get_option('mls_plugin_style_proplanghide') ){ $language = get_option('mls_plugin_prop_language'); }
	else{ $language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '1'; }
	
// Update option temporarily
     mls_localize_translations_for_language($language);
	
	$_SESSION['mls_search_filters'] = array( 'newdevelopment' => $newdevelopment );
	
    // Fetch the properties based on search parameters
    $data = mls_plugin_fetch_properties([''],[''],'','','','','', $filter_type, $p_sorttype, $page, $query_id, $language, $newdevelopment,'', [''], $mls_atts_propertytypes, $mls_atts_locations, $mls_atts_filterid, $mls_atts_pmustfeatures);
    
    
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
    
    $newdevelopment = isset($atts['newdevelopment']) ? sanitize_text_field($atts['newdevelopment']) : '';
   $references = (isset($atts['references']) && !empty($atts['references'])) ? sanitize_text_field($atts['references']) : '';
	
	if(!get_option('mls_plugin_style_proplanghide') ){ $language = get_option('mls_plugin_prop_language'); }
	else{ $language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '1'; }
	
// Update option temporarily
    mls_localize_translations_for_language($language);
	
	$_SESSION['mls_search_filters'] = array( 'newdevelopment' => $newdevelopment );
	
	if($references){
    // Fetch the properties based on search parameters
    $data = mls_plugin_fetch_properties([''],[''], $references,'','','','', $filter_type, '', '', '', $language, $newdevelopment,'', [''],'','','','');

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
        'max_pricefilter' => '10000000',
		'p_sorttype' => '1'
    ];

    // Merge provided attributes with defaults
    $atts = shortcode_atts($default_atts, $atts, 'mls_banner_search');
    
	if(!get_option('mls_plugin_style_proplanghide') ){ $language = get_option('mls_plugin_prop_language'); }
	else{ $language = isset($atts['language']) ? sanitize_text_field($atts['language']) : '1'; }
	
	// Update option temporarily
    mls_localize_translations_for_language($language);

	// Set the searchtitle after updating the option
if ($atts['searchtitle'] == 'searchtitle') {
    $atts['searchtitle'] = mls_plugin_translate('labels', 'searchtitle');
}

    $filter_type = $atts['filtertype'];
    $searchtitle = $atts['searchtitle'];
    $maxthumbnail = $atts['maxthumbnail'];
	$includesorttype = $atts['includesorttype'];
    $p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $atts['p_sorttype'];
    $newdevelopment = $atts['newdevelopment'];
    $min_price = $atts['min_pricefilter'];
	$max_price = $atts['max_pricefilter'];
    $min_max_price = $min_price . ' to ' . $max_price;

    // Fetch the property types selected in the settings & Multi-lang
    
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

	// Retrieve filter values from session
    $session_filters = isset($_SESSION['mls_search_filters']) ? $_SESSION['mls_search_filters'] : [];
    $mls_search_performed = isset($_GET['mls_search_performed']) && $_GET['mls_search_performed'] == '1';

	// Check if grouping is enabled and has groups
$grouping_enabled = get_option('mls_plugin_custom_locationgrouping', '0') === '1';
$groups = get_option('mls_location_groups', []);
$locationgrouping = ($grouping_enabled && !empty($groups)) ? 'locationgroupingenabled' : 'locationgroupingdisabled';
	
    // Search form HTML
    ob_start();
    ?>
    <section class="mls-banner-search-wrapper">
        <div class="mls-container">
            <div class="filter-form-wrapper mls-heading mls-main-content mls-banner-search <?php echo esc_attr($locationgrouping); ?>">
                <h4><?php echo esc_attr($searchtitle); ?></h4>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="banner_search_form" class="mls-proplist-search-form mls-form">
                    <input type="hidden" name="action" value="mls_banner_search">
                    <input type="hidden" name="maxthumbnail" value="<?php echo esc_attr($maxthumbnail); ?>">
					<input type="hidden" name="includesorttype" value="<?php echo esc_attr($includesorttype); ?>">
                    <input type="hidden" name="language" value="<?php echo esc_attr($language); ?>">
                    <input type="hidden" name="newdevelopment" value="<?php echo esc_attr($newdevelopment); ?>">
                    <input type="hidden" name="defaultminprice" value="<?php echo esc_attr($min_price); ?>">
	                <input type="hidden" name="defaultmaxprice" value="<?php echo esc_attr($max_price); ?>">
					<input type="hidden" name="wpml_lang" value="<?php echo apply_filters('wpml_current_language', null); ?>">
					<input type="hidden" name="p_sorttype" id="search_form_sorttype" class="search_form_sorttype" value="<?php echo esc_attr($p_sorttype); ?>">
					
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
    <div class="mls-tab-container mls-f-order1">
		<ul id="mls-navtabs1" class="scroll_tabs_theme_light custom-mls-tab">
        <?php foreach ($selected_tabs as $tab): ?>
			<li class="custom-mls-tab-list">
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
	<!-- 	Parent & child locations	 -->
		
		<?php
// Get current selections from session
$selected_parent = ($mls_search_performed && $session_filters['parent_area']) ? $session_filters['parent_area'] : '';
$selected_children = ($mls_search_performed && $session_filters['child_area']) ? $session_filters['child_area'] : [];

if ($grouping_enabled && !empty($groups) && !$mls_atts_filterid && !$mls_atts_locations ): ?>
    <div class="mls-form-group mls-f-order2 mls-location-group-container"
         data-groups='<?php echo wp_json_encode($groups); ?>'
         data-selected-parent='<?php echo esc_attr($selected_parent); ?>'
         data-selected-children='<?php echo wp_json_encode($selected_children); ?>'
         data-translate-select-parent='<?php echo esc_attr(mls_plugin_translate("labels","select_parent_area")); ?>'
         data-translate-select-child='<?php echo esc_attr(mls_plugin_translate("labels","select_child_area")); ?>'
         data-translate-please-select='<?php echo esc_attr(mls_plugin_translate("labels","please_select_child_area")); ?>'>
        <div>
        <label for="mls_search_parent_area"><?php echo mls_plugin_translate('labels','area'); ?></label>
        <select id="mls_search_parent_area" name="mls_search_parent_area" class="mls_parent_area_sel sel-app">
            <option value=""><?php echo mls_plugin_translate('placeholders','search_area'); ?></option>
            <?php
            foreach ($groups as $group) {
                $parent = $group['parent'];
                $selected = ($selected_parent === $parent) ? 'selected' : '';
                echo '<option value="' . esc_attr($parent) . '" ' . esc_attr($selected) . '>' . esc_html($parent) . '</option>';
            }
            ?>
        </select>
        </div>
        <div>
        <label for="mls_search_child_area"><?php echo mls_plugin_translate('labels','child_area'); ?></label>
        <select id="mls_search_child_area" name="mls_search_child_area[]" class="mls_child_area_sel mls-multiselect mls-ms-arw" multiple>
            <option value=""><?php echo mls_plugin_translate('placeholders','all_child_area'); ?></option>
            <?php
            // Pre-populate child options if parent is already selected
            if ($selected_parent) {
                foreach ($groups as $group) {
                    if ($group['parent'] === $selected_parent) {
                        foreach ($group['children'] as $child) {
                            $child_selected = in_array($child, $selected_children) ? 'selected' : '';
                            echo '<option value="' . esc_attr($child) . '" ' . esc_attr($child_selected) . '>' . esc_html($child) . '</option>';
                        }
                        break;
                    }
                }
            }
            ?>
        </select>
        </div>
    </div>

    <!-- Hidden field to store the original area values for form submission -->
    <input type="hidden" id="mls_search_area_hidden" name="mls_search_area[]" value="<?php echo esc_attr(implode(',', (array) $selected_children)); ?>">
<?php else: ?>
					
    <div class="mls-form-group mls-f-order2">
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
<?php endif; ?>					
					
		<div class="mls-form-group mls-f-order3">

        <label for="mls_search_type"><?php echo mls_plugin_translate('labels','property_type'); ?></label>
        
            <?php
	
	echo '<select id="mls_search_type_ban" name="mls_search_type[]" class="mls_type_sel" multiple>';
            if (isset($property_types['PropertyTypes']) && is_array($property_types['PropertyTypes'])) {        
        foreach ($property_types['PropertyTypes']['PropertyType'] as $type) {
            $option_value = $type['OptionValue'];  // The value attribute
            $option_label = $type['Type'];
$selected = (in_array($option_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option value="' . esc_attr($option_value) . '" ' . esc_attr($selected) . ' data-parent="" >' . esc_html($option_label) . '</option>';
            $with_subtypes = false;

            if (isset($type['SubType']) && count($type['SubType']) > 0) {
                $with_subtypes = true;
            }

            if ($with_subtypes) { 
                foreach ($type['SubType'] as $subtype) {
                    $suboption_value = $subtype['OptionValue'];  // The value attribute
                    $suboption_label = $subtype['Type'];
$selected = (in_array($suboption_value, $session_filters['type'] ?? []) && $mls_search_performed) ? 'selected' : '';
echo '<option data-parent="' . esc_attr($option_label) . '" value="' . esc_attr($suboption_value) . '" ' . esc_attr($selected) . '>' . esc_html($suboption_label) . '</option>';
                   
                }
            }
        }
    }else { echo mls_plugin_translate("labels","no_property_type"); } 
	 echo '</select>';
            ?>
        

        </div><div class="mls-form-group mls-f-order4">

        <label for="mls_search_keyword"><?php echo mls_plugin_translate('labels','reference_id'); ?></label>
        <!-- <input type="text" id="mls_search_keyword" value="<?php echo esc_attr($session_filters['keyword'] ?? ''); ?>" name="mls_search_keyword" /> -->
        <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','search_reference_id'); ?>" id="mls_search_keyword_ban" 
        value="<?php echo esc_attr(($mls_search_performed && !empty($session_filters['keyword'])) ? $session_filters['keyword'] : ''); ?>" 
        name="mls_search_keyword" />

        </div>
			
					<div class="mls-form-group mls-f-order6">
    <label for="mls_search_beds"><?php echo mls_plugin_translate('labels','beds'); ?></label>
    <select id="mls_search_beds" name="mls_search_beds" class="sel-app">
        <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
        <?php echo wp_kses( generate_bed_bath_options($atts['bedsfilter'], '+', ($mls_search_performed && !empty($session_filters['beds'])) ? $session_filters['beds'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
    </select>
</div>

<div class="mls-form-group mls-f-order7">
    <label for="mls_search_baths"><?php echo mls_plugin_translate('labels','baths'); ?></label>
    <select id="mls_search_baths" name="mls_search_baths" class="sel-app">
        <option value=""><?php echo mls_plugin_translate('options','any'); ?></option>
        <?php echo wp_kses( generate_bed_bath_options($atts['bathsfilter'], '+', ($mls_search_performed && !empty($session_filters['baths'])) ? $session_filters['baths'] : ''), array( 'option' => array( 'value' => array(), 'selected' => array() ) ) ); ?>
    </select>
</div>


<div class="mls-form-group mls-f-order8">
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
		<div class="pr-btn-wrapper"><button class="price-range-reset"><?php echo mls_plugin_translate('buttons','reset'); ?></button> <button class="price-range-done" onclick="return false;"><?php echo mls_plugin_translate('buttons','done'); ?></button></div>
    </div>
</div>
<div class="mls-search-features-dropdown">
    <div class="mls-feat-dropdown-top"><h4><?php echo mls_plugin_translate('general','additionalfeatures'); ?></h4><span class="mls-af-close">×</span></div>
    <div class="mls-feat-top-search">
	<div class="mls-feat-top-search-col1">
	

    <div class="mls-form-group mls-fts-sel">
        <select id="mls_search_features_search_type" name="mls_search_features_search_type" class="sel-app">
            <option disabled><?php echo mls_plugin_translate('labels','feature_search_type'); ?></option>
            <option value="2" <?php echo ($mls_search_performed && isset($session_filters['mls_search_features_search_type']) && $session_filters['mls_search_features_search_type'] == '2') ? 'selected' : ''; ?>>
                <?php echo mls_plugin_translate('options','musthavefeatures2'); ?>
            </option>
            <option value="1" <?php echo ($mls_search_performed && isset($session_filters['mls_search_features_search_type']) && $session_filters['mls_search_features_search_type'] == '1') ? 'selected' : ''; ?>>
                <?php echo mls_plugin_translate('options','musthavefeatures1'); ?>
            </option>            
        </select>
    </div>
		</div>
	<div class="mls-feat-top-search-col2">
		<div class="mls-form-group">
        <input type="submit" name="mls_search_features_resetbtn" value="<?php echo mls_plugin_translate('buttons','reset'); ?>" >
		<input type="submit" class="done-btn"  name="mls_search_features_donebtn" value="<?php echo mls_plugin_translate('buttons','done'); ?>" >
        </div>
		</div>
		
    </div>

	<?php echo mls_plugin_cached_searchfeatures_multilang($language, ''); ?>
</div>

					

		<div class="mls-form-group mls-f-order9">
                        <input type="submit" name="mls_banner_search_submit" value="<?php echo mls_plugin_translate('buttons','search_properties'); ?>" />
                    </div>
	<div class="mls-form-group mls-add-feat-btn-wrapper mls-f-order5">
          <button type="button" class="mls-add-feat-btn"><img src="<?php echo esc_url( plugins_url('assets/images/filter_light.png', __DIR__) ); ?>" alt="" /> <?php echo mls_plugin_translate('buttons','additionalfeatures'); ?></button>
</div>
                </form>

                <div class="mls-selected-labels">
			<div class="mls_selected_features">
				<?php if($mls_search_performed && isset($session_filters['additional_features'])){
				foreach($session_filters['additional_features'] as $feature_key => $feature_values){
					if (!empty($feature_values) && is_array($feature_values)) {
            foreach ($feature_values as $key => $value) {
                if (!empty($key)) { // Ensure the value is not empty
                    echo '<span>'. $key .'</span>';
                }
            }
        }
				}
			} ?>
			</div>
		</div>
        
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
		$parent_area = isset($_POST['mls_search_parent_area']) ? sanitize_text_field($_POST['mls_search_parent_area']) : '';
		$child_area = isset($_POST['mls_search_child_area']) ? array_map('sanitize_text_field', $_POST['mls_search_child_area']) : [];
        $keyword = sanitize_text_field($_POST['mls_search_keyword']);
        $beds = sanitize_text_field($_POST['mls_search_beds']);
        $baths = sanitize_text_field($_POST['mls_search_baths']);
        
        $filter_type = isset($_POST['filter_type']) ? sanitize_text_field($_POST['filter_type']) : '';
        $newdevelopment = isset($_POST['newdevelopment']) ? sanitize_text_field($_POST['newdevelopment']) : '';
        $maxthumbnail = isset($_POST['maxthumbnail']) ? sanitize_text_field($_POST['maxthumbnail']) : '';
		$includesorttype = isset($_POST['includesorttype']) ? intval($_POST['includesorttype']) : 1;
        $language = isset($_POST['language']) ? intval($_POST['language']) : 1;
		$p_sorttype = isset($_GET['p_sorttype']) ? sanitize_text_field($_GET['p_sorttype']) : $_POST['p_sorttype'];
        $page = 1; // Always start at page 1 for new searches

        $defaultminprice = sanitize_text_field($_POST['defaultminprice']);
		$defaultmaxprice = sanitize_text_field($_POST['defaultmaxprice']);
		$min_prices = sanitize_text_field($_POST['mls_search_minprice']);
        $max_prices = sanitize_text_field($_POST['mls_search_maxprice']);

		// Set min and max price to empty if they match the default values
		$min_prices = ($min_prices === $defaultminprice) ? '' : $min_prices;
		$max_prices = ($max_prices === $defaultmaxprice) ? '' : $max_prices;

		// Determine min_max_price
		if ($min_prices === '' && $max_prices === '') {
			$min_max_price = 'Select a price range';  // Both values are empty
		} elseif ($min_prices === '') {
			$min_max_price = ' Upto ' . $max_prices; // Only max price is set
		} elseif ($max_prices === '') {
			$min_max_price = ' Starts from ' . $min_prices; // Only min price is set
		} else {
			$min_max_price = $min_prices . ' to ' . $max_prices; // Both values are set
		}

// 		Additional features
$mls_search_features_search_type = sanitize_text_field($_POST['mls_search_features_search_type']);
		
        
        /*$feature_categories = [
            'Setting', 'Orientation', 'Condition', 'Pool', 'Climate', 'Views', 'Features', 
            'Furniture', 'Kitchen', 'Garden', 'Security', 'Parking', 'Utilities', 
            'Category', 'Rentals', 'Plots'
        ];
        
        $mls_search_features = [];
        
        foreach ($feature_categories as $category) {
            $key = "mls_search_feature_{$category}";
            $mls_search_features[$category] = isset($_POST[$key]) ? array_map('sanitize_text_field', (array) $_POST[$key]) : [];
        }*/

        $feature_categories = [
            'Setting', 'Orientation', 'Condition', 'Pool', 'Climate', 'Views', 'Features', 
            'Furniture', 'Kitchen', 'Garden', 'Security', 'Parking', 'Utilities', 
            'Category', 'Rentals', 'Plots'
        ];
        
        $mls_search_features = [];
        
        foreach ($feature_categories as $category) {
            $key = "mls_search_feature_{$category}";
            if (isset($_POST[$key])) {
                foreach ((array)$_POST[$key] as $feature_value) {
                    // Extract the label from the HTML structure
                    $label = '';
                    if (isset($_POST['mls_search_features_labels'][$feature_value])) {
                        $label = sanitize_text_field($_POST['mls_search_features_labels'][$feature_value]);
                    }
                    
                    if (!empty($label)) {
                        $mls_search_features[$category][$label] = sanitize_text_field($feature_value);
                    } else {
                        // Fallback if label isn't available
                        $mls_search_features[$category][] = sanitize_text_field($feature_value);
                    }
                }
            }
        }

		
        // Store filter values in session
        $_SESSION['mls_search_filters'] = array(
            'area' => $area,
            'type' => $type,
			'parent_area' => $parent_area,
			'child_area' => $child_area,
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
			'p_sorttype' => $p_sorttype,
            'page_num' => $page,
			'mls_search_features_search_type' => $mls_search_features_search_type, 
			'additional_features' => $mls_search_features 
        );

		if (!isset($query_id)) {
			$query_id = ''; // Default to an empty string or set a fallback value
		}
		
        $data = mls_plugin_fetch_properties($area, $type, $keyword, $beds, $baths, $min_prices, $max_prices, $filter_type, $p_sorttype , $page, $query_id, $language, $newdevelopment, $mls_search_features_search_type, $mls_search_features,'','','','');

        if (isset($data['QueryInfo']['QueryId'])) {
            $query_id = $data['QueryInfo']['QueryId'];
        }

// Detect WPML language directly from the form
$current_lang = isset($_POST['wpml_lang']) ? sanitize_text_field($_POST['wpml_lang']) : 'en';

// Check if WPML is active
if (defined('ICL_SITEPRESS_VERSION') && get_option('mls_plugin_style_proplanghide')) {

    if (function_exists('icl_object_id')) {
        $search_page_id = icl_object_id(7866, 'page', true, $current_lang);
    } else {
        $search_page_id = ($current_lang === 'es') ? 19713 : 7866;
    }

} else {
    $search_page_id = 7866; 
}
		
        // Redirect to the search results page (replace 7866 with your actual search results page ID)
        wp_redirect(add_query_arg(array('mls_search_performed' => '1', 'query_id' => $query_id, 'page_num' => $page), get_permalink($search_page_id)));
        exit;
    }
}
add_action('admin_post_mls_banner_search', 'mls_plugin_handle_banner_form_submission');
add_action('admin_post_nopriv_mls_banner_search', 'mls_plugin_handle_banner_form_submission');