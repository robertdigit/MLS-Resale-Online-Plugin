<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function mls_property_details_shortcode() {
    $property_ref = get_query_var('property_ref');
    $property_type = isset($_GET['type']) ? $_GET['type'] : '';
	$view_more_url = home_url( add_query_arg( [], $_SERVER['REQUEST_URI'] ) );
	
	// Map property types to their corresponding filters
$filter_map = [
    'For Sale' => get_option('mls_plugin_filter_id_sales'),
    'For Rent' => get_option('mls_plugin_filter_id_short_rentals'),
    'For Rent' => get_option('mls_plugin_filter_id_long_rentals'),
    'Featured' => get_option('mls_plugin_filter_id_features'),
];
	
	
    if (!$property_ref || !$property_type) {
        return '<div class="search-not-perform"><p>Invalid property information.</p></div>';
    }
// echo $property_ref . "<br>";
// 	echo $property_type . "<br>";
    // Fetch property details using the reference and type
    $properties = mls_plugin_fetch_ref($property_ref, $property_type);

    if (!$properties) {
        return '<div class="search-not-perform"><p>Property not found.</p></div>';
    }
// 	else{
// 		return '<pre>' . print_r($properties, true) . '</pre>';
// 	}
$property_details = $properties['Property'];
	
    // Start output buffering
    ob_start();
    ?>
<section class="mls-parent-wrapper">
    <div class="mls-prj-details mls-main-content">
       <div class="mls-container">
          <div class="mls-breadcrumb">
             <?php mls_plugin_breadcrumb(); ?>
          </div>
<!-- 		   <div class="mls-pagination-btns-block">
			   <div class="mls-pagi-btns-left">
				   <div><a href="javascript:void(0);" class="mls-button" id="backToPreviousPage"><img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" /> Back to Property List</a></div>
			   </div>
			   <div class="mls-pagi-btns-right">
				   <div><a href="javascript:void(0);" class="mls-button"><img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" /> Previous</a><a href="javascript:void(0);" class="mls-button">Next <img src="<?php echo esc_url(plugins_url('assets/images/next-arrow.png', __DIR__)); ?>" alt="Next" /></a></div>
			   </div>
		   </div> -->
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
                    <img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" />
                    Back to previous page </a>
            <?php
            } else {
				$previous_page_url = home_url();
                // Fallback if no referrer is found
            ?>
                <a href="<?php echo esc_url($previous_page_url); ?>" class="mls-button">
                    <img src="<?php echo esc_url(plugins_url('assets/images/prev-arrow.png', __DIR__)); ?>" alt="Previous" />
                    Back to Home </a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="mls-pagi-btns-right">
        <div>
            <a href="javascript:void(0);" class="mls-button">
                <img src="<?php echo esc_url(plugins_url('assets/images/prev.png', __DIR__)); ?>" alt="Previous" /> 
                Previous
            </a>
            <a href="javascript:void(0);" class="mls-button">
                Next <img src="<?php echo esc_url(plugins_url('assets/images/next.png', __DIR__)); ?>" alt="Next" />
            </a>
        </div>
    </div>
</div>

          <div class="mls-prj-detail-full mls-heading">
             <div class="mls-prj-left">
                 <div class="mls-prj-section mls-prj-lay mls-prjs1">
                     <div class="mls-prjs1-top">
                         <div class="mls-prjs1-top-left">
                            <div class="mls-prj-title">
<!--                                <div class="mls-pyc-left"><span>For Sale</span></div> -->
								<?php if (in_array($property_type, $filter_map)) {
    // Loop through and find the matching property type
    foreach ($filter_map as $mlsrentkey => $value) {
        if ($property_type == $value) {
//             echo "Property type matches {$key} filter";
			echo '<div class="mls-pyc-left"><span>'. $mlsrentkey .'</span></div>';
            // Additional actions can be performed here based on the match
            break;
        }
    }
} ?>
                                <?php $prptitle = $property_details['PropertyType']['NameType'] . ' in ' . $property_details['Location']  . ', ' . $property_details['Area'] . ', ' . $property_details['Country']; 
//                                 $prpfulllocation = $property_details['Location']  . ', ' . $property_details['Area'] . ', ' . $property_details['Country'];
	$prpfulllocation = $property_details['Location']  . ', ' . $property_details['Province'] ;
                                $prplocation = $property_details['Location'];
                                $prpgeoX = $property_details['GpsX'];
                                $prpgeoY = $property_details['GpsY'];
								$rentalpriceperiod = $property_details['RentalPeriod'] ?? 'Month'; 
                                ?>

                               <h2><?php echo esc_html($prptitle); ?></h2>
                               <p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($property_details['Area'] . ', ' . $property_details['Country']); ?></p>
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
                            <span class="mls-ss-title">Share this:</span> <?php echo wp_kses_post(social_share_function($view_more_url)); ?>
                        </div>
						 <div class="mls-virtual-btn">
							 <a href="javascript:void(0);" class="mls-button"><i class="fa-solid fa-vr-cardboard"></i> Virtual Tour</a>
						 </div>
                     </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5 show-xs">
                    <div class="cn-post">
                        <div class="mls-prj-price cn-area">
                           <div><a href="#contact-from" class="mls-button">Book a Viewing</a></div>                            			<?php $price = $property_details['Price'];
                            if(!$price){ $price = $property_details['RentalPrice1']; }?>
                            <h3><small><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?></small> <?php echo esc_html(format_price($price)); if ($mlsrentkey === 'For Rent' ) { echo '<span>/'. $rentalpriceperiod .'</span>'; }?></h3>
                        </div>
                    </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs2">
                    <div class="mls-prj-feature">
        <?php if (!empty($property_details['Bedrooms']) && $property_details['Bedrooms'] != 0): ?>
            <div>
                <div class="mls-pycf-t">Bedrooms</div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/bed.png', __DIR__)); ?>" alt="Bedrooms" /> 
                    <span><?php echo esc_html($property_details['Bedrooms']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Bathrooms']) && $property_details['Bathrooms'] != 0): ?>
            <div>
                <div class="mls-pycf-t">Bathrooms</div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/bathtub.png', __DIR__)); ?>" alt="Bathrooms" />
                    <span><?php echo esc_html($property_details['Bathrooms']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Built']) && $property_details['Built'] != 0): ?>
            <div>
                <div class="mls-pycf-t">Built Area</div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/angle.png', __DIR__)); ?>" alt="Built Area" /> 
                    <span><?php echo esc_html($property_details['Built']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['GardenPlot']) && $property_details['GardenPlot'] != 0): ?>
            <div>
                <div class="mls-pycf-t">Garden Plot</div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/garden.png', __DIR__)); ?>" alt="Garden Plot" /> 
                    <span><?php echo esc_html($property_details['GardenPlot']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($property_details['Terrace']) && $property_details['Terrace'] != 0): ?>
            <div>
                <div class="mls-pycf-t">Terrace</div>
                <div class="mls-pycf-c">
                    <img src="<?php echo esc_url(plugins_url('assets/images/outdoor.png', __DIR__)); ?>" alt="Terrace" /> 
                    <span><?php echo esc_html($property_details['Terrace']); ?> m<sup>2</sup></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5 show-xs">
                               <h4>Property Highlights</h4>
                               <div class="ltst-pst">
                    <?php if (!empty($property_ref)): ?>
                        <div class="ltst">
                            <h4>Reference ID</h4>
                            <p><?php echo esc_html($property_ref); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Price']) || !empty($property_details['RentalPrice1']) ): ?>
                        <div class="ltst">
                            <h4>Price</h4>
                            <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html(format_price($price)); if ($mlsrentkey === 'For Rent' ) { echo '<span>/'. $rentalpriceperiod .'</span>'; }?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Location'])): ?>
                        <div class="ltst">
                            <h4>Location</h4>
                            <p><?php echo esc_html($property_details['Location']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Area'])): ?>
                        <div class="ltst">
                            <h4>Area</h4>
                            <p><?php echo esc_html($property_details['Area']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Country'])): ?>
                        <div class="ltst">
                            <h4>Country</h4>
                            <p><?php echo esc_html($property_details['Country']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['PropertyType']['NameType'])): ?>
                        <div class="ltst">
                            <h4>Property type</h4>
                            <p><?php echo esc_html($property_details['PropertyType']['NameType']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['Basura_Tax_Year']) && $property_details['Basura_Tax_Year'] != 0): ?>
                        <div class="ltst">
                            <h4>Garbage fees/year</h4>
                            <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['Basura_Tax_Year']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property_details['IBI_Fees_Year']) && $property_details['IBI_Fees_Year'] != 0): ?>
                        <div class="ltst">
                            <h4>IBI Fees</h4>
                            <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['IBI_Fees_Year']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5">
                     <h4>Features</h4>
                     <div class="mls-prj-moredetail">
                         <ul>
            <?php $propertyFeatures = $property_details['PropertyFeatures'];
            if($propertyFeatures){
            foreach ($propertyFeatures['Category'] as $feature): ?>
                <li>
                    <span class="mls-prjmd-title"><?php echo esc_html($feature['Type']); ?></span>
    <span class="mls-prjmd-cnt"><?php echo esc_html(implode(', ', $feature['Value'])); ?></span>
                </li>
            <?php endforeach; 
            }else{ echo "<p>No Features Available.</p>"; }
            ?>
        </ul>
                     </div>
                </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs3">
                     <h4>Description</h4>
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
                     <h4>Amenities</h4>
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
                <p>No amenities available.</p>
            <?php endif; ?>
        </div>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs5">
                     <h4>Explore Perfect Area in Map</h4>
                     <?php $map_provider = get_option('mls_plugin_map_provider', 'openstreetmap');

                        // Check if OpenStreetMap is selected
                        if ($map_provider === 'openstreetmap') {
                        mls_plugin_display_map($prptitle, $prpgeoX, $prpgeoY, $prpfulllocation); 
                        } ?>
                 </div>
                 <div class="mls-prj-section mls-prj-lay mls-prjs6" id="contact-from">
                     <h4>Book a Viewing</h4>
					  <?php $videohide = get_option('mls_plugin_leadformvideohide');
							 if($videohide){ $videohidecls ="videohidecls"; }else{$videohidecls="";} ?>
                   <div class="mls-form <?php echo esc_attr( $videohidecls ); ?>">
                         <form id="mls-lead-form" method="POST">
                             <input type="hidden" name="action" value="mls_plugin_lead_form">
                             <input type="hidden" name="property_ref" value="<?php echo esc_attr($property_ref); ?>">
                             <div class="mls-form-group mls-c1 date-field">
                                 <div class="schedule-date-slider">
        <?php
        // Get the current date
        $currentDate = new DateTime();

        // Loop through the next 10 days
        for ($i = 0; $i < 10; $i++) {
            // Clone the current date to avoid modifying the original
            $date = clone $currentDate;
            $date->modify("+$i day");

            // Get the day, date, and month
            $dayName = $date->format('D'); // Day name (e.g., Mon, Tue)
            $dayNum = $date->format('d');  // Day number (e.g., 01, 02)
            $month = $date->format('M');   // Month name (e.g., Jan, Feb)
            $year = $date->format('Y');

            // Generate a unique value for the radio button ID and value
//             $value = strtolower($dayName) . '-' . $dayNum . '-' . strtolower($month) . '-' . $year;
			$value = $dayName . '-' . $dayNum . '-' . $month . '-' . $year;
            $isChecked = ($i === 0) ? 'checked="checked"' : ''; // Check the first input by default
        ?>
            <div>
                <div class="property-schedule-singledate-wrapper">
        <input type="radio" id="scheduledate" name="scheduledate" class="" value="<?php echo esc_attr($value); ?>" >
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
                             <div class="mls-form-group mls-c3 time-field custom-select">
                               <?php mls_plugin_display_available_timings(); ?>
                             </div>
							 <div class="mls-form-group mls-c3 time-field custom-select">
                               <?php mls_plugin_display_language_options(); ?>
                             </div>
                             <div class="mls-form-group mls-c3 user-type custom-select">
                                <select name="buyerseller" id="buyerseller" >
                                    <option>Please select the type of visitor</option>
                                    <option>Buyer</option>
                                    <option>Seller</option>
                                    <option>Agent</option>
                                </select>
                             </div>
                             <div class="mls-form-group mls-c3 tiw-align">
                                <input type="text" placeholder="Your Name" id="user" name="user" >
								 <span class="error-message" id="userError"></span>
                             </div>
                             <div class="mls-form-group mls-c3 tiw-align">
                                <input type="email" placeholder="Your Email" id="email" name="email" >
								 <span class="error-message" id="emailError"></span>
                             </div>
                             <div class="mls-form-group mls-c3 tiw-align">
                                <input type="text" placeholder="Your Phone" id="phone" name="phone" >
								 <span class="error-message" id="phoneError"></span>
                             </div>
                              <?php if($videohide){ ?>
							 <input type="hidden" name="personvideo" value="Person">
							 <?php }else{ ?>
                             <div class="mls-form-group mls-c3 tiw-align tour-info-wrap">
                                 <label>Tour Type: <span class="tour-info"><i class="fa-solid fa-info"></i></span></label>
                                 <div class="tour-info-toggle">
                                     <p>"If you are unable to visit the property in person, we can visit for you and connect via whatsapp video to give you a tour."</p>
                                 </div>
                                 <div class="pervid">
                                 <div class="pervid-blk active">
                                    <input type="radio" id="Person" name="personvideo" value="Person" checked>
                                    <label for="Person"><i class="fa-solid fa-user"></i> In Person</label>
                                 </div>
                                 <div class="pervid-blk">
                                    <input type="radio" id="Video" name="personvideo" value="Video">
                                    <label for="Video"><i class="fa-solid fa-video"></i> Video</label>
                                 </div>
                                 </div>
                             </div>
							 <?php } ?>
                             <div class="mls-form-group mls-c1">
                                 <?php $commentvalue = "I'm interested in '" . $prptitle . " , Ref: " . $property_ref . "'"; ?>
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
                                 <input type="submit" id="mlsSubmitButton" name="mls_leadform_submit" class="button" value="Submit Request">
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
                           <div><a href="#contact-from" class="mls-button">Book a Viewing</a></div>                            			<?php $price = $property_details['Price'];
                            if(!$price){ $price = $property_details['RentalPrice1']; }?>
                            <h3><small><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?></small> <?php echo esc_html(format_price($price)); if ($mlsrentkey === 'For Rent' ) { echo '<span>/'. $rentalpriceperiod .'</span>'; } ?></h3>
                        </div>
                    </div>
                    <div class="mls-latest-post">
                       <h3>Property Highlights</h3>
                       <div class="ltst-pst">
            <?php if (!empty($property_ref)): ?>
                <div class="ltst">
                    <h4>Reference ID</h4>
                    <p><?php echo esc_html($property_ref); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Price']) || !empty($property_details['RentalPrice1']) ): ?>
                <div class="ltst">
                    <h4>Price</h4>
                    <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html(format_price($price)); if ($mlsrentkey === 'For Rent' ) { echo '<span>/'. $rentalpriceperiod .'</span>'; } ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Location'])): ?>
                <div class="ltst">
                    <h4>Location</h4>
                    <p><?php echo esc_html($property_details['Location']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Area'])): ?>
                <div class="ltst">
                    <h4>Area</h4>
                    <p><?php echo esc_html($property_details['Area']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Country'])): ?>
                <div class="ltst">
                    <h4>Country</h4>
                    <p><?php echo esc_html($property_details['Country']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['PropertyType']['NameType'])): ?>
                <div class="ltst">
                    <h4>Property type</h4>
                    <p><?php echo esc_html($property_details['PropertyType']['NameType']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['Basura_Tax_Year']) && $property_details['Basura_Tax_Year'] != 0): ?>
                <div class="ltst">
                    <h4>Garbage fees/year</h4>
                    <p><?php echo esc_html(RESALES_ONLINE_API_CURRENCY[$property_details['Currency']]); ?> <?php echo esc_html($property_details['Basura_Tax_Year']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($property_details['IBI_Fees_Year']) && $property_details['IBI_Fees_Year'] != 0): ?>
                <div class="ltst">
                    <h4>IBI Fees</h4>
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
if (mls_plugin_is_license_valid()) {
add_shortcode('mls_property_details', 'mls_property_details_shortcode');
}