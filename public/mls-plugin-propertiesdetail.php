<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!defined('CAL_GREGORIAN')) {
    define('CAL_GREGORIAN', 0);
}

if (!function_exists('cal_days_in_month')) {
    function cal_days_in_month($calendar, $month, $year) {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}

function mls_property_details_shortcode() {
	
	$weblinkstructure = get_option('mls_plugin_weblink_structure', 'weblink_advanced');
	if ($weblinkstructure == 'weblink_advanced') { $property_ref = get_query_var('property_ref'); }
	else{ $property_ref = isset($_GET['id']) ? $_GET['id'] : ''; }
    
    $property_type = isset($_GET['type']) ? $_GET['type'] : '';	
	$property_lang = isset($_GET['lang']) ? $_GET['lang'] : '';
	$view_more_url = home_url( add_query_arg( [], $_SERVER['REQUEST_URI'] ) );
	
	// Update option temporarily
    update_option('mls_temp_language_code', $property_lang);
	
	// Map property types to their corresponding filters
$filter_map = [
    mls_plugin_translate('labels','for_sale') ?? 'For Sale' => get_option('mls_plugin_filter_id_sales'),
    mls_plugin_translate('labels','for_rent') ?? 'For Rent' => get_option('mls_plugin_filter_id_short_rentals'),
    mls_plugin_translate('labels','for_rent') ?? 'For Rent' => get_option('mls_plugin_filter_id_long_rentals'),
    mls_plugin_translate('labels','featured') ?? 'Featured' => get_option('mls_plugin_filter_id_features'),
];
	
	if(isset($_SESSION['mls_search_filters']['newdevelopment']) ){ $newdevelopment = $_SESSION['mls_search_filters']['newdevelopment']; }
    if (!$property_ref || !$property_type) {
        return '<div class="search-not-perform"><p>'.mls_plugin_translate('error','mls_propertdetail_invalid_found') .'</p></div>';
    }
// echo $property_ref . "<br>";
// 	echo $property_type . "<br>";
    // Fetch property details using the reference and type
    $properties = mls_plugin_fetch_ref($property_ref, $property_type, $property_lang, $newdevelopment);
	$property_details = $properties['Property'];
    if (!$property_details) {
        return '<div class="search-not-perform"><p>'.mls_plugin_translate('error','mls_propertdetail_not_found') .'</p></div>';
    }
// 	else{
// 		return '<pre>' . print_r($properties, true) . '</pre>';
// 	}

	
    // Start output buffering
    ob_start();
    ?>
<section class="mls-parent-wrapper">
    <div class="mls-prj-details mls-main-content">
       <div class="mls-container">
		   <?php  $breadcrumbhide = get_option('mls_plugin_style_breadcrumbhide', '1'); 
		   if($breadcrumbhide) { $breadcrumbhide="mlsbc-hide";}else{$breadcrumbhide="";} ?>
          <div class="mls-breadcrumb <?php echo $breadcrumbhide; ?>">
             <?php  mls_plugin_breadcrumb();  ?>
          </div>
		   <div class="mls-pagination-btns-block">
    <div class="mls-pagi-btns-left">
        <div>
            <?php
	 $current_page_url = get_permalink();
            // Check if there's a referrer (previous page)
            if (isset($_SERVER['HTTP_REFERER']) && !is_same_page($_SERVER['HTTP_REFERER'], $current_page_url)) {
   $previous_page_url = $_SERVER['HTTP_REFERER'];

            ?>
                <a href="<?php echo esc_url($previous_page_url); ?>" class="mls-button">
                    <img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" /><?php echo mls_plugin_translate('buttons','back_previous'); ?></a>
            <?php
            } else {
				$previous_page_url = home_url();
                // Fallback if no referrer is found
            ?>
                <a href="<?php echo esc_url($previous_page_url); ?>" class="mls-button">
                    <img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" /><?php echo mls_plugin_translate('buttons','back_home'); ?></a>
            <?php
            }
            ?>
        </div>
    </div>

</div>

          <div class="mls-prj-detail-full mls-heading">
             <div class="mls-prj-left">
                 <div class="mls-prj-section mls-prj-lay mls-prjs1">
                     <div class="mls-prjs1-top">
                         <div class="mls-prjs1-top-left">
                            <div class="mls-prj-title">
								<?php if (in_array($property_type, $filter_map)) {
    // Loop through and find the matching property type
    foreach ($filter_map as $mlsrentkey => $value) {
        if ($property_type == $value) {
			echo '<div class="mls-pyc-left"><span>'. $mlsrentkey .'</span></div>';
            break;
        }
    }
} ?>
                                <?php $prptitle = $property_details['PropertyType']['NameType'] . ' ' . (mls_plugin_translate('general','in') ?? 'in') . ' ' . $property_details['Location']  . ', ' . $property_details['Area'] . ', ' . $property_details['Country']; 
//                                 $prpfulllocation = $property_details['Location']  . ', ' . $property_details['Area'] . ', ' . $property_details['Country'];
	$prpfulllocation = $property_details['Location']  . ', ' . $property_details['Province'] ;
                                $prplocation = $property_details['Location'];
								$prpgeoX = isset($property_details['GpsX']) ? $property_details['GpsX'] : null;
								$prpgeoY = isset($property_details['GpsY']) ? $property_details['GpsY'] : null;
								$prpVirtualTour = isset($property_details['VirtualTour']) ? $property_details['VirtualTour'] : null;
								$prpVideoTour = isset($property_details['VideoTour']) ? $property_details['VideoTour'] : null;
								$rentalpriceperiod = $property_details['RentalPeriod'] ?? mls_plugin_translate('general','month'); 
								$prpdescription = $property_details['Description'];
								$prpfirstimage = $property_details['Pictures']['Picture'][0]['PictureURL'];
                                ?>

                               <h2><?php echo esc_html($prptitle); ?></h2>
                               <p class="mls-pyc-d-loc"><img src="<?php echo esc_url(plugins_url('assets/images/map-pin.png', __DIR__)); ?>"> <?php echo esc_html($property_details['Area'] . ', ' . $property_details['Country']); ?></p>
                            </div> 
                         </div>
                     </div>
                   <div class="mls-prj-img">
                        <div class="light-gallery-wrapper">
                            <div class="mls-project-slider" id="lightgallery">
                                <?php if (isset($property_details['Pictures']['Picture'])): ?>
                                    <?php foreach ($property_details['Pictures']['Picture'] as $picture): ?>
                                <div class="mls-project-li-wrapper" data-src="<?php echo esc_url($picture['PictureURL']); ?>"><img src="<?php echo esc_url($picture['PictureURL']); ?>" alt="Property Image" /></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="mls-project-nav-slider">
                                <?php if (isset($property_details['Pictures']['Picture'])): ?>
                                    <?php foreach ($property_details['Pictures']['Picture'] as $picture): ?>
                                <div class="mls-project-nav-wrapper"><img src="<?php echo esc_url($picture['PictureURL']); ?>" alt="Property Image" /></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                   </div>
                     <div class="mls-prjs1-bottom">
                        <div class="mls-share-social-pd">
                            <span class="mls-ss-title"><?php echo mls_plugin_translate('general','share'); ?></span> <?php echo wp_kses_post(social_share_function($view_more_url)); ?>
                        </div>
						 <div class="mls-virtual-btn">
							 <?php if($prpVirtualTour){ ?> <a href="javascript:void(0);" id="open-virtual-tour" class="mls-button"><i class="fa-solid fa-vr-cardboard"></i> <?php echo mls_plugin_translate('buttons','virtual_tour'); ?></a><?php } ?> 
							<?php if($prpVideoTour){ ?> <a href="javascript:void(0);" id="open-video-tour" class="mls-button"><i class="fa-solid fa-video"></i> <?php echo mls_plugin_translate('buttons','video_tour'); ?></a><?php } ?> 
						 </div>
                     </div>
                 </div>
			   <?php if($prpVirtualTour){ ?>
				<div id="open-virtual-tour-pop" style="display: none;">
					<iframe src="<?php echo $prpVirtualTour; ?> " width="560" height="315" frameborder="0" allow="fullscreen; accelerometer; gyroscope; magnetometer; vr; xr; xr-spatial-tracking; autoplay; camera; microphone" data-iframe="true" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" oallowfullscreen="true" msallowfullscreen="true"></iframe>
				 </div>
				 <?php } if($prpVideoTour){ ?> 
				<div id="open-video-tour-pop" style="display: none;">
					<iframe width="560" height="315" src="<?php echo $prpVideoTour; ?> " frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
				 </div>
				 <?php } ?> 
				 
                 <div class="mls-prj-section mls-prj-lay mls-prjs5 show-xs">
                    <div class="cn-post">
                        <div class="mls-prj-price cn-area">
                           <div><a href="#contact-from" class="mls-button"><?php echo mls_plugin_translate('buttons','book_viewing'); ?></a></div>                            			<?php $price = $property_details['Price'];
                            if(!$price){ $price = $property_details['RentalPrice1']; }?>
                            <h3><small><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?></small> <?php echo esc_html(format_prices($price)); if ($mlsrentkey === mls_plugin_translate('labels','for_rent') ) { echo '<span>/'. $rentalpriceperiod .'</span>'; }?></h3>
                        </div>
                    </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs2">
                    <div class="mls-prj-feature">
        <?php if (!empty($property_details['Bedrooms']) && $property_details['Bedrooms'] != 0): ?>
            <div>
                <div class="mls-pycf-t"><?php echo mls_plugin_translate('specification','bedrooms'); ?></div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/bed.png', __DIR__)); ?>" alt="Bedrooms" /> 
                    <span><?php echo esc_html($property_details['Bedrooms']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Bathrooms']) && $property_details['Bathrooms'] != 0): ?>
            <div>
                <div class="mls-pycf-t"><?php echo mls_plugin_translate('specification','bathrooms'); ?></div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/bathtub.png', __DIR__)); ?>" alt="Bathrooms" />
                    <span><?php echo esc_html($property_details['Bathrooms']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Built']) && $property_details['Built'] != 0): ?>
            <div>
                <div class="mls-pycf-t"><?php echo mls_plugin_translate('specification','built'); ?></div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/angle.png', __DIR__)); ?>" alt="Built Area" /> 
                    <span><?php echo esc_html($property_details['Built']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['GardenPlot']) && $property_details['GardenPlot'] != 0): ?>
            <div>
                <div class="mls-pycf-t"><?php echo mls_plugin_translate('specification','garden'); ?></div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/garden.png', __DIR__)); ?>" alt="Garden Plot" /> 
                    <span><?php echo esc_html($property_details['GardenPlot']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Terrace']) && $property_details['Terrace'] != 0): ?>
            <div>
                <div class="mls-pycf-t"><?php echo mls_plugin_translate('specification','terrace'); ?></div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/outdoor.png', __DIR__)); ?>" alt="Terrace" /> 
                    <span><?php echo esc_html($property_details['Terrace']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5 show-xs">
                               <h4><?php echo mls_plugin_translate('prp_highlights','property_highlights'); ?></h4>
                               <div class="ltst-pst">
                    <?php if (!empty($property_details['Reference'])): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','referenceid'); ?></h4>
                            <p><?php echo esc_html($property_details['Reference']); ?></p>
                        </div>
                    <?php endif; ?>

                    

                    <?php if (!empty($property_details['Location'])): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','location'); ?></h4>
                            <p><?php echo esc_html($property_details['Location']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Area'])): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','area'); ?></h4>
                            <p><?php echo esc_html($property_details['Area']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Country'])): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','country'); ?></h4>
                            <p><?php echo esc_html($property_details['Country']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['PropertyType']['NameType'])): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','property_type'); ?></h4>
                            <p><?php echo esc_html($property_details['PropertyType']['NameType']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Basura_Tax_Year']) && $property_details['Basura_Tax_Year'] != 0): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','garbage_fees'); ?></h4>
                            <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['Basura_Tax_Year']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['IBI_Fees_Year']) && $property_details['IBI_Fees_Year'] != 0): ?>
                        <div class="ltst">
                            <h4><?php echo mls_plugin_translate('prp_highlights','ibi_fees'); ?></h4>
                            <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['IBI_Fees_Year']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5">
                     <h4><?php echo mls_plugin_translate('general','features'); ?></h4>
                     <div class="mls-prj-moredetail">
                         <ul>
            <?php $propertyFeatures = $property_details['PropertyFeatures'];
            if($propertyFeatures['Category']){
            foreach ($propertyFeatures['Category'] as $feature): ?>
                <li>
                    <span class="mls-prjmd-title"><?php echo esc_html($feature['Type']); ?></span>
    <span class="mls-prjmd-cnt"><?php echo esc_html(implode(', ', $feature['Value'])); ?></span>
                </li>
            <?php endforeach; 
            }else{ echo "<p>".mls_plugin_translate('error','mls_propertdetail_no_features')."</p>"; }
            ?>
        </ul>
                     </div>
                </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs3">
                     <h4><?php echo mls_plugin_translate('general','description'); ?></h4>
                     <div class="mls-prj-content">
                      <?php echo wp_kses_post( wpautop( $property_details['Description'] ) ); ?>
                     </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs4">
                     <?php 
        $base_url = plugins_url('assets/images/', __DIR__);

    $amenities = [
        'Electricity' => [
            'img' => $base_url . 'batteryy.png',
            'display' => 'Power Back Up'
        ],
        'Lift' => [
            'img' => $base_url . 'elevatorr.png',
            'display' => 'Lifts'
        ],
        'Pool' => [
            'img' => $base_url . 'swimmingg.png',
            'display' => 'Swimming Pool'
        ],
        'Gym' => [
            'img' => $base_url . 'treadmilll.png',
            'display' => 'Gymnasium'
        ],
        'Parking' => [
            'img' => $base_url . 'car-parkingg.png',
            'display' => 'Reserved Parking'
        ],
        'Security' => [
            'img' => $base_url . 'guardd.png',
            'display' => 'Security Services'
        ],
        'CCTV' => [  // Note: Use a unique key for each amenity
            'img' => $base_url . 'cctvv.png',
            'display' => 'CCTV Surveillance'
        ],
        'Garden' => [
            'img' => $base_url . 'playgroundd.png',
            'display' => 'Children’s Play Area'
        ],
        'Staff Accommodation' => [
            'img' => $base_url . 'guardd.png',
            'display' => 'Maintenance Crew'
        ]
    ];
        $matchingAmenities = [];

        $propertyFeatures = $property_details['PropertyFeatures']; 
        foreach ($propertyFeatures['Category'] as $feature) {
        foreach ($feature['Value'] as $value) {
            if (array_key_exists($value, $amenities)) {
                $matchingAmenities[$value] = $amenities[$value]; // Add matching amenity to the array
            }
        }
    }
        ?>
                     <h4><?php echo mls_plugin_translate('general','amenities'); ?></h4>
                    <div class="mls-prj-amenities">
            <?php if (!empty($matchingAmenities)): ?>
                <?php foreach ($matchingAmenities as $amenityName => $amenityData): ?>
                    <div>
                        <div class="mls-prj-amen">
                            <span class="amen-img">
                                <img src="<?php echo esc_url($amenityData['img']); ?>" alt="" />
                            </span>
                            <span><?php echo esc_attr($amenityData['display']); ?></span> <!-- Custom display name -->
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo mls_plugin_translate('error','mls_propertdetail_no_amenities'); ?></p>
            <?php endif; ?>
        </div>
                 </div>
                 <?php $maphide = get_option('mls_plugin_maphide'); if(!$maphide){ ?>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5">
                     <h4><?php echo mls_plugin_translate('prp_map','property_location'); ?></h4>
					 <p class="mls-prj-section-subtitle"><?php echo mls_plugin_translate('prp_map','property_location_descp'); ?></p>
                     <?php $map_provider = get_option('mls_plugin_map_provider', 'openstreetmap');

                        // Check if OpenStreetMap is selected
                        if ($map_provider === 'openstreetmap') {
// Mapping array to convert locations
$prplocation_mapping = [
    "Calahonda" => [
        "name" => "Sitio de Calahonda",
    ],
    "La Quinta" => [
        "lng" => 36.516720,
        "lat" => -4.997039
    ],
    "New Golden Mile" => [
        "name" => "Urb. Coto de la Serena"
    ],
    "The Golden Mile" => [
        "name" => "El Vicario"
    ]
];

// Check if $prplocation exists in the mapping array and replace it
if (array_key_exists($prplocation, $prplocation_mapping)) {
    $location_data = $prplocation_mapping[$prplocation];
    
    // Update location name if 'name' exists
    if (isset($location_data['name'])) {
        $prplocation = $location_data['name'];
    }
    
    // Update coordinates if they exist
    if (isset($location_data['lat']) && isset($location_data['lng'])) {
        $prpgeoX = $location_data['lat'];
        $prpgeoY = $location_data['lng'];
    }
}
							
                        mls_plugin_display_map($prptitle, $prpgeoX, $prpgeoY, $prplocation); 
                        } ?>
                 </div>
				 <?php } ?>
                 <div class="mls-prj-section mls-prj-lay mls-prjs6" id="contact-from">
                     <h4><?php echo get_option("mls_plugin_leadformheading"); ?></h4>
					  <?php $videohide = get_option('mls_plugin_leadformvideohide');
	$scheduledatehide = get_option('mls_plugin_leadformscheduledatehide');
	$langhide = get_option('mls_plugin_leadformlanghide');
	$buyersellerhide = get_option('mls_plugin_leadformbuyersellerhide');
							 if($videohide){ $videohidecls ="videohidecls"; }else{$videohidecls="";}
					 $options = [
    'videohide' => 'videohidecls',
    'scheduledatehide' => 'date-time-hide',
    'langhide' => 'lang-hide',
    'buyersellerhide' => 'bsa-hide',
];

$classes = [];
foreach ($options as $option_key => $class_name) {
    $option_value = get_option("mls_plugin_leadform{$option_key}");
    $classes[$class_name] = $option_value ? $class_name : '';
}

// Example usage
$videohidecls = $classes['videohidecls'];
$scheduledatehidecls = $classes['date-time-hide'];
$langhidecls = $classes['lang-hide'];
$buyersellerhidecls = $classes['bsa-hide'];

					 ?>
                   <div class="mls-form <?php echo $videohidecls . " "; echo $scheduledatehidecls . " "; echo $langhidecls . " "; echo $buyersellerhidecls . " "; ?>">
                         <form id="mls-lead-form" method="POST">
                             <input type="hidden" name="action" value="mls_plugin_lead_form">
                             <input type="hidden" name="property_ref" value="<?php echo esc_attr($property_details['Reference']); ?>">
                             <?php
// Get the current year, month, and day
$currentYear = (int) date('Y');
$currentMonth = (int) date('n'); // Numeric representation of month (1-12)
$currentDay = (int) date('j');  // Current day of the month

// Generate years dynamically (current year + next 1 year)
$years = range($currentYear, $currentYear + 1);

// Generate months dynamically
$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December',
];

if (!$scheduledatehidecls) {
?>

<div class="mls-form-group mls-c2-fix month-field dmy custom-select">
    <select name="month" id="month-select" onchange="updateDateSlider()">
        <option value="">Please select the Month</option>
        <?php foreach ($months as $key => $monthName): ?>
            <option value="<?php echo $key; ?>" <?php echo ($key === $currentMonth) ? 'selected' : ''; ?>>
                <?php echo esc_html($monthName); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mls-form-group mls-c2-fix year-field dmy custom-select">
    <select name="year" id="year-select" onchange="updateDateSlider()">
        <option value="">Please select the Year</option>
        <?php foreach ($years as $year): ?>
            <option value="<?php echo $year; ?>" <?php echo ($year === $currentYear) ? 'selected' : ''; ?>>
                <?php echo esc_html($year); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mls-form-group mls-c1 date-field dmy">
    <div class="schedule-date-slider" id="date-slider">
        <?php
        // Loop through dates for the current month and year by default
        $selectedMonth = $currentMonth;
        $selectedYear = $currentYear;

        // Start from the current day for the current month and year
        $startDay = ($selectedMonth === $currentMonth && $selectedYear === $currentYear) ? $currentDay : 1;
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = DateTime::createFromFormat('Y-n-j', "$selectedYear-$selectedMonth-$day");

            $dayName = $date->format('D'); // Day name (e.g., Mon, Tue)
            $dayNum = $date->format('d');  // Day number (e.g., 01, 02)
            $month = $date->format('M');   // Short month name (e.g., Jan)
            $year = $date->format('Y');
            $formattedDate = $date->format('d/m/Y');
            $dayFullName = $date->format('l'); // Full day name
            $fullvalue = $formattedDate . ' (' . $dayFullName . ')';
   			$pastdayscls = ($day < $startDay) ? 'pastdayscls' : '';
			$currentdaycls = ($day == $currentDay && $selectedMonth === $currentMonth && $selectedYear === $currentYear) ? 'currentdaycls' : '';
        ?>
            <div class="<?php echo $pastdayscls. ' ' . $currentdaycls; ?>">
                <div class="property-schedule-singledate-wrapper">
                    <input type="radio" id="" name="scheduledate" value="<?php echo esc_attr($fullvalue); ?>">
                    <div>
                        <span class="day-name"><?php echo esc_html($dayName); ?></span>
                        <span class="day-num"><?php echo esc_html($dayNum); ?></span>
                        <span class="day-month"><?php echo esc_html($month); ?></span>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <span class="error-message" id="scheduledateError"></span>
</div>

<?php 
} else { 
    echo '<div class="mls-form-group mls-c1 date-field dmy"><input type="radio" id="" name="scheduledate" value="-" checked /></div>'; 
} 
?>

							 
                             <div class="mls-form-group mls-c3 time-field custom-select">
                               <?php mls_plugin_display_available_timings(); ?>
                             </div>
							 <div class="mls-form-group mls-c3 lang-field custom-select">
                               <?php mls_plugin_display_language_options(); ?>
                             </div>
                             <div class="mls-form-group mls-c3 bsa-field custom-select">
                                <select name="buyerseller" id="buyerseller" >
                                    <option><?php echo mls_plugin_translate('placeholders','buyer'); ?></option>
                                    <option><?php echo mls_plugin_translate('placeholders','agent'); ?></option>
                                </select>
                             </div>
                             <div class="mls-form-group mls-c2 user-field">
                                <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','your_name'); ?>" id="user" name="user" >
								 <span class="error-message" id="userError"></span>
                             </div>
                             <div class="mls-form-group mls-c2 email-field">
                                <input type="email" placeholder="<?php echo mls_plugin_translate('placeholders','your_email'); ?>" id="email" name="email" >
								 <span class="error-message" id="emailError"></span>
                             </div>
                             <div class="mls-form-group mls-c2 phone-field">
                                <input type="text" placeholder="<?php echo mls_plugin_translate('placeholders','your_phone'); ?>" id="phone" name="phone" >
								 <span class="error-message" id="phoneError"></span>
                             </div>
                              <?php if($videohide){ ?>
							 <input type="hidden" name="personvideo" value="Person">
							 <?php }else{ ?>
                             <div class="mls-form-group mls-c2 tour-info-wrap">
                                 <label><?php echo mls_plugin_translate('labels','tour_type'); ?> <span class="tour-info"><img src="<?php echo esc_url(plugins_url('assets/images/info.png', __DIR__)); ?>" class="tour-ico" alt="" /></span></label>
                                 <div class="tour-info-toggle">
                                     <p><?php echo mls_plugin_translate('labels','tour_type_desc'); ?></p>
                                 </div>
                                 <div class="pervid">
                                 <div class="pervid-blk ">
                                    <input type="radio" id="Person" name="personvideo" value="Person" checked>
                                    <label for="Person"><img src="<?php echo esc_url(plugins_url('assets/images/person_t.png', __DIR__)); ?>" class="tour-ico" alt="" /> <?php echo mls_plugin_translate('placeholders','person'); ?></label>
                                 </div>
                                 <div class="pervid-blk">
                                    <input type="radio" id="Video" name="personvideo" value="Video">
                                    <label for="Video"><img src="<?php echo esc_url(plugins_url('assets/images/video_t.png', __DIR__)); ?>" class="tour-ico" alt="" /> <?php echo mls_plugin_translate('placeholders','video'); ?></label>
                                 </div>
                                 </div>
                             </div>
							 <?php } ?>
                             <div class="mls-form-group mls-c1 interest-field">
                                 <?php $commentvalue = mls_plugin_translate('placeholders','comment'). "'" . $prptitle . " , Ref: " . $property_details['Reference'] . "'"; ?>
                                <textarea id="comments" name="comment" cols="45" rows="8"><?php echo esc_html($commentvalue); ?></textarea>
                             </div>
                             <div class="mls-form-group mls-c1 mls-pr">
                                <input type="text" id="pricerangeResults" class="price-range-iput-block" value="$0 - 10000">
                                 <div class="mls-dropdown">
                                   <label>Price selector</label>
                                  <div class="price-input">
                                    <div class="field">
                                      <input type="number" min=0 max="9900" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" />
                                    </div>
                                    <div class="separator">-</div>
                                    <div class="field">
                                      <input type="number" min=0 max="10000" oninput="validity.valid||(value='10000');" id="max_price" class="price-range-field" />
                                    </div>
                                  </div>
                                  <div class="price-range-display">Price Range: <input id="pricerangeDisplay" class="price-range-display-block"  value="$0 to 10000"  /></div>
                                   <div class="slider-range-wrapper">
                                      <div id="slider-range" class="price-filter-range" name="rangeInput"></div>                                 
                                    </div>
                                 <div class="pr-btn-wrapper">
                                    <button class="pr-reset reset" id="price-range-reset">Reset</button>
                                    <button class="price-range-search" id="price-range-submit">Submit</button>
                                 </div>
                                 </div>
                             </div>
                             <div class="mls-form-group mls-c1">
                                 <input type="submit" id="mlsSubmitButton" name="mls_leadform_submit" class="button" value="<?php echo mls_plugin_translate('buttons','submit_request'); ?>">
                             </div>
                         </form>
                     <div id="form-response-message"></div>
                     </div>
                 </div>
             </div>
             <div class="mls-prj-right mls-prj-sidebar hide-xs">
                 <div class="mls-prj-sidebar-inner">
                    <div class="mls-latest-post cn-post">
                        <div class="mls-prj-price cn-area">
                           <div><a href="#contact-from" class="mls-button"><?php echo mls_plugin_translate('buttons','book_viewing'); ?></a></div>                            			<?php $price = $property_details['Price'];
                            if(!$price){ $price = $property_details['RentalPrice1']; }?>
                            <h3><small><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?></small> <?php echo esc_html(format_prices($price)); if ($mlsrentkey === mls_plugin_translate('labels','for_rent') ) { echo '<span>/'. $rentalpriceperiod .'</span>'; } ?></h3>
                        </div>
                    </div>
                    <div class="mls-latest-post">
                       <h3><?php echo mls_plugin_translate('prp_highlights','property_highlights'); ?></h3>
                       <div class="ltst-pst">
            <?php if (!empty($property_details['Reference'])): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','referenceid'); ?></h4>
                    <p><?php echo esc_html($property_details['Reference']); ?></p>
                </div>
            <?php endif; ?>


            <?php if (!empty($property_details['Location'])): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','location'); ?></h4>
                    <p><?php echo esc_html($property_details['Location']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Area'])): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','area'); ?></h4>
                    <p><?php echo esc_html($property_details['Area']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Country'])): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','country'); ?></h4>
                    <p><?php echo esc_html($property_details['Country']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['PropertyType']['NameType'])): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','property_type'); ?></h4>
                    <p><?php echo esc_html($property_details['PropertyType']['NameType']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Basura_Tax_Year']) && $property_details['Basura_Tax_Year'] != 0): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','garbage_fees'); ?></h4>
                    <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['Basura_Tax_Year']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['IBI_Fees_Year']) && $property_details['IBI_Fees_Year'] != 0): ?>
                <div class="ltst">
                    <h4><?php echo mls_plugin_translate('prp_highlights','ibi_fees'); ?></h4>
                    <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['IBI_Fees_Year']); ?></p>
                </div>
            <?php endif; ?>
        </div>

                    </div>
                 </div>
             </div>
          </div>
       </div>
    </div>
</section>


    <?php
	
    // Return the buffered content
    return ob_get_clean();

}
if (mls_plugin_check_license_status()) {
add_shortcode('mls_property_details', 'mls_property_details_shortcode');
}