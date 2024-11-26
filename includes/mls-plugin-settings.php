<?php
// Hook to add the settings page to the WordPress admin menu.
add_action('admin_menu', 'mls_plugin_add_admin_menu');

// Function to add the settings page.
function mls_plugin_add_admin_menu() {
    add_menu_page(
        'MLS Plugin Settings', // Page title
        'MLS Plugin',          // Menu title
        'manage_options',      // Capability required to access this menu
        'mls_plugin_settings', // Menu slug
        'mls_plugin_settings_page', // Function to display the page content
        'dashicons-admin-generic', // Icon for the menu
        20                     // Position on the menu
    );
	 // Add a new submenu item with the updated name
    add_submenu_page(
        'mls_plugin_settings', // Parent slug
        'MLS Plugin Settings',  // Page title
        'MLS Setting',          // Submenu title (this will be "MLS Setting")
        'manage_options',       // Capability required to access this menu
        'mls_plugin_settings',  // Slug for this submenu
        'mls_plugin_settings_page' // Function to display the page content
    );
}

// Function to display the settings page content.

function mls_plugin_settings_page() {
    // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

	if (mls_plugin_is_license_valid()) {
    // Get the active tab from the $_GET parameter
    $default_tab = null;
    $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
	
	// Process form submissions separately for each tab
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mls_plugin_api_key'])) {
        // Process the "Connection with Resales Online" form
        update_option('mls_plugin_api_key', sanitize_text_field($_POST['mls_plugin_api_key']));
        update_option('mls_plugin_client_id', sanitize_text_field($_POST['mls_plugin_client_id']));
        update_option('mls_plugin_filter_id_sales', sanitize_text_field($_POST['mls_plugin_filter_id_sales']));
		update_option('mls_plugin_filter_id_short_rentals', sanitize_text_field($_POST['mls_plugin_filter_id_short_rentals']));
		update_option('mls_plugin_filter_id_long_rentals', sanitize_text_field($_POST['mls_plugin_filter_id_long_rentals']));
		update_option('mls_plugin_filter_id_features', sanitize_text_field($_POST['mls_plugin_filter_id_features']));
		
    $selected_page_id = sanitize_text_field($_POST['mls_plugin_property_detail_page_id']);
    update_option('mls_plugin_property_detail_page_id', $selected_page_id);
	// Handle property types submission
    if (isset($_POST['mls_plugin_property_types'])) {
        // Sanitize and encode the selected property types as JSON
//         $property_types = array_map('sanitize_text_field', $_POST['mls_plugin_property_types']);
		$property_types = array_map('stripslashes', $_POST['mls_plugin_property_types']);

        update_option('mls_plugin_property_types', $property_types);
		
		 $property_types = array_map('stripslashes', $_POST['mls_plugin_property_types']); // Decode the input

		
		
    } else {
        // Clear the stored types if no types are selected
        update_option('mls_plugin_property_types', json_encode(array()));
    }
    } elseif (isset($_POST['mls_plugin_default_color'])) { 
        // Process the "Styles" form
        update_option('mls_plugin_default_color', sanitize_text_field($_POST['mls_plugin_default_color']));
        update_option('mls_plugin_blue_color', sanitize_text_field($_POST['mls_plugin_blue_color']));
        update_option('mls_plugin_black_color', sanitize_text_field($_POST['mls_plugin_black_color']));
        update_option('mls_plugin_green_color', sanitize_text_field($_POST['mls_plugin_green_color']));
		update_option('mls_plugin_white_color', sanitize_text_field($_POST['mls_plugin_white_color']));
		update_option('mls_plugin_border_color', sanitize_text_field($_POST['mls_plugin_border_color']));
		update_option('mls_plugin_banner_search_background_color', sanitize_text_field($_POST['mls_plugin_banner_search_background_color']));
		update_option('mls_plugin_banner_search_buttonbackground_color', sanitize_text_field($_POST['mls_plugin_banner_search_buttonbackground_color']));
		update_option('mls_plugin_banner_search_text_color', sanitize_text_field($_POST['mls_plugin_banner_search_text_color']));
		update_option('mls_plugin_banner_search_button_color', sanitize_text_field($_POST['mls_plugin_banner_search_button_color']));
		update_option('mls_plugin_banner_search_buttonhoverbackground_color', sanitize_text_field($_POST['mls_plugin_banner_search_buttonhoverbackground_color']));
		update_option('mls_def_prop_layout', sanitize_text_field($_POST['mls_def_prop_layout']));
    } elseif (isset($_POST['mls_plugin_leadformtomail']) || isset($_POST['mls_plugin_available_timings'])) {
		// Sanitize and update available timings
    $selected_timings = array_map('sanitize_text_field', $_POST['mls_plugin_available_timings']);
    update_option('mls_plugin_available_timings', $selected_timings);
	update_option('mls_plugin_leadformvideohide', sanitize_text_field($_POST['mls_plugin_leadformvideohide']));
		 $selected_languages = array_map('sanitize_text_field', $_POST['mls_plugin_languages']);
    update_option('mls_plugin_languages', $selected_languages);
    update_option('mls_plugin_custom_languages', sanitize_text_field($_POST['mls_plugin_custom_languages']));
     // Process "Lead Form To mail"
    $emails = sanitize_text_field($_POST['mls_plugin_leadformtomail']);
    $emails_array = explode(',', $emails);
    $valid_emails = array_map('trim', $emails_array);
    $validated_emails = array_filter($valid_emails, 'is_email');
    update_option('mls_plugin_leadformtomail', implode(', ', $validated_emails));

    // Process "Lead Form CC mail"
    $cc_emails = sanitize_text_field($_POST['mls_plugin_leadformccmail']);
    $cc_emails_array = explode(',', $cc_emails);
    $cc_valid_emails = array_map('trim', $cc_emails_array);
    $cc_validated_emails = array_filter($cc_valid_emails, 'is_email');
    update_option('mls_plugin_leadformccmail', implode(', ', $cc_validated_emails));
		
		update_option('mls_plugin_leadformmailsubject', sanitize_text_field($_POST['mls_plugin_leadformmailsubject']));
		update_option('mls_plugin_leadformmailheadertext', sanitize_text_field($_POST['mls_plugin_leadformmailheadertext']));
		update_option('mls_plugin_leadformmailfootertext', sanitize_text_field($_POST['mls_plugin_leadformmailfootertext']));
		
		// Process "Lead Form Mail Header Logo" (Media Library Image URL)
    update_option('mls_plugin_leadformmailheaderlogo', esc_url_raw($_POST['mls_plugin_leadformmailheaderlogo']));
		
    }elseif (isset($_POST['mls_plugin_save_map_settings'])) {
    // Process the "Map Integration" form
    update_option('mls_plugin_maphide', sanitize_text_field($_POST['mls_plugin_maphide']));
    if (isset($_POST['mls_plugin_map_provider'])) {
        update_option('mls_plugin_map_provider', sanitize_text_field($_POST['mls_plugin_map_provider']));
    }
		
	}
    // You can add more conditions for other tabs here...
}
	
    ?>
    <!-- Our admin page content should all be inside .wrap -->
    <div class="wrap mls-custom-admin-style">
        <!-- Print the page title -->
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <!-- Here are our tabs -->
        <nav class="nav-tab-wrapper mls-admin-navtab">
            <a href="?page=mls_plugin_settings" class="nav-tab <?php if ($tab === null): ?>nav-tab-active<?php endif; ?>">Connection with Resales Online</a>
            <a href="?page=mls_plugin_settings&tab=styles" class="nav-tab <?php if ($tab === 'styles'): ?>nav-tab-active<?php endif; ?>">Styles</a>
            <a href="?page=mls_plugin_settings&tab=map_integration" class="nav-tab <?php if ($tab === 'map_integration'): ?>nav-tab-active<?php endif; ?>">Integration with Map</a>
            <a href="?page=mls_plugin_settings&tab=lead_form" class="nav-tab <?php if ($tab === 'lead_form'): ?>nav-tab-active<?php endif; ?>">Lead Form</a>
            <a href="?page=mls_plugin_settings&tab=language" class="nav-tab <?php if ($tab === 'language'): ?>nav-tab-active<?php endif; ?>">Language</a>
        </nav>

        <div class="tab-content">
        
            <?php
           

            // Render the content for each tab based on the active tab
            switch ($tab) {
                case 'styles':
                    ?>
                    <h2>MLS Plugin Colour Setting</h2>
                    <form method="post">
                    <table class="form-table">
                        <tr valign="top"> <th scope="row"><h3>General Colour Setting</h3></th></tr>
                        <tr valign="top">
                            <th scope="row">Primary Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_blue_color" value="<?php echo esc_attr(get_option('mls_plugin_blue_color', '#0073e1')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Secondary Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_green_color" value="<?php echo esc_attr(get_option('mls_plugin_green_color', '#69c17d')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Text Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_default_color" value="<?php echo esc_attr(get_option('mls_plugin_default_color', '#5c727d')); ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Dark Text Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_black_color" value="<?php echo esc_attr(get_option('mls_plugin_black_color', '#222')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Light Text Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_white_color" value="<?php echo esc_attr(get_option('mls_plugin_white_color', '#ffffff')); ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Border Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_border_color" value="<?php echo esc_attr(get_option('mls_plugin_border_color', '#e7e7e7')); ?>" /></td>
                        </tr>
						<tr valign="top"> <th scope="row"><h3>Banner Search Form Colour Setting</h3></th></tr>
						<tr valign="top">
                            <th scope="row">Text Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_text_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_text_color', '#fff')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Background Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_background_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_background_color', '#f7f7f7')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Button Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_button_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_button_color', '#fff')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Button Background Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_buttonbackground_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_buttonbackground_color', '#0073e1')); ?>" /></td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Button Hover Background Color</th>
                            <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_buttonhoverbackground_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_buttonhoverbackground_color', '#69C17D')); ?>" /></td>
                        </tr>
						<tr valign="top">
							<?php $mls_def_prop_layout = get_option('mls_def_prop_layout'); ?>
                            <th scope="row">Property Listing Layout </th>
                            <td>
								<div class="mls_def_layout">
									<select name="mls_def_prop_layout" id="mls_def_prop_layout" >
                                    <option value="pro-grid" <?php selected($mls_def_prop_layout, 'pro-grid'); ?>>Grid</option>
<option value="pro-list" <?php selected($mls_def_prop_layout, 'pro-list'); ?>>List</option>

                                </select>
								</div>
                              <p class="description">Select to set the Default Property Layout.</p>  
							</td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
                    <?php
                    break;

                case 'map_integration':
    ?>
    <h2>Map Integration</h2>
    <form method="post" action="">
        <table class="form-table">
			<tr valign="top">
    <th scope="row">Hide Map Section</th>
    <td>
        <input type="checkbox" name="mls_plugin_maphide" value="1" <?php checked(get_option('mls_plugin_maphide'), '1'); ?> />
        <p class="description">Check to hide the Map Section in Property detail page.</p>
    </td>
</tr>
            <tr valign="top">
                <th scope="row">Select Map Service</th>
                <td>
                    <select name="mls_plugin_map_provider" id="mls_plugin_map_provider">
                        <option value="openstreetmap" <?php selected(get_option('mls_plugin_map_provider'), 'openstreetmap'); ?>>OpenStreetMap</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" name="mls_plugin_save_map_settings" value="Save Changes" class="button-primary" />
    </form>
    <?php
    break;


                case 'lead_form':
                    ?>
                    
                    <form method="post">
                    <table class="form-table easySel-style">
						<tr valign="top"> <th scope="row"><h2>Lead Form Setting</h2></th></tr>
						<tr valign="top">
    <th scope="row">Hide Tour Type field</th>
    <td>
        <input type="checkbox" name="mls_plugin_leadformvideohide" value="1" <?php checked(get_option('mls_plugin_leadformvideohide'), '1'); ?> />
        <p class="description">Check to hide the Tour Type field (Video and In Person options) in the Lead Form. By default, the value will be set to 'Person' and stored in the submission.</p>
    </td>
</tr>
						<tr valign="top">
                            <th scope="row">Available Timing</th>
                            <td>
                    <?php mls_plugin_leadform_timing(); ?>
</td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Preferred Languages</th>
                            <td class="mlspreflang">
                    <?php mls_plugin_display_language_setting_options(); ?>
</td>
                        </tr>
                        <tr valign="top"> <th scope="row"><h2>Email Configuration</h2></th></tr>
						<tr valign="top">
                            <th scope="row">To Email Address</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformtomail" value="<?php echo esc_attr(get_option('mls_plugin_leadformtomail')); ?>" placeholder="Enter emails separated by commas" />
                    <p class="description">You can enter multiple email addresses separated by comma.</p>
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">CC Email Address</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformccmail" value="<?php echo esc_attr(get_option('mls_plugin_leadformccmail')); ?>" placeholder="Enter emails separated by commas" />
                    <p class="description">You can enter multiple email addresses separated by comma.</p>
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Email Subject</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformmailsubject" value="<?php echo esc_attr(get_option('mls_plugin_leadformmailsubject')); ?>"  />
                    
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Email Template Header Logo</th>
                            <td>
                    <img id="mls_plugin_leadformmailheaderlogo_preview" src="<?php echo esc_url(get_option('mls_plugin_leadformmailheaderlogo')); ?>" style="max-width: 150px; height: auto; display: <?php echo get_option('mls_plugin_leadformmailheaderlogo') ? 'block' : 'none'; ?>" />
                    <input type="hidden" id="mls_plugin_leadformmailheaderlogo" name="mls_plugin_leadformmailheaderlogo" value="<?php echo esc_attr(get_option('mls_plugin_leadformmailheaderlogo')); ?>" />
                    <button type="button" class="button" id="mls_plugin_leadformmailheaderlogo_button">Select Image</button>
                    <button type="button" class="button" id="mls_plugin_leadformmailheaderlogo_remove" style="display: <?php echo get_option('mls_plugin_leadformmailheaderlogo') ? 'inline-block' : 'none'; ?>;">Remove Image</button>
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Email Template Header Text</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformmailheadertext" value="<?php echo esc_attr(get_option('mls_plugin_leadformmailheadertext')); ?>" />
                    
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Email Template Footer Text</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformmailfootertext" value="<?php echo esc_attr(get_option('mls_plugin_leadformmailfootertext')); ?>" />
                    
                </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
                    <?php
                    break;

                case 'language':
                    ?>
                    <h2>Language Settings</h2>
                    <!-- Add content for Language settings here -->
			<p>Language Setting will be here </p>
                    <?php
                    break;

                default:
                    ?>
                    <h2>Connection with Resales Online</h2>
                    <form method="post">
                    <table class="form-table easySel-style ft-flex">
                        <tr valign="top">
                            <th scope="row">API Key</th>
                            <td><input type="text" name="mls_plugin_api_key" value="<?php echo esc_attr(get_option('mls_plugin_api_key')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide API Key</p>
                                </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Client ID</th>
                            <td><input type="text" name="mls_plugin_client_id" value="<?php echo esc_attr(get_option('mls_plugin_client_id')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide Client ID</p>
                                </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Default Filter ID Sales</th>
                            <td><input type="text" name="mls_plugin_filter_id_sales" value="<?php echo esc_attr(get_option('mls_plugin_filter_id_sales')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide Default Filter ID Sales</p>
                                </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Default Filter ID Short Rentals</th>
                            <td><input type="text" name="mls_plugin_filter_id_short_rentals" value="<?php echo esc_attr(get_option('mls_plugin_filter_id_short_rentals')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide Default Filter ID Short Rentals</p>
                                </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Default Filter ID Long Rentals</th>
                            <td><input type="text" name="mls_plugin_filter_id_long_rentals" value="<?php echo esc_attr(get_option('mls_plugin_filter_id_long_rentals')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide Default Filter ID Long Rentals</p>
                                 </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Default Filter ID Features</th>
                            <td><input type="text" name="mls_plugin_filter_id_features" value="<?php echo esc_attr(get_option('mls_plugin_filter_id_features')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Provide Default Filter ID Features</p>
                                 </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Include Only Property Types on Search</th>
                            <td>
								<?php mls_plugin_property_type_filter_callback(); ?>
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please Include Only Property Types on Search</p>
                                 </div>                            
                                </div>
							</td>
                        </tr>
						<tr valign="top">
<?php $prpdtselected_page_id = get_option('mls_plugin_property_detail_page_id', ''); // Get saved page ID
    $mls_allpages = get_pages(); ?>							
                            <th scope="row">Select Property Detail Page</th>
                            <td>
								<select name="mls_plugin_property_detail_page_id">
                            <option value="">-- Select a Page --</option>
                            <?php foreach ($mls_allpages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" 
            <?php selected($prpdtselected_page_id, $page->ID); ?>>
            <?php echo esc_html($page->post_title); ?>
        </option>
                            <?php endforeach; ?>
                        </select>
								<div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Please select the page to display property details.</p>
                                 </div>                            
                                </div>
                   <p class="description"> Use shortcode <code>[mls_property_details]</code> on the selected page.</p>
							</td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Refresh Cache</th>
                            <td>
								<div class="refresh_btn"><a class="mls_plugin_refresh_btn button" href="#">Refresh</a></div>
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Click the button here to fetch new data's from Resale Online</p>
                                 </div>                            
                                </div>
								<p id="mls-last-refresh">
            <?php
            $last_refresh = get_option('mls_plugin_last_cache_refresh');
            echo $last_refresh 
                ? 'Last refreshed on: ' . esc_html(date_i18n('d/m/Y H:i', strtotime($last_refresh)))
                : 'Cache has not been refreshed yet.';
            ?>
        </p>
							</td>
                        </tr>
                    </table>
				<?php submit_button(); ?>
                </form>
                    <?php
                    break;
            }
            ?>
        
        </div>
    </div>
    <?php
	}else{ ?>
		<div class="wrap mls-custom-admin-style">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
				<p>Please activate your plugin license key to access all features.</p>
				<p>
    <a href="<?php echo esc_url(site_url()); ?>/wp-admin/admin.php?page=mls_plugin_license">click here</a> to activate the license.
</p>

			
		</div>
	<?php
	}		
	
}


// Hook to initialize plugin settings.
function mls_plugin_register_settings() {
    register_setting('mls_plugin_settings_group', 'mls_plugin_api_key');
    register_setting('mls_plugin_settings_group', 'mls_plugin_client_id'); // New Client ID field
    register_setting('mls_plugin_settings_group', 'mls_plugin_filter_id_sales'); // New Filter ID Sales field
    register_setting('mls_plugin_settings_group', 'mls_plugin_filter_id_short_rentals'); // New Filter ID Short Rentals field
    register_setting('mls_plugin_settings_group', 'mls_plugin_filter_id_long_rentals'); // New Filter ID Long Rentals field
    register_setting('mls_plugin_settings_group', 'mls_plugin_filter_id_features'); // New Filter ID Features field
	register_setting('mls_plugin_settings_group', 'mls_plugin_leadformtomail'); // New Filter ID Features field
	 register_setting('mls_plugin_settings_group', 'mls_plugin_property_types', 'mls_plugin_sanitize_property_types');
	
	// Add settings section and fields.
    add_settings_section('mls_plugin_section_id', '', null, 'mls_plugin');
	add_settings_field(
        'mls_plugin_property_types', 
        'Include Only Property Types on Search', 
        'mls_plugin_property_type_filter_callback', 
        'mls_plugin_settings_page', 
        'mls_plugin_section_id'
    );

    register_setting('mls_plugin_settings_group', 'mls_plugin_default_color');
    register_setting('mls_plugin_settings_group', 'mls_plugin_blue_color');
    register_setting('mls_plugin_settings_group', 'mls_plugin_green_color');
    register_setting('mls_plugin_settings_group', 'mls_plugin_black_color');
    register_setting('mls_plugin_settings_group', 'mls_plugin_white_color');
    register_setting('mls_plugin_settings_group', 'mls_plugin_border_color');
}

add_action('admin_init', 'mls_plugin_register_settings');

// Applying colors to the frontend.
function mls_plugin_apply_custom_colors() {
    $default_color = esc_attr(get_option('mls_plugin_default_color', '#5c727d'));
    $blue_color = esc_attr(get_option('mls_plugin_blue_color', '#0073e1'));
    $green_color = esc_attr(get_option('mls_plugin_green_color', '#69c17d'));
    $black_color = esc_attr(get_option('mls_plugin_black_color', '#222'));
    $white_color = esc_attr(get_option('mls_plugin_white_color', '#ffffff'));
    $border_color = esc_attr(get_option('mls_plugin_border_color', '#e7e7e7'));
	$banner_search_background_color = esc_attr(get_option('mls_plugin_banner_search_background_color'));
	$banner_search_text_color = esc_attr(get_option('mls_plugin_banner_search_text_color'));
	$banner_search_button_color = esc_attr(get_option('mls_plugin_banner_search_button_color'));
	$banner_search_buttonhoverbackground_color = esc_attr(get_option('mls_plugin_banner_search_buttonhoverbackground_color'));
	$banner_search_buttonbackground_color = esc_attr(get_option('mls_plugin_banner_search_buttonbackground_color'));
    ?>
    <style>
    :root {
        --mlsDefault-color: <?php echo esc_attr($default_color); ?>;
        --mlsSecondary-color: <?php echo esc_attr($blue_color); ?>;
        --mlsTertiary-color: <?php echo esc_attr($green_color); ?>;
        --mlsBlack-color: <?php echo esc_attr($black_color); ?>;
        --mlsWhite-color: <?php echo esc_attr($white_color); ?>;
        --mlsBorder-color: <?php echo esc_attr($border_color); ?>;
		--mlsbannersearchbackground-color: <?php echo esc_attr($banner_search_background_color); ?>;
		--mlsbannersearchbuttonbackground-color: <?php echo esc_attr($banner_search_buttonbackground_color); ?>;
		--mlsbannersearch-color: <?php echo esc_attr($banner_search_text_color); ?>;
--mlsbannersearchbutton-color: <?php echo esc_attr($banner_search_button_color); ?>;
--mlsbannersearchbuttonhoverbackground-color: <?php echo esc_attr($banner_search_buttonhoverbackground_color); ?>;
    }
</style>
    <?php
}
add_action('wp_head', 'mls_plugin_apply_custom_colors');

// Plugin Setting Field Validation

function mls_plugin_sanitize_settings($input) {
    // Sanitize each field
    $input['mls_plugin_api_key'] = sanitize_text_field($input['mls_plugin_api_key']);
    $input['mls_plugin_client_id'] = sanitize_text_field($input['mls_plugin_client_id']);
    $input['mls_plugin_filter_id_sales'] = sanitize_text_field($input['mls_plugin_filter_id_sales']);
    $input['mls_plugin_filter_id_short_rentals'] = sanitize_text_field($input['mls_plugin_filter_id_short_rentals']);
    $input['mls_plugin_filter_id_long_rentals'] = sanitize_text_field($input['mls_plugin_filter_id_long_rentals']);
    $input['mls_plugin_filter_id_features'] = sanitize_text_field($input['mls_plugin_filter_id_features']);
	$input['mls_plugin_leadformtomail'] = sanitize_text_field($input['mls_plugin_leadformtomail']);
	 if (isset($input['mls_plugin_property_types']) && is_array($input['mls_plugin_property_types'])) {
        $input['mls_plugin_property_types'] = array_map('sanitize_text_field', $input['mls_plugin_property_types']);
    }
    
    return $input;
}

add_filter('pre_update_option_mls_plugin_settings_group', 'mls_plugin_sanitize_settings');

// Sanitize the property types field.
// function mls_plugin_sanitize_property_types($input) {
//     if (is_array($input)) {
//         return array_map('sanitize_text_field', $input);
//     }
//     return sanitize_text_field($input);
// }




// Callback for the API key field.
function mls_plugin_api_key_callback() {
    $api_key = get_option('mls_plugin_api_key');
    echo '<input type="text" name="mls_plugin_api_key" value="' . esc_attr($api_key) . '" />';
}

// Function to create a single page
function create_mls_page($pageid, $title, $slug, $shortcode) {
    $page = get_page_by_path($slug);
    
    if (!$page) {
        $page_id = wp_insert_post(
            array(
                'import_id'    => $pageid,
                'post_title'    => $title,
                'post_content'  => $shortcode,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_name'     => $slug
            )
        );
        return $page_id;
    }
    
    return $page->ID;
}

function create_mls_pages() {
    $pages = array(
        'property-list' => array(
            'title' => 'Property List',
            'shortcode' => '[mls_property_list]',
            'import_id' => '7864',
            'label' => 'MLS Listing Page'
        ),
        'property-detail' => array(
            'title' => 'Property Detail',
            'shortcode' => '[mls_property_details]',
            'import_id' => '7865',
            'label' => 'MLS Detail Page'
        ),
        'property-search-result' => array(
            'title' => 'Property Search Result',
            'shortcode' => '[mls_search_results]',
            'import_id' => '7866',
            'label' => 'MLS Search Results Page'
        )
    );

    $created_pages = array();

    foreach ($pages as $slug => $page_data) {
        $page_id = create_mls_page($page_data['import_id'], $page_data['title'], $slug, $page_data['shortcode']);
        $created_pages[$slug] = array('id' => $page_id, 'label' => $page_data['label']);
    }

    // Store page IDs and their labels in options
    update_option('mls_plugin_page_ids', $created_pages);

    return $created_pages;
}

function mls_highlight_admin_pages($title, $post_id) {
    // Get the stored page IDs and labels from the options table
    $mls_pages = get_option('mls_plugin_page_ids');

    // Check if the current post ID matches one of the created pages
    if (!is_admin() || empty($mls_pages)) {
        return $title; // Return the title unmodified if not in admin or no pages are stored
    }

    foreach ($mls_pages as $slug => $page_data) {
        if ($post_id == $page_data['id']) {
            // Append the custom label to the title
            return $title . ' (' . esc_html($page_data['label']) . ')';
        }
    }

    return $title; // Return the unmodified title if no match is found
}
add_filter('the_title', 'mls_highlight_admin_pages', 10, 2);

function mls_plugin_admin_notice() {
    $selected_page_id = get_option('mls_plugin_property_detail_page_id', '');
    
    if (empty($selected_page_id)) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>Please select a property detail page in the MLS Plugin settings. Use the shortcode <code>[mls_property_details]</code> on the selected page.</p>';
        echo '</div>';
    }
	
	/* Trial Period Notice */
$trialperiodnotice = mls_plugin_is_license_valid();
if ($trialperiodnotice) {
    $trialenabled = $trialperiodnotice['data']['trialenabled'];
    $expiration_date = $trialperiodnotice['data']['expiration_date'];
    if ($trialenabled && $expiration_date) {
        // Get the current date and time
        $current_date = new DateTime();

        // Create a DateTime object for the expiration date
        $expiration_date_time = new DateTime($expiration_date);

        // Check if the expiration date is in the future
        if ($current_date < $expiration_date_time) {
            // Calculate the exact difference in seconds
            $difference_in_seconds = $expiration_date_time->getTimestamp() - $current_date->getTimestamp();

            // Convert the difference into days, hours, minutes, and seconds
            $days_left = floor($difference_in_seconds / 86400);
            $hours_left = floor(($difference_in_seconds % 86400) / 3600);
            $minutes_left = floor(($difference_in_seconds % 3600) / 60);
            $seconds_left = $difference_in_seconds % 60;

            echo '<div class="mls-trial-notice notice notice-warning is-dismissible">';
            if ($days_left > 1) {
                // Show days if more than a day is left
                echo '<p>Resale Online MLS Plugin: You are using the trial period. Your trial ends in <b>' . $days_left . ' days.</b></p>';
            }
			elseif ($days_left < 2 && $days_left > 0) {
                // Show days if more than a day is left
                echo '<p>Resale Online MLS Plugin: You are using the trial period. Your trial ends in <b>' . $days_left . ' day.</b></p>';
            }
			else {
                // Show detailed time if less than a day is left
//                 echo '<p>Resale Online MLS Plugin: You are using the trial period. Your trial ends in <b>' . $hours_left . ' hours, ' . $minutes_left . ' minutes, and ' . $seconds_left . ' seconds.</b></p>';
				echo '<p>Resale Online MLS Plugin: You are using the trial period. Your trial ends in <b>' . $hours_left . ' hours, ' . $minutes_left . ' minutes.</b></p>';
            }
            echo '</div>';
        } else {
            echo '<div class="mls-trial-notice notice notice-warning is-dismissible">';
            echo '<p>Resale Online MLS Plugin: Your trial period has ended. Please <a href="https://clarkdigital.es/resales-online-plugin/#contactplugin" target="_blank">contact us</a> to complete the renewal process and reactivate your license.</p>';
            echo '</div>';
        }
    }
}
	
}
add_action('admin_notices', 'mls_plugin_admin_notice');

?>