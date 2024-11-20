<?php

if (!session_id()) {
    session_start();
}

// Function to display the fetched properties.
function mls_plugin_display_propertiess($data, $maximage, $includesorttype, $p_sorttype) {
	
	ob_start();
// 	if($data){ return '<pre>' . print_r($data, true) . '</pre>'; }
    if (is_array($data) && isset($data['Property']) && is_array($data['Property'])) {

        if (isset($data['QueryInfo']) && is_array($data['QueryInfo'])) {
            $query_id = isset($_GET['query_id']) ? esc_html($_GET['query_id']) : esc_html($data['QueryInfo']['QueryId']);
            $current_page = isset($_GET['page_num']) ? esc_html($_GET['page_num']) : esc_html($data['QueryInfo']['CurrentPage']);
            $properties_per_page = esc_html($data['QueryInfo']['PropertiesPerPage']);
            $total_properties = esc_html($data['QueryInfo']['PropertyCount']);
            $total_pages = ceil($total_properties / $properties_per_page);
			
			$filter_type = esc_html($data['QueryInfo']['SearchType']);
        }
?>

      <section class="mls-parent-wrapper">
            <div class="mls-list-filter">
                <div class="layout-filter">
    <span class="grid"><img src="<?php echo esc_url( plugins_url('assets/images/pixels.png', __DIR__) ); ?>" alt="" /></span>
    <span class="list"><img src="<?php echo esc_url( plugins_url('assets/images/list.png', __DIR__) ); ?>" alt="" /></span>
</div>

           <?php if ($includesorttype == '1') : ?>
    <div class="sorttype mls-form">
        <form id="sort_form"  method="get">
            <span class="sb-label">Sort by:</span>
            <select name="p_sorttype" id="order_search" onchange="this.form.submit()">
    <option value="0" <?php selected($p_sorttype === '0'); ?>>Price (Lowest First)</option>
    <option value="1" <?php selected($p_sorttype === '1'); ?>>Price (Highest First)</option>
    <option value="2" <?php selected($p_sorttype === '2'); ?>>Location</option>
    <option value="3" <?php selected($p_sorttype === '3'); ?>>Most Recent First</option>
    <option value="4" <?php selected($p_sorttype === '4'); ?>>Oldest First</option>
</select>
            <?php

		// Loop through query parameters
    foreach ($_GET as $key => $value) {
        // Keep all parameters except 'p_sorttype', set 'query_id' value to an empty string if it exists
        if ($key === 'query_id' || $key === 'page_num') {
            echo '<input type="hidden" name="query_id" value="">';
			 echo '<input type="hidden" name="page_num" value="1">';
        } elseif ($key !== 'p_sorttype') {
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        }
    }
            ?>
        </form>
    </div>
            
<?php endif; 
			
			?>
</div>
 <?php 
		if($total_properties > 2) { $layoutclass = "pro-grid"; }elseif($total_properties < 3 && $total_properties > 0){ $layoutclass = "pro-list"; }else{$layoutclass = "no-post";} 
		// Retrieve filter values from session
        if ($filter_type === 'Short Term Rental' || $filter_type === 'Long Term Rental') {
        $filter_name = "For Rent";
    	} elseif ($filter_type === 'Sale (Featured For RSO)') {
        $filter_name = "Featured";
    	} else {
        $filter_name = "For Sale";
   	 	}
			?>
            <div class="mls-pro-list-wrapper <?php echo esc_attr( $layoutclass ); ?>">
                <?php if($total_properties > 0) {
		foreach ($data['Property'] as $property) : ?>
                    <div class="mls-property-box mls-main-content mls-heading">
                        <div class="mls-property-in">
                            <div class="mls-property-img">
                                <div class="for-mls-pyc">
                                    <div class="mls-pyc-left"><span><?php echo esc_html( $filter_name ); ?></span></div>
                                    <div class="mls-pyc-right"><span>Ref: <?php echo esc_html($property['Reference']); ?></span></div>
                                </div>

                                <div class="mls-project-listing-slider lightgallery2" id="lightgallery2">
									 <?php
				$prpdtselected_page_id = get_option('mls_plugin_property_detail_page_id', '');
    $prpdetailpage_id = $prpdtselected_page_id ? $prpdtselected_page_id : 7865;
$prpdetailpage = get_post($prpdetailpage_id);
$prpdetailpage_slug = $prpdetailpage ? $prpdetailpage->post_name : '';
                                    $property_title = sanitize_title($property['PropertyType']['NameType'] . ' in ' . $property['Location'] . ', ' . $property['Area'] . ', ' . $property['Country']);
                                    $property_ref = $property['Reference'];
                                    $property_filter_type = $data['QueryInfo']['ApiId'];
                                    $view_more_url = home_url("{$prpdetailpage_slug}/{$property_title}/{$property_ref}/?type={$property_filter_type}");
                                    ?>
									
    <?php if (isset($property['Pictures']['Count']) && isset($property['Pictures']['Picture'])) : ?>
        <?php 
        $maxImages = empty($maximage) ? $property['Pictures']['Count'] : min($maximage, $property['Pictures']['Count']); 
        ?>
        
        <?php for ($i = 0; $i < $maxImages; $i++) : ?>
            <div class="mls-list-li-wrapper" data-src="<?php echo esc_url($property['Pictures']['Picture'][$i]['PictureURL']); ?>">
                <a class="pro-li-fly-link"  href="<?php echo esc_url($view_more_url); ?>"></a>
                <img src="<?php echo esc_url($property['Pictures']['Picture'][$i]['PictureURL']); ?>" alt="Property Image" />
            </div>
        <?php endfor; ?>

        <?php if ($maximage > 0) : ?>
            <div>
                <a class="pro-li-fly-link"  href="<?php echo esc_url($view_more_url); ?>"></a>
                <img src="<?php echo esc_url( plugins_url('assets/images/listing_instruction_img.png', __DIR__) ); ?>" alt="Listing Instruction Image" />
            </div>
        <?php endif; ?>
      
	<?php elseif (isset($property['MainImage'])) : ?>
        <div class="mls-list-li-wrapper" data-src="<?php echo esc_url($property['MainImage']); ?>">
            <a class="pro-li-fly-link" href="<?php echo esc_url($view_more_url); ?>"></a>
            <img src="<?php echo esc_url($property['MainImage']); ?>" alt="Main Property Image" />
        </div>
									
    <?php else : ?>
        <div>
            <img src="<?php echo esc_url( plugins_url('assets/images/new-image-8.jpg', __DIR__) ); ?>" alt="Default Image" />
        </div>
    <?php endif; ?>
</div>


                                <div class="mls-pro-img-count">
                                    <div>
                                        <span class="loc-fv-toggle">
                                            <img src="<?php echo esc_url( plugins_url('assets/images/pin.png', __DIR__) ); ?>">
                                            <span class="loc"><a href="#"><?php echo esc_html($property['Location']) . ', ' . esc_html($property['Area']) . ', ' . esc_html($property['Country']); ?></a></span>
                                            <span class="loc-fv"><?php echo esc_html($property['Location']) . ', ' . esc_html($property['Area']) . ', ' . esc_html($property['Country']); ?></span>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mls-share-social-fn">
                                            <img src="<?php echo esc_url( plugins_url('assets/images/share-1.png', __DIR__) ); ?>" alt="">
                                            <?php echo wp_kses_post( social_share_function($view_more_url) ); ?>
                                        </span>
                                        <span class="img-count"><img src="<?php echo esc_url(  plugins_url('assets/images/photo-camera-interface-symbol-for-button.png', __DIR__) ); ?>"> <span><?php echo esc_html($property['Pictures']['Count']); ?></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mls-property-cnt">
                                <div class="mls-pyc-title">
                                    <h2>
                                        <a href="<?php echo esc_url($view_more_url); ?>" >
                                            <?php echo esc_html($property['PropertyType']['NameType']) . ' in ' . esc_html($property['Location']) . ', ' . esc_html($property['Area']) . ', ' . esc_html($property['Country']); ?>
                                        </a>
                                    </h2>
									<?php $price = $property['Price'];
									if(!$price){ $price = $property['RentalPrice1']; }
									$rentalpriceperiod = $property['RentalPeriod'] ?? 'Month'; ?>
                                    <h3><?php echo esc_html( RESALES_ONLINE_API_CURRENCY[$property['Currency']] ) . ' ' . esc_html( format_price( $price ) ); if ($filter_type === 'Short Term Rental' || $filter_type === 'Long Term Rental') { echo '<span>/'. $rentalpriceperiod .'</span>'; } ?>
									</h3>
                                    <p>
										<?php echo esc_html( wp_trim_words( wpautop( esc_html( $property['Description'] ) ), 25, '...' ) ); ?>
									</p>
                                </div>

                                <div class="mls-pyc-feature">
    <?php if (!empty($property['Bedrooms']) && $property['Bedrooms'] != 0): ?>
        <div class="mls-pycf-c">
            <img src="<?php echo esc_url( plugins_url('assets/images/bed.png', __DIR__) ); ?>" alt="Bedrooms" /> 
            <span><?php echo esc_html($property['Bedrooms']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($property['Bathrooms']) && $property['Bathrooms'] != 0): ?>
        <div class="mls-pycf-c">
            <img src="<?php echo esc_url( plugins_url('assets/images/bathtub.png', __DIR__) ); ?>" alt="Bathrooms" /> 
            <span><?php echo esc_html($property['Bathrooms']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($property['Built']) && $property['Built'] != 0): ?>
        <div class="mls-pycf-c">
            <img src="<?php echo esc_url( plugins_url('assets/images/angle.png', __DIR__) ); ?>" alt="Built Area" /> 
            <span><?php echo esc_html($property['Built']); ?> m<sup>2</sup></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($property['GardenPlot']) && $property['GardenPlot'] != 0): ?>
        <div class="mls-pycf-c">
            <img src="<?php echo esc_url( plugins_url('assets/images/garden.png', __DIR__) ); ?>" alt="Garden Plot" /> 
            <span><?php echo esc_html($property['GardenPlot']); ?> m<sup>2</sup></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($property['Terrace']) && $property['Terrace'] != 0): ?>
        <div class="mls-pycf-c">
            <img src="<?php echo esc_url( plugins_url('assets/images/outdoor.png', __DIR__) ); ?>" alt="Terrace" /> 
            <span><?php echo esc_html($property['Terrace']); ?> m<sup>2</sup></span>
        </div>
    <?php endif; ?>
</div>

                            </div>

                            <div class="mls-pyc-btn-wrapper"><a href="<?php echo esc_url($view_more_url); ?>"  class="mls-button mls-pyc-button">View More</a></div>
                        </div>
                    </div>
                <?php endforeach; 
			}else{ ?>
				<div class="nopropertyfound">
					<p>No properties found.</p>
				</div>
				<?php } ?>
            </div>

            <!-- Pagination Section -->
            <div class="mls-pagination">
                <?php mls_plugin_pagination($current_page, $total_pages, $query_id); ?>
            </div>
		  
        
      </section>

<?php
    } else {
        echo '<div class="search-not-perform"><p>No properties found.</p></div>';
    }
	// Return the buffered content instead of echoing it
    return ob_get_clean();
}

// Pagination Function
function mls_plugin_pagination($current_page, $total_pages, $query_id) {
    if ($total_pages <= 1) return;

    $pagination_html = '<ul class="mls-pagination-list">';
    $range = 2; // Number of pages to show on either side of the current page
    $show_first_last = 2; // Number of first and last pages to always show

    // Always show the first pages
    for ($i = 1; $i <= min($show_first_last, $total_pages); $i++) {
        $class = ($i == $current_page) ? 'class="active"' : '';
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url(add_query_arg(array(
            'page_num' => $i,
            'query_id' => $query_id
        ))) . '">' . esc_html($i) . '</a></li>';
    }

    // Add ellipsis if needed
    if ($current_page > $show_first_last + $range + 1) {
        $pagination_html .= '<li><span>...</span></li>';
    }

    // Calculate start and end page to show
    $start = max($current_page - $range, $show_first_last + 1);
    $end = min($current_page + $range, $total_pages - $show_first_last);

    // Show the middle pages
    for ($i = $start; $i <= $end; $i++) {
        $class = ($i == $current_page) ? 'class="active"' : '';
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url(add_query_arg(array(
            'page_num' => $i,
            'query_id' => $query_id
        ))) . '">' . esc_html($i) . '</a></li>';
    }

    // Add ellipsis if needed for the end
    if ($current_page < $total_pages - $show_first_last - $range) {
        $pagination_html .= '<li><span>...</span></li>';
    }

    // Always show the last pages
    for ($i = max($total_pages - $show_first_last + 1, $start); $i <= $total_pages; $i++) {
        $class = ($i == $current_page) ? 'class="active"' : '';
        $pagination_html .= '<li ' . $class . '><a href="' . esc_url(add_query_arg(array(
            'page_num' => $i,
            'query_id' => $query_id
        ))) . '">' . esc_html($i) . '</a></li>';
    }

    $pagination_html .= '</ul>';
    
    // Escape the output before printing
    echo wp_kses_post($pagination_html);
}



function social_share_function( $property_link ) {
    $social_icons = array(
        'facebook' => 'https://www.facebook.com/sharer.php?u=' . $property_link,
        'twitter' => 'https://twitter.com/intent/tweet?url=' . $property_link,
        'whatsapp' => 'whatsapp://send?text=' . $property_link . ' (Shared from ' . get_bloginfo( 'name' ) . ')', // Check for WhatsApp app first
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $property_link
    );

    $output = '<div class="social-share-buttons">';
    foreach ( $social_icons as $network => $url ) {
        $output .= '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">
    <img src="' . plugin_dir_url(__DIR__ ) . 'assets/images/social-icons/' . $network . '.png' . '" alt="' . ucfirst( $network ) . '">
</a>';
    }
    $output .= '</div>';

    return $output;
}

function format_price($price) {
    // Check if the price is a range
    if (strpos($price, '-') !== false) {
        // Handle range pricing
        $price_range = explode('-', $price); // Split the price range
        return number_format(trim($price_range[0])) . ' - ' . number_format(trim($price_range[1]));
    } else {
        // Handle single price
        return number_format($price);
    }
}

?>
