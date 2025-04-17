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
	// 	API Connection setting update code
    if (isset($_POST['mls_plugin_save_connection_settings'])) {
        // Process the "Connection with Resales Online" form
        update_option('mls_plugin_api_key', sanitize_text_field($_POST['mls_plugin_api_key']));
        update_option('mls_plugin_client_id', sanitize_text_field($_POST['mls_plugin_client_id']));
        update_option('mls_plugin_filter_id_sales', sanitize_text_field($_POST['mls_plugin_filter_id_sales']));
		update_option('mls_plugin_filter_id_short_rentals', sanitize_text_field($_POST['mls_plugin_filter_id_short_rentals']));
		update_option('mls_plugin_filter_id_long_rentals', sanitize_text_field($_POST['mls_plugin_filter_id_long_rentals']));
		update_option('mls_plugin_filter_id_features', sanitize_text_field($_POST['mls_plugin_filter_id_features']));
		update_option('mls_plugin_style_propdetailpagehide', sanitize_text_field($_POST['mls_plugin_style_propdetailpagehide']));
		update_option('mls_plugin_style_proplanghide', sanitize_text_field($_POST['mls_plugin_style_proplanghide']));
		update_option('mls_plugin_property_detail_page_slug', sanitize_text_field($_POST['mls_plugin_property_detail_page_slug']));
		
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
// 		clear cache & sync latest data from resle online
		mls_plugin_refresh_locations();
    } 
	
// 	Style color setting update code
	elseif (isset($_POST['mls_plugin_save_style_settings'])) {
        // Process the "Styles" form
        // Update options for all the light theme inputs
update_option('mls_plugin_primary_color', sanitize_text_field($_POST['mls_plugin_primary_color']));
update_option('mls_plugin_secondary_color', sanitize_text_field($_POST['mls_plugin_secondary_color']));
update_option('mls_plugin_text_color', sanitize_text_field($_POST['mls_plugin_text_color']));
update_option('mls_plugin_black_color', sanitize_text_field($_POST['mls_plugin_black_color']));

update_option('mls_plugin_bg_grey_color', sanitize_text_field($_POST['mls_plugin_bg_grey_color']));
update_option('mls_plugin_bg_white_color', sanitize_text_field($_POST['mls_plugin_bg_white_color']));
update_option('mls_plugin_bg_dark_color', sanitize_text_field($_POST['mls_plugin_bg_dark_color']));
update_option('mls_plugin_border_color', sanitize_text_field($_POST['mls_plugin_border_color']));
update_option('mls_plugin_border_dark_color', sanitize_text_field($_POST['mls_plugin_border_dark_color']));
update_option('mls_plugin_link_color', sanitize_text_field($_POST['mls_plugin_link_color']));
update_option('mls_plugin_link_hover_color', sanitize_text_field($_POST['mls_plugin_link_hover_color']));
update_option('mls_plugin_button_color', sanitize_text_field($_POST['mls_plugin_button_color']));
update_option('mls_plugin_button_bg_color', sanitize_text_field($_POST['mls_plugin_button_bg_color']));
update_option('mls_plugin_button_border_color', sanitize_text_field($_POST['mls_plugin_button_border_color']));
update_option('mls_plugin_button_hover_color', sanitize_text_field($_POST['mls_plugin_button_hover_color']));
update_option('mls_plugin_button_bg_hover_color', sanitize_text_field($_POST['mls_plugin_button_bg_hover_color']));
update_option('mls_plugin_button_border_hover_color', sanitize_text_field($_POST['mls_plugin_button_border_hover_color']));
update_option('mls_plugin_banner_search_color', sanitize_text_field($_POST['mls_plugin_banner_search_color']));
update_option('mls_plugin_banner_search_bg_color', sanitize_text_field($_POST['mls_plugin_banner_search_bg_color']));
update_option('mls_plugin_banner_search_btn_color', sanitize_text_field($_POST['mls_plugin_banner_search_btn_color']));
update_option('mls_plugin_banner_search_btn_bg_color', sanitize_text_field($_POST['mls_plugin_banner_search_btn_bg_color']));
update_option('mls_plugin_banner_search_btn_hover_bg_color', sanitize_text_field($_POST['mls_plugin_banner_search_btn_hover_bg_color']));
update_option('mls_plugin_banner_search_tabcolor', sanitize_text_field($_POST['mls_plugin_banner_search_tabcolor']));
update_option('mls_plugin_banner_search_tabbackgroundcolor', sanitize_text_field($_POST['mls_plugin_banner_search_tabbackgroundcolor']));
// Update options for dark theme colors
update_option('mls_plugin_dark_primary_color', sanitize_text_field($_POST['mls_plugin_dark_primary_color']));
update_option('mls_plugin_dark_secondary_color', sanitize_text_field($_POST['mls_plugin_dark_secondary_color']));
update_option('mls_plugin_dark_text_color', sanitize_text_field($_POST['mls_plugin_dark_text_color']));
update_option('mls_plugin_dark_black_color', sanitize_text_field($_POST['mls_plugin_dark_black_color']));
update_option('mls_plugin_dark_bg_grey_color', sanitize_text_field($_POST['mls_plugin_dark_bg_grey_color']));
update_option('mls_plugin_dark_bg_white_color', sanitize_text_field($_POST['mls_plugin_dark_bg_white_color']));
update_option('mls_plugin_dark_bg_dark_color', sanitize_text_field($_POST['mls_plugin_dark_bg_dark_color']));
update_option('mls_plugin_dark_border_color', sanitize_text_field($_POST['mls_plugin_dark_border_color']));
update_option('mls_plugin_dark_border_dark_color', sanitize_text_field($_POST['mls_plugin_dark_border_dark_color']));
update_option('mls_plugin_dark_link_color', sanitize_text_field($_POST['mls_plugin_dark_link_color']));
update_option('mls_plugin_dark_link_hover_color', sanitize_text_field($_POST['mls_plugin_dark_link_hover_color']));
update_option('mls_plugin_dark_button_color', sanitize_text_field($_POST['mls_plugin_dark_button_color']));
update_option('mls_plugin_dark_button_bg_color', sanitize_text_field($_POST['mls_plugin_dark_button_bg_color']));
update_option('mls_plugin_dark_button_border_color', sanitize_text_field($_POST['mls_plugin_dark_button_border_color']));
update_option('mls_plugin_dark_button_hover_color', sanitize_text_field($_POST['mls_plugin_dark_button_hover_color']));
update_option('mls_plugin_dark_button_bg_hover_color', sanitize_text_field($_POST['mls_plugin_dark_button_bg_hover_color']));
update_option('mls_plugin_dark_button_border_hover_color', sanitize_text_field($_POST['mls_plugin_dark_button_border_hover_color']));
update_option('mls_plugin_dark_banner_search_color', sanitize_text_field($_POST['mls_plugin_dark_banner_search_color']));
update_option('mls_plugin_dark_banner_search_bg_color', sanitize_text_field($_POST['mls_plugin_dark_banner_search_bg_color']));
update_option('mls_plugin_dark_banner_search_btn_color', sanitize_text_field($_POST['mls_plugin_dark_banner_search_btn_color']));
update_option('mls_plugin_dark_banner_search_btn_bg_color', sanitize_text_field($_POST['mls_plugin_dark_banner_search_btn_bg_color']));
update_option('mls_plugin_dark_banner_search_btn_hover_bg_color', sanitize_text_field($_POST['mls_plugin_dark_banner_search_btn_hover_bg_color']));
update_option('mls_plugin_dark_banner_search_tabcolor', sanitize_text_field($_POST['mls_plugin_dark_banner_search_tabcolor']));
update_option('mls_plugin_dark_banner_search_tabbackgroundcolor', sanitize_text_field($_POST['mls_plugin_dark_banner_search_tabbackgroundcolor']));

// 	layout update option	
		update_option('mls_def_prop_layout', sanitize_text_field($_POST['mls_def_prop_layout']));
		update_option('mls_plugin_style_breadcrumbhide', sanitize_text_field($_POST['mls_plugin_style_breadcrumbhide']));
		update_option('mls_plugin_style_darklighthide', sanitize_text_field($_POST['mls_plugin_style_darklighthide']));
		update_option('mls_plugin_prop_detailsidebaroffset', sanitize_text_field($_POST['mls_plugin_prop_detailsidebaroffset']));
		update_option('mls_plugin_tabs_to_display', array_map('sanitize_text_field', $_POST['mls_plugin_tabs_to_display'] ?? ['sales']));

// 	fontsize update option 
update_option('mls_plugin_google_font', sanitize_text_field($_POST['mls_plugin_google_font']));
update_option('mls_custom_font_file', sanitize_text_field($_POST['mls_custom_font_file']));
update_option('mls_plugin_fontfamily', sanitize_text_field($_POST['mls_plugin_fontfamily']));
	update_option('mls_plugin_paragraph_fontsize', sanitize_text_field($_POST['mls_plugin_paragraph_fontsize']));
    update_option('mls_plugin_lg_fontsize', sanitize_text_field($_POST['mls_plugin_lg_fontsize']));
    update_option('mls_plugin_md_fontsize', sanitize_text_field($_POST['mls_plugin_md_fontsize']));
    update_option('mls_plugin_sm_fontsize', sanitize_text_field($_POST['mls_plugin_sm_fontsize']));
    update_option('mls_plugin_button_fontsize', sanitize_text_field($_POST['mls_plugin_button_fontsize']));
    update_option('mls_plugin_filter_form_heading', sanitize_text_field($_POST['mls_plugin_filter_form_heading']));
    update_option('mls_plugin_property_list_heading', sanitize_text_field($_POST['mls_plugin_property_list_heading']));
    update_option('mls_plugin_property_list_price_heading', sanitize_text_field($_POST['mls_plugin_property_list_price_heading']));
    update_option('mls_plugin_property_single_heading', sanitize_text_field($_POST['mls_plugin_property_single_heading']));
    update_option('mls_plugin_property_single_section_heading', sanitize_text_field($_POST['mls_plugin_property_single_section_heading']));
    update_option('mls_plugin_property_single_price_heading', sanitize_text_field($_POST['mls_plugin_property_single_price_heading']));
		
    } 
	// 	lead form setting update code
	elseif ( isset($_POST['mls_plugin_save_lead_form_settings']) ) {
		// Sanitize and update available timings
    if (isset($_POST['mls_plugin_available_timings']) && is_array($_POST['mls_plugin_available_timings'])) {
    $selected_timings = array_map('sanitize_text_field', $_POST['mls_plugin_available_timings']);
    } else { $selected_timings = []; }
    update_option('mls_plugin_available_timings', $selected_timings);
	update_option('mls_plugin_leadformvideohide', sanitize_text_field($_POST['mls_plugin_leadformvideohide']));
		 $selected_languages = array_map('sanitize_text_field', $_POST['mls_plugin_languages']);
    update_option('mls_plugin_languages', $selected_languages);
    update_option('mls_plugin_custom_languages', sanitize_text_field($_POST['mls_plugin_custom_languages'])); 
		update_option('mls_plugin_leadformheading', sanitize_text_field($_POST['mls_plugin_leadformheading'])); 
		update_option('mls_plugin_leadformscheduledatehide', sanitize_text_field($_POST['mls_plugin_leadformscheduledatehide'])); 
		update_option('mls_plugin_leadformlanghide', sanitize_text_field($_POST['mls_plugin_leadformlanghide'])); 
		update_option('mls_plugin_leadformbuyersellerhide', sanitize_text_field($_POST['mls_plugin_leadformbuyersellerhide'])); 
		
    }
	// email configuration setting update code
	elseif (isset($_POST['mls_plugin_save_email_config_settings']) ) {
		
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
		
    }
	// map setting update code
	elseif (isset($_POST['mls_plugin_save_map_settings'])) {
    // Process the "Map Integration" form
    update_option('mls_plugin_maphide', sanitize_text_field($_POST['mls_plugin_maphide']));
    if (isset($_POST['mls_plugin_map_provider'])) {
        update_option('mls_plugin_map_provider', sanitize_text_field($_POST['mls_plugin_map_provider']));
    }
		
	}
	// advanced setting update code
	elseif (isset($_POST['mls_plugin_save_advanced_settings'])) {
    update_option('mls_plugin_weblink_structure', sanitize_text_field($_POST['mls_plugin_weblink_structure']));
		
	}
// 	Language update code
	elseif (isset($_POST['mls_plugin_save_prop_language_settings'])) {
    
    update_option('mls_plugin_prop_language', sanitize_text_field($_POST['mls_plugin_prop_language']));
	update_option('mls_plugin_style_proplanghide', sanitize_text_field($_POST['mls_plugin_style_proplanghide']));
		
	}
    // You can add more conditions for other tabs here...
}
	
    ?>
    <!-- Our admin page content should all be inside .wrap -->
    <div class="wrap mls-custom-admin-style">
        <!-- Print the page title -->
        <h1 class="mls-ap-heading-style1"><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="mls-innersection-style1">
        <!-- Here are our tabs -->
        <nav class="nav-tab-wrapper mls-admin-navtab">
            <a href="?page=mls_plugin_settings" class="nav-tab <?php if ($tab === null): ?>nav-tab-active<?php endif; ?>">Connection with Resales Online</a>
            <a href="?page=mls_plugin_settings&tab=styles" class="nav-tab <?php if ($tab === 'styles'): ?>nav-tab-active<?php endif; ?>">Styles</a>
            <a href="?page=mls_plugin_settings&tab=map_integration" class="nav-tab <?php if ($tab === 'map_integration'): ?>nav-tab-active<?php endif; ?>">Integration with Map</a>
            <a href="?page=mls_plugin_settings&tab=lead_form" class="nav-tab <?php if ($tab === 'lead_form'): ?>nav-tab-active<?php endif; ?>">Lead Form</a>
			<a href="?page=mls_plugin_settings&tab=lead_form_email_config" class="nav-tab <?php if ($tab === 'lead_form_email_config'): ?>nav-tab-active<?php endif; ?>">Email Configuration</a>
            <a href="?page=mls_plugin_settings&tab=language" class="nav-tab <?php if ($tab === 'language'): ?>nav-tab-active<?php endif; ?>">Language</a>
			<a href="?page=mls_plugin_settings&tab=advanced" class="nav-tab <?php if ($tab === 'advanced'): ?>nav-tab-active<?php endif; ?>">Advanced</a>
        </nav>

        <div class="tab-content">
        
            <?php
           

// Render the content for each tab based on the active tab
switch ($tab) {
case 'styles':
?>
<h2>MLS Plugin Style Setting</h2>
<form method="post">
<table class="form-table ft-flex">
<tr valign="top">
    <th scope="row">Enable Dark Theme</th>
    <td>
      <section class="toggle-Section">
          <label class="mls-switch">
              <input type="checkbox" id="tog-darklight-hide" name="mls_plugin_style_darklighthide" value="1" <?php checked(get_option('mls_plugin_style_darklighthide', ''), '1'); ?>>
              <span class="mls-tog-slider round"></span>
          </label>
       </section>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Toggle to enable or disable the dark theme</p>
			</div>                            
		</div>
    </td>
</tr>
<tr valign="top" class="tog-light-row mls-row-heading"><th colspan="2"><h2>Styling Options</h2></th></tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>General Colors</h3></th></tr>
<!-- Light Theme -->
<tr valign="top" class="tog-light-row">
    <th scope="row">Primary Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_primary_color" value="<?php echo esc_attr(get_option('mls_plugin_primary_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the main accent color used across the site</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Secondary Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_secondary_color" value="<?php echo esc_attr(get_option('mls_plugin_secondary_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the secondary accent color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Text Colors</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Body Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_text_color" value="<?php echo esc_attr(get_option('mls_plugin_text_color', '#5c727d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for regular text</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Title Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_black_color" value="<?php echo esc_attr(get_option('mls_plugin_black_color', '#222222')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for titles</p>
			</div>                            
		</div>
	</td>
</tr>

<tr valign="top" class="tog-light-row">
    <th scope="row">Link Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_link_color" value="<?php echo esc_attr(get_option('mls_plugin_link_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the color of text links</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Link Hover Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_link_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_link_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the color when hovering over text links</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Background Colors</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Blocks Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_bg_grey_color" value="<?php echo esc_attr(get_option('mls_plugin_bg_grey_color', '#f7f7f7')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for overall blocks backgrounds</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Form Fields & Property List Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_bg_white_color" value="<?php echo esc_attr(get_option('mls_plugin_bg_white_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines background for form fields and property lists</p>
			</div>                            
		</div>
	</td>
</tr>

<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Border Colors</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_border_color" value="<?php echo esc_attr(get_option('mls_plugin_border_color', '#cccccc')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for block and field borders</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-roww" style="display: none">
    <th scope="row">Dark Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_border_dark_color" value="<?php echo esc_attr(get_option('mls_plugin_border_dark_color', '#828282')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places:<b></b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row mls-row-heading"><th colspan="2"><h2>Button Styles</h2></th></tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Default Button Colors</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_color" value="<?php echo esc_attr(get_option('mls_plugin_button_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on buttons</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_button_bg_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button background color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_border_color" value="<?php echo esc_attr(get_option('mls_plugin_button_border_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button border color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Hover Button Colors</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Hover Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_button_hover_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Hover Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_bg_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_button_bg_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button background color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Hover Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_button_border_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_button_border_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button border color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Miscellaneous Buttons</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Reset & Pagination Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_bg_dark_color" value="<?php echo esc_attr(get_option('mls_plugin_bg_dark_color', '#ededed')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for Reset button, Back button, Pagination button and Select option checkmark</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row mls-row-heading"><th colspan="2"><h2>Search Form Styles</h2></th></tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Property Search Form</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Tab Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_tabcolor" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_tabcolor', '#0073E1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on search tabs</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Tab Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_tabbackgroundcolor" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_tabbackgroundcolor', '#E5F1FC')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color for search tabs</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row"> <th scope="row" colspan="2"><h3>Home Banner Search Form</h3></th></tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for text in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_bg_color', '#f7f7f7')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background for the search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Button Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_btn_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_btn_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color of buttons in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Button Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_btn_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_btn_bg_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color of buttons in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-light-row">
    <th scope="row">Hover Button Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_banner_search_btn_hover_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_banner_search_btn_hover_bg_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color for buttons on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row mls-row-heading"><th scope="row" colspan="2"><h2>Styling Options</h2></th></tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>General Colors</h3></th></tr>
<!-- Dark Theme -->
<tr valign="top" class="tog-dark-row">
    <th scope="row">Primary Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_primary_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_primary_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the main accent color used across the site</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Secondary Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_secondary_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_secondary_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the secondary accent color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Text Colors</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Body Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_text_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_text_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for regular text</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Titles Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_black_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_black_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for titles</p>
			</div>                            
		</div>
	</td>
</tr>

<tr valign="top" class="tog-dark-row">
    <th scope="row">Link Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_link_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_link_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the color of text links</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Link Hover Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_link_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_link_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the color when hovering over text links</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Background Colors</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Blocks Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_bg_grey_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_bg_grey_color', '#434343')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for overall blocks backgrounds</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Form Fields & Property List Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_bg_white_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_bg_white_color', '#434343')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines background for form fields and property lists</p>
			</div>                            
		</div>
	</td>
</tr>

<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Border Colors</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_border_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_border_color', '#6c6c6c')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for block and field borders</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-roww" style="display: none;">
    <th scope="row">Border Dark Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_border_dark_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_border_dark_color', '#adadad')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b></b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="mls-row-heading tog-dark-row"><th colspan="2"><h2>Button Styles</h2></th></tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Default Button Colors</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on buttons</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_bg_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button background color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_border_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_border_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button border color</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Hover Button Colors</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Hover Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_hover_color', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Hover Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_bg_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_bg_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button background color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Hover Border Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_button_border_hover_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_button_border_hover_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button border color on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Miscellaneous Buttons</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Reset & Pagination Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_bg_dark_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_bg_dark_color', '#383838')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for Reset button, Back button, Pagination button and Select option checkmark</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row mls-row-heading"><th colspan="2"><h2>Search Form Styles</h2></th></tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Property Search Form</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Tab Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_tabcolor" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_tabcolor', '#ffffff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color on search tabs</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Tab Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_tabbackgroundcolor" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_tabbackgroundcolor', '#585858')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color for search tabs</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row"> <th scope="row" colspan="2"><h3>Home Banner Search Form</h3></th></tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_color', '#fff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Used for text in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_bg_color', '#f7f7f7')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background for the search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Button Text Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_btn_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_btn_color', '#fff')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the text color of buttons in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Button Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_btn_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_btn_bg_color', '#0073e1')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color of buttons in the banner search form</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="tog-dark-row">
    <th scope="row">Hover Button Background Color</th>
    <td><input type="text" class="mls-color-field" data-alpha-enabled="true" name="mls_plugin_dark_banner_search_btn_hover_bg_color" value="<?php echo esc_attr(get_option('mls_plugin_dark_banner_search_btn_hover_bg_color', '#69c17d')); ?>" />
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the background color for buttons on hover</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="mls-row-heading"><th colspan="2"><h2>Typography</h2></th></tr>
<tr valign="top"> <th scope="row" colspan="2"><h3>Font Family</h3></th></tr>
<tr valign="top">
    <th scope="row">Select your Font Family</th>
    <td class="mls-fz-col">
		<?php $saved_font_family = get_option('mls_plugin_fontfamily', 'Default'); ?>
        <label class="mls-custom-radio">Default Font from your Theme
            <input type="radio" name="mls_plugin_fontfamily" id="Default" value="Default" 
            <?php echo ($saved_font_family === 'Default') ? 'checked' : ''; ?> /><span class="checkmark"></span>
        </label>
        <label class="mls-custom-radio">Google Font
            <input type="radio" name="mls_plugin_fontfamily" id="google" value="google" 
            <?php echo ($saved_font_family === 'google') ? 'checked' : ''; ?> /><span class="checkmark"></span>
            
        </label>
        <label class="mls-custom-radio">Custom Font
            <input type="radio" name="mls_plugin_fontfamily" id="custom" value="custom" 
            <?php echo ($saved_font_family === 'custom') ? 'checked' : ''; ?> /><span class="checkmark"></span>
        </label>
    </td>
</tr>
<tr valign="top" class="mls-gfonts-wrap">
    <th scope="row">Select your Font Family from Google fonts</th>
    <td class="mls-fz-col"><?php mls_plugin_google_fonts_dropdown(); ?>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Select the font family for overall MLS plugin texts</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="mls-cfonts-wrap">
    <th scope="row">Upload your Custom Font Family</th>
    <td class="mls-fz-col">
		<input type="text" id="mls_custom_font_url" name="mls_custom_font_file" value="<?php echo esc_url(get_option('mls_custom_font_file', '')); ?>" placeholder="Font URL" style="width: 70%;">
   <button type="button" class="button button-secondary" id="mls_upload_custom_font">Upload Font</button>
   <p class="description">Upload your font file (WOFF, WOFF2, TTF, or OTF) using the button above.</p>
	</td>
</tr>
<tr valign="top"><th scope="row" colspan="2"><h3>Font Size</h3></th></tr>
<tr valign="top">
    <th scope="row">Paragraph Font Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_paragraph_fontsize" value="<?php echo esc_attr(get_option('mls_plugin_paragraph_fontsize', '18')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective pages: <b>Paragraph & List [Property Search, List Property, Single Property]</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Small Font Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_sm_fontsize" value="<?php echo esc_attr(get_option('mls_plugin_sm_fontsize', '12')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>Error Message Text, Tooltip Text, Single Property Label</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Medium Font Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_md_fontsize" value="<?php echo esc_attr(get_option('mls_plugin_md_fontsize', '14')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>Labels</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Large Font Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_lg_fontsize" value="<?php echo esc_attr(get_option('mls_plugin_lg_fontsize', '16')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>Form Fields, Clear & Select All Option Button, No Property Message</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Button Font Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_button_fontsize" value="<?php echo esc_attr(get_option('mls_plugin_button_fontsize', '15')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the button font size</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top"> <th scope="row" colspan="2"><h3>Titles Font Size</h3></th></tr>
<tr valign="top">
    <th scope="row">Single Property Title</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_property_single_heading" value="<?php echo esc_attr(get_option('mls_plugin_property_single_heading', '28')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the single property title size</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Search Form & List Property Title</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_filter_form_heading" value="<?php echo esc_attr(get_option('mls_plugin_filter_form_heading', '24')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the property search form title & list property title sizes</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" style="display: none;">
    <th scope="row">List Property Heading Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_property_list_heading" value="<?php echo esc_attr(get_option('mls_plugin_property_list_heading', '24')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>List Property Heading</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top">
    <th scope="row">Property Price & Single Property Blocks Title</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_property_list_price_heading" value="<?php echo esc_attr(get_option('mls_plugin_property_list_price_heading', '22')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>Defines the single property price title & single property block title sizes</p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" style="display: none;">
    <th scope="row">Single Property Price Heading Size</th>
	<td class="mls-fz-col"><input type="number" name="mls_plugin_property_single_price_heading" value="<?php echo esc_attr(get_option('mls_plugin_property_single_price_heading', '24')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>Single Property Price Title</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" style="display: none;">
    <th scope="row">Single Property Section Heading Size</th>
    <td class="mls-fz-col"><input type="number" name="mls_plugin_property_single_section_heading" value="<?php echo esc_attr(get_option('mls_plugin_property_single_section_heading', '22')); ?>" /><span>px</span>
		<div class="mls-admin-info-wrap">
			<span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
			<div class="mls-admin-info-toggle" style="display: none;">
				<p>This is reflected in the following respective places: <b>Single Property Section Heading</b></p>
			</div>                            
		</div>
	</td>
</tr>
<tr valign="top" class="mls-row-heading"><th colspan="2"><h2>Property Layout</h2></th></tr>
<tr valign="top"> <th scope="row" colspan="2"><h3>Property Layout Setting</h3></th></tr>
<tr valign="top">
    <?php $mls_def_prop_layout = get_option('mls_def_prop_layout'); ?>
    <th scope="row">Property Listing Layout</th>
    <td>
        <div class="mls_def_layout">
            <select name="mls_def_prop_layout" id="mls_def_prop_layout" >
			<option value="pro-list" <?php selected($mls_def_prop_layout, 'pro-list'); ?>>List</option>
            <option value="cols2" <?php selected($mls_def_prop_layout, 'cols2'); ?>>Grid - 2 Column</option>
			<option value="cols3" <?php selected($mls_def_prop_layout, 'cols3'); echo empty($mls_def_prop_layout) ? ' selected' : ''; ?>>Grid - 3 Column</option>
			<option value="cols4" <?php selected($mls_def_prop_layout, 'cols4'); ?>>Grid - 4 Column</option>
			<option value="cols5" <?php selected($mls_def_prop_layout, 'cols5'); ?>>Grid - 5 Column</option>
				


        </select>
        </div>
      <p class="description note-style">Select to set the Default Property Layout.</p>  
    </td>
</tr>
<tr valign="top">
    <th scope="row">Hide Breadcrumb</th>
    <td>
     <section class="toggle-Section">
          <label class="mls-switch">
          <input type="checkbox" id="tog-breadcrumb-hide" name="mls_plugin_style_breadcrumbhide" value="1" <?php checked(get_option('mls_plugin_style_breadcrumbhide', '1'), '1'); ?>>
          <span class="mls-tog-slider round"></span>
          </label>
       </section>

    </td>
</tr>
<tr valign="top">
    <th scope="row">Sticky Sidebar Offset</th>
    <td><input type="number" name="mls_plugin_prop_detailsidebaroffset" value="<?php echo esc_attr(get_option('mls_plugin_prop_detailsidebaroffset', '20')); ?>" /></td>
</tr>
<tr valign="top" class="mls-row-heading"><th colspan="2"><h2>Search Form Settings</h2></th></tr>
<tr valign="top"> <th scope="row" colspan="2"><h3>Tabs to Display</h3></th></tr>
<tr valign="top">
<th scope="row">Select Tabs to Display</th>
    <td>
        <div class="mls-custom-checkbox-group">
        <?php
        $tab_options = ['sales', 'long_rentals', 'short_rentals', 'new_development'];
        $selected_tabs = get_option('mls_plugin_tabs_to_display', ['sales']); // Default to 'sales'

        foreach ($tab_options as $option) {
            $checked = in_array($option, $selected_tabs) ? 'checked' : '';
            echo '<div class="mls-custom-checkbox">
                    <input type="checkbox" name="mls_plugin_tabs_to_display[]" value="' . esc_attr($option) . '" ' . $checked . ' /> <label>' . ucfirst(str_replace('_', ' ', $option)) . '</label></div>';
        }
        ?>
        </div>
    </td>
</tr>
</table>
                    <p class="submit">
        <input type="submit" name="mls_plugin_save_style_settings" value="Save Changes" class="button button-primary" />
		</p>
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
		<div class="mls-custom-checkbox-group">
			<div class="mls-custom-checkbox">
        <input type="checkbox" name="mls_plugin_maphide" value="1" <?php checked(get_option('mls_plugin_maphide'), '1'); ?> />
				<label></label>
			</div>
		</div>
        <p class="description note-style">Check to hide the Map Section in Property detail page.</p>
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
		<p class="submit">
        <input type="submit" name="mls_plugin_save_map_settings" value="Save Changes" class="button button-primary" />
		</p>
    </form>
    <?php
    break;
		
		case 'advanced':
    ?>
    <h2>Advanced Settings</h2>
    <form method="post" action="">
        <table class="form-table">
			
			<tr valign="top">
    <th scope="row">Select Weblink Structure</th>
    <td class="basadv-col">
		<div class="mls-fz-col">
		<?php $weblink_structure = get_option('mls_plugin_weblink_structure', 'weblink_advanced'); ?>
		<label class="mls-custom-radio">Basic
            <input type="radio" name="mls_plugin_weblink_structure" id="weblink_basic" value="weblink_basic" 
            <?php echo ($weblink_structure === 'weblink_basic') ? 'checked' : ''; ?> /><span class="checkmark"></span>
        </label>
        <label class="mls-custom-radio">Advanced
            <input type="radio" name="mls_plugin_weblink_structure" id="weblink_advanced" value="weblink_advanced" 
            <?php echo ($weblink_structure === 'weblink_advanced') ? 'checked' : ''; ?> /><span class="checkmark"></span>
        </label>
		</div>
		<div class="basadv-result">
			<p class="mls_plugin_weblink_structure_advanced">https://site.com/page-slug/property-title/referenceID/?type=filterID&lang=LanguageID</p>
			<p class="mls_plugin_weblink_structure_basic">https://site.com/page-slug/?id=referenceID&lang=LanguageID&type=filterID</p>
		</div>
		<p class="description note-style"><b>Note:</b> Make sure to clear permalink cache (under Settings >> Permalink >> click Save changes button without changing any option) once after changing the link structure. </p>
    </td>
</tr>

        </table>
		<p class="submit">
        <input type="submit" name="mls_plugin_save_advanced_settings" value="Save Changes" class="button button-primary" />
		</p>
    </form>
    <?php
    break;


                case 'lead_form':
                    ?>
<h2>Lead Form Setting</h2>                    
                    <form method="post">
                    <table class="form-table easySel-style">
						<tr valign="top">
    <th scope="row">Lead Form Heading</th>
    <td>
        <input type="text" name="mls_plugin_leadformheading" value="<?php echo esc_attr(get_option('mls_plugin_leadformheading', 'Book a Viewing')); ?>" />
    </td>
</tr>
						<tr valign="top">
    <th scope="row">Hide Tour Type field</th>
    <td>
		<section class="toggle-Section">
      <label class="mls-switch">
      <input type="checkbox" id="tog-tour-hide" name="mls_plugin_leadformvideohide" value="1" <?php checked(get_option('mls_plugin_leadformvideohide'), '1'); ?> >
      <span class="mls-tog-slider round"></span>
      </label>
   </section>
      
        <p class="description note-style">Check to hide the Tour Type field (Video and In Person options) in the Lead Form. By default, the value will be set to 'Person' and stored in the submission.</p>
    </td>
</tr>
						<tr valign="top">
    <th scope="row">Hide Schedule date and Available Timing fields</th>
    <td>
 <section class="toggle-Section">
      <label class="mls-switch">
      <input type="checkbox" id="tog-timing-hide" name="mls_plugin_leadformscheduledatehide" value="1" <?php checked(get_option('mls_plugin_leadformscheduledatehide'), '1'); ?>>
      <span class="mls-tog-slider round"></span>
      </label>
   </section>
        
    </td>
</tr>
						
						<tr valign="top" class="tog-timing-row">
                            <th scope="row">Available Timing</th>
                            <td>
                    <?php mls_plugin_leadform_timing(); ?>
</td>
                        </tr>
						<tr valign="top">
    <th scope="row">Hide Preferred Languages field</th>
    <td>
 <section class="toggle-Section">
      <label class="mls-switch">
      <input type="checkbox"  id="tog-lang-hide" name="mls_plugin_leadformlanghide" value="1" <?php checked(get_option('mls_plugin_leadformlanghide'), '1'); ?>>
      <span class="mls-tog-slider round"></span>
      </label>
   </section>
    </td>
</tr>
						<tr valign="top" class="tog-lang-row">
                            <th scope="row">Preferred Languages</th>
                            <td class="mlspreflang mls-lf-pl">
								
                    <?php mls_plugin_display_language_setting_options(); ?>
</td>
                        </tr>
						<tr valign="top">
    <th scope="row">Hide Buyer/Seller field</th>
    <td>
 <section class="toggle-Section">
      <label class="mls-switch">
      <input type="checkbox"  id="tog-bsa-hide"  name="mls_plugin_leadformbuyersellerhide" value="1" <?php checked(get_option('mls_plugin_leadformbuyersellerhide'), '1'); ?>>
      <span class="mls-tog-slider round"></span>
      </label>
   </section>
       
    </td>
</tr>
                        
                    </table>
                    <p class="submit">
        <input type="submit" name="mls_plugin_save_lead_form_settings" value="Save Changes" class="button button-primary" />
		</p>
                </form>
                    <?php
                    break;
					
					case 'lead_form_email_config':
                    ?>
<h2>Email Configuration Setting</h2>                    
                    <form method="post">
                    <table class="form-table easySel-style">
						
						<tr valign="top">
                            <th scope="row">To Email Address</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformtomail" value="<?php echo esc_attr(get_option('mls_plugin_leadformtomail')); ?>" placeholder="Enter emails separated by commas" />
                    <p class="description note-style">You can enter multiple email addresses separated by comma.</p>
                </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">CC Email Address</th>
                            <td>
                    <input type="text" name="mls_plugin_leadformccmail" value="<?php echo esc_attr(get_option('mls_plugin_leadformccmail')); ?>" placeholder="Enter emails separated by commas" />
                    <p class="description note-style">You can enter multiple email addresses separated by comma.</p>
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
                    <p class="submit">
        <input type="submit" name="mls_plugin_save_email_config_settings" value="Save Changes" class="button button-primary" />
		</p>
                </form>
                    <?php
                    break;

                case 'language':
                    ?>
                    <h2>Language Settings</h2>
                    <!-- Add content for Language settings here -->
			<form method="post" action="">
        <table class="form-table">
						<tr valign="top">
                            <th scope="row">Enable Multi-Language</th>
                            <td>
							 <section class="toggle-Section">
								  <label class="mls-switch">
								  <input type="checkbox" id="tog-proplang-hide" name="mls_plugin_style_proplanghide" value="1" <?php checked(get_option('mls_plugin_style_proplanghide'), '1'); ?>>
								  <span class="mls-tog-slider round"></span>
								  </label>
							   </section>
								<p class="description note-style">Enable this toggle if your site is multi-language.</p>
								<p class="description note-style"><b>Warning:</b> Enabling this toggle will hide the dropdown for 'Property types, Property Language, Property Detail Page'.<br>You have to add the language attributes in each language pages for all shortcodes, enter the slug for property detail page and All Property types will show directly in the filters based on the language attributes.</p>
							   <div class="proplang-note proplang-table">
								   <table border="1" cellpadding="5" cellspacing="0" class="wp-list-table widefat fixed mls-table-theme"><thead><tr><th>Language</th><th>Language ID</th></tr></thead><tbody><tr><td>English</td><td>1</td></tr><tr><td>Spanish</td><td>2</td></tr><tr><td>German</td><td>3</td></tr><tr><td>French</td><td>4</td></tr><tr><td>Dutch</td><td>5</td></tr><tr><td>Danish</td><td>6</td></tr><tr><td>Russian</td><td>7</td></tr><tr><td>Swedish</td><td>8</td></tr><tr><td>Polish</td><td>9</td></tr><tr><td>Norwegian</td><td>10</td></tr><tr><td>Turkish</td><td>11</td></tr><tr><td>Finnish</td><td>13</td></tr><tr><td>Hungarian</td><td>14</td></tr></tbody></table>
								   <p class="description note-style"><b>Eg Shortcode:</b> <code>[mls_property_list language='2' ]</code></p>
								</div>
							</td>
                        </tr>
			
            <tr valign="top" class="tog-proplang-row">
				<?php $mls_prop_language = get_option('mls_plugin_prop_language'); ?>
                <th scope="row">Select Property Language</th>
                <td>
                    <select name="mls_plugin_prop_language" id="mls_plugin_prop_language">
                    <option value="1" <?php selected($mls_prop_language, '1'); ?>>English</option>
  <option value="2" <?php selected($mls_prop_language, '2'); ?>>Spanish</option>
  <option value="3" <?php selected($mls_prop_language, '3'); ?>>German</option>
  <option value="4" <?php selected($mls_prop_language, '4'); ?>>French</option>
  <option value="5" <?php selected($mls_prop_language, '5'); ?>>Dutch</option>
  <option value="6" <?php selected($mls_prop_language, '6'); ?>>Danish</option>
  <option value="7" <?php selected($mls_prop_language, '7'); ?>>Russian</option>
  <option value="8" <?php selected($mls_prop_language, '8'); ?>>Swedish</option>
  <option value="9" <?php selected($mls_prop_language, '9'); ?>>Polish</option>
  <option value="10" <?php selected($mls_prop_language, '10'); ?>>Norwegian</option>
  <option value="11" <?php selected($mls_prop_language, '11'); ?>>Turkish</option>
  <option value="13" <?php selected($mls_prop_language, '13'); ?>>Finnish</option>
  <option value="14" <?php selected($mls_prop_language, '14'); ?>>Hungarian</option>
                    </select>
					<p class="description note-style">Select your default properties language.</p>
					<p class="description note-style"><b>Warning:</b> Changing the language here will remove the selected property types in connection tab. Please Select Property types in <a href="?page=mls_plugin_settings">connection tab</a> to get data in your selected language.</p>
					<p class="description note-style"><b>Note:</b> If selected language doesn't have description or any other data, it'll bring English content as default.</p>
					
                </td>
            </tr>
        </table>
		<p class="submit">
        <input type="submit" name="mls_plugin_save_prop_language_settings" value="Save Changes" class="button button-primary" />
		</p>
    </form>
                    <?php
                    break;

                default:
                    ?>
			<div class="mls-refresh-row-flex">
                    <h2>Connection with Resales Online</h2>
					<div class="refresh_btn">
						<div>
						<span><b>Refresh Cache/Sync:</b> <a class="mls_plugin_refresh_btn button" href="#"><span class="dashicons dashicons-image-rotate"></span> Sync</a></span>
						<div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Click the button here to fetch new data from Resale Online</p>
                                 </div>                            
                         </div>
						</div>
								<p id="mls-last-refresh" class="note-style">
            <?php
            $last_refresh = get_option('mls_plugin_last_cache_refresh');
            echo $last_refresh 
                ? 'Last refreshed on: ' . esc_html(date_i18n('d/m/Y H:i', strtotime($last_refresh)))
                : 'Cache has not been refreshed yet.';
            ?>
        </p>
						
					</div>
			</div>
                    <form method="post">
                    <table class="form-table easySel-style ft-flex">
                        <tr valign="top">
                            <th scope="row">API Key</th>
                            <td><input type="text" name="mls_plugin_api_key" value="<?php echo esc_attr(get_option('mls_plugin_api_key')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Provide API Key from Resale Online</p>
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
                                     <p>Provide Client ID from Resale Online</p>
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
                                     <p>Provide Default Filter ID Sales from Resale Online</p>
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
                                     <p>Provide Default Filter ID Short Rentals from Resale Online</p>
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
                                     <p>Provide Default Filter ID Long Rentals from Resale Online</p>
                                 </div>                            
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Default Filter ID Featured</th>
                            <td><input type="text" name="mls_plugin_filter_id_features" value="<?php echo esc_attr(get_option('mls_plugin_filter_id_features')); ?>" />
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Provide Default Filter ID Featured from Resale Online</p>
                                 </div>                            
                                </div>
                            </td>
                        </tr>
						<tr valign="top">
                            <th scope="row">Enable Multi-language</th>
                            <td>
							 <section class="toggle-Section">
								  <label class="mls-switch">
									  <input type="checkbox" id="tog-propdetailpage-hide" name="mls_plugin_style_proplanghide" value="1" <?php checked(get_option('mls_plugin_style_proplanghide'), '1'); ?>>
								  <span class="mls-tog-slider round"></span>
								  </label>
							   </section>
								<p class="description note-style">Enable this toggle if your site is multi-language.</p>
								<p class="description note-style"><b>Warning:</b> Enabling this toggle will hide the dropdown for 'Property types, Property Language, Property Detail Page'.<br>You have to add the language attributes in each language pages for all shortcodes, enter the slug for property detail page and All Property types will show directly in the filters based on the language attributes.</p>
								<p class="description note-style"><b>Eg Shortcode:</b> <code>[mls_property_list language='2' ]</code></p>
							</td>
                        </tr>
                        <tr valign="top" class="tog-propdetailpage-row">
                            <th scope="row">Include Only Property Types on Search</th>
                            <td>
								<?php mls_plugin_property_type_filter_callback(); ?>
                                <div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Select Property Types you want to show in Search form</p>
                                 </div>                            
                                </div>
								<div class="easySelect-option-area"></div>
							</td>
                        </tr>
						<tr valign="top" class="tog-propdetailpage-row">
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
                                     <p>Select the page to display property details.</p>
                                 </div>                            
                                </div>
                   <p class="description note-style"> Use shortcode <code>[mls_property_details]</code> on the selected page.</p>
							</td>
                        </tr>
						<tr valign="top" class="tog-propdetailpage-row-show">
                            <th scope="row">Property Detail Page Slug</th>
                            <td>
								<input type="text" class="" name="mls_plugin_property_detail_page_slug" value="<?php echo esc_attr(get_option('mls_plugin_property_detail_page_slug')); ?>" placeholder="property-detail"/>
								<div class="mls-admin-info-wrap">
                                <span class="mls-admin-info-btn"><i class="fa-solid fa-circle-info"></i></span>
                                <div class="mls-admin-info-toggle" style="display: none;">
                                     <p>Enter the page slug without slashes to display property details.</p>
                                 </div>                            
                                </div>
                   <p class="description note-style"> Note: All Language property detail page slug should be same. <br>Use shortcode <code>[mls_property_details]</code> on the selected page.</p>
							</td>
                        </tr>
						<tr valign="top">
    <th scope="row" class="ver-mid">Connection Status</th>
    <td>
        <?php
        
            // Example API call function
            $api_status = mls_plugin_get_cached_mls_connection();
			
if (!empty($api_status['transaction']) && !empty($api_status['transaction']['status'])) {
    if ($api_status['transaction']['status'] === 'success') {
        echo '<div class="mls-cstatus-success"><span class="dashicons dashicons-saved"></span> Success</div>';
    } else {
        // Check for 'errordescription' in the response
        $transaction = $api_status['transaction'] ?? null;

        if (!empty($transaction) && is_array($transaction)) {
            echo '<div class="cs-err w-100"><div class="mls-cstatus-error"><span class="dashicons dashicons-no-alt"></span> Error</div></div>';

			 // Start the error message container
    echo '<div class="mls-connection-error">';

    // Print general transaction details
    echo '<strong>Connection Error Details:</strong><br>';
    echo '<ul>';
    if (!empty($transaction['incomingIp'])) {
        echo '<li><strong>Incoming IP:</strong> ' . esc_html($transaction['incomingIp']) . '</li>';
    }
    if (!empty($transaction['datetime'])) {
        echo '<li><strong>Date & time:</strong> ' . esc_html($transaction['datetime']) . '</li>';
    }
    echo '</ul>';

    // Print error description if available
    if (!empty($transaction['errordescription']) && is_array($transaction['errordescription'])) {
        echo '<strong>Error Descriptions:</strong>';
        echo '<ul>';
        foreach ($transaction['errordescription'] as $code => $message) {
            echo '<li><strong>' . esc_html($code) . ':</strong> ' . esc_html($message) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No detailed error description available.</p>';
    }

    // Close the error message container
    echo '</div>';
        } else {
            // Fallback for unknown error
            echo '<span style="color: red;">Error: Unknown error in response</span>';
        }
    }
}

        ?>
    </td>
</tr>

                    </table>
				<p class="submit">
        <input type="submit" name="mls_plugin_save_connection_settings" value="Save Changes" class="button button-primary" />
		</p>
                </form>
                    <?php
                    break;
            }
            ?>
        
        </div>
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
    register_setting('mls_plugin_settings_group', 'mls_plugin_border_color');
}

add_action('admin_init', 'mls_plugin_register_settings');

// Applying colors to the frontend.
function mls_plugin_apply_custom_colors() {
    // Light Theme Colors
$mls_plugin_primary_color = esc_attr(get_option('mls_plugin_primary_color', '#0073e1'));
$mls_plugin_secondary_color = esc_attr(get_option('mls_plugin_secondary_color', '#69c17d'));
$mls_plugin_text_color = esc_attr(get_option('mls_plugin_text_color', '#5c727d'));
$mls_plugin_black_color = esc_attr(get_option('mls_plugin_black_color', '#222222'));
$mls_plugin_bg_grey_color = esc_attr(get_option('mls_plugin_bg_grey_color', '#f7f7f7'));
$mls_plugin_bg_white_color = esc_attr(get_option('mls_plugin_bg_white_color', '#ffffff'));
$mls_plugin_bg_dark_color = esc_attr(get_option('mls_plugin_bg_dark_color', '#ededed'));
$mls_plugin_border_color = esc_attr(get_option('mls_plugin_border_color', '#cccccc'));
$mls_plugin_border_dark_color = esc_attr(get_option('mls_plugin_border_dark_color', '#828282'));
$mls_plugin_link_color = esc_attr(get_option('mls_plugin_link_color', '#0073e1'));
$mls_plugin_link_hover_color = esc_attr(get_option('mls_plugin_link_hover_color', '#69c17d'));

// Button Colors
$mls_plugin_button_color = esc_attr(get_option('mls_plugin_button_color', '#ffffff'));
$mls_plugin_button_bg_color = esc_attr(get_option('mls_plugin_button_bg_color', '#0073e1'));
$mls_plugin_button_border_color = esc_attr(get_option('mls_plugin_button_border_color', '#0073e1'));
$mls_plugin_button_hover_color = esc_attr(get_option('mls_plugin_button_hover_color', '#ffffff'));
$mls_plugin_button_bg_hover_color = esc_attr(get_option('mls_plugin_button_bg_hover_color', '#69c17d'));
$mls_plugin_button_border_hover_color = esc_attr(get_option('mls_plugin_button_border_hover_color', '#69c17d'));

// Banner Search Colors
$mls_plugin_banner_search_color = esc_attr(get_option('mls_plugin_banner_search_color', '#ffffff'));
$mls_plugin_banner_search_bg_color = esc_attr(get_option('mls_plugin_banner_search_bg_color', '#f7f7f7'));
$mls_plugin_banner_search_btn_color = esc_attr(get_option('mls_plugin_banner_search_btn_color', '#ffffff'));
$mls_plugin_banner_search_btn_bg_color = esc_attr(get_option('mls_plugin_banner_search_btn_bg_color', '#0073e1'));
$mls_plugin_banner_search_btn_hover_bg_color = esc_attr(get_option('mls_plugin_banner_search_btn_hover_bg_color', '#69c17d'));
$mls_plugin_banner_search_tabcolor = esc_attr(get_option('mls_plugin_banner_search_tabcolor', '#0073E1'));
$mls_plugin_banner_search_tabbackgroundcolor = esc_attr(get_option('mls_plugin_banner_search_tabbackgroundcolor', '#E5F1FC'));
	
// dark theme
$mls_plugin_dark_primary_color = esc_attr(get_option('mls_plugin_dark_primary_color', '#0073e1'));
$mls_plugin_dark_secondary_color = esc_attr(get_option('mls_plugin_dark_secondary_color', '#69c17d'));
$mls_plugin_dark_text_color = esc_attr(get_option('mls_plugin_dark_text_color', '#ffffff'));
$mls_plugin_dark_black_color = esc_attr(get_option('mls_plugin_dark_black_color', '#ffffff'));
$mls_plugin_dark_bg_grey_color = esc_attr(get_option('mls_plugin_dark_bg_grey_color', '#434343'));
$mls_plugin_dark_bg_white_color = esc_attr(get_option('mls_plugin_dark_bg_white_color', '#434343'));
$mls_plugin_dark_bg_dark_color = esc_attr(get_option('mls_plugin_dark_bg_dark_color', '#383838'));
$mls_plugin_dark_border_color = esc_attr(get_option('mls_plugin_dark_border_color', '#6c6c6c'));
$mls_plugin_dark_border_dark_color = esc_attr(get_option('mls_plugin_dark_border_dark_color', '#adadad'));
$mls_plugin_dark_link_color = esc_attr(get_option('mls_plugin_dark_link_color', '#0073e1'));
$mls_plugin_dark_link_hover_color = esc_attr(get_option('mls_plugin_dark_link_hover_color', '#69c17d'));
$mls_plugin_dark_button_color = esc_attr(get_option('mls_plugin_dark_button_color', '#ffffff'));
$mls_plugin_dark_button_bg_color = esc_attr(get_option('mls_plugin_dark_button_bg_color', '#0073e1'));
$mls_plugin_dark_button_border_color = esc_attr(get_option('mls_plugin_dark_button_border_color', '#0073e1'));
$mls_plugin_dark_button_hover_color = esc_attr(get_option('mls_plugin_dark_button_hover_color', '#ffffff'));
$mls_plugin_dark_button_bg_hover_color = esc_attr(get_option('mls_plugin_dark_button_bg_hover_color', '#69c17d'));
$mls_plugin_dark_button_border_hover_color = esc_attr(get_option('mls_plugin_dark_button_border_hover_color', '#69c17d'));
$mls_plugin_dark_banner_search_color = esc_attr(get_option('mls_plugin_dark_banner_search_color', '#fff'));
$mls_plugin_dark_banner_search_bg_color = esc_attr(get_option('mls_plugin_dark_banner_search_bg_color', '#f7f7f7'));
$mls_plugin_dark_banner_search_btn_color = esc_attr(get_option('mls_plugin_dark_banner_search_btn_color', '#fff'));
$mls_plugin_dark_banner_search_btn_bg_color = esc_attr(get_option('mls_plugin_dark_banner_search_btn_bg_color', '#0073e1'));
$mls_plugin_dark_banner_search_btn_hover_bg_color = esc_attr(get_option('mls_plugin_dark_banner_search_btn_hover_bg_color', '#69c17d'));
	$mls_plugin_dark_banner_search_tabcolor = esc_attr(get_option('mls_plugin_dark_banner_search_tabcolor', '#ffffff'));
$mls_plugin_dark_banner_search_tabbackgroundcolor = esc_attr(get_option('mls_plugin_dark_banner_search_tabbackgroundcolor', '#585858'));

	$dark_light_hide = get_option('mls_plugin_style_darklighthide');
	if ($dark_light_hide) { 
		// Output CSS for Dark Mode
echo "<style>
    :root {
        /* Common Variables (Using Dark Theme PHP Variables for Dark Mode) */
        --mls-Primary-color: $mls_plugin_dark_primary_color;
        --mls-Secondary-color: $mls_plugin_dark_secondary_color;
        --mls-Text-color: $mls_plugin_dark_text_color;
        --mls-Black-color: $mls_plugin_dark_black_color;
        --mls-BgGrey-color: $mls_plugin_dark_bg_grey_color;
        --mls-BgWhite-color: $mls_plugin_dark_bg_white_color;
        --mls-BgDark-color: $mls_plugin_dark_bg_dark_color;
        --mls-Border-color: $mls_plugin_dark_border_color;
        --mls-BorderDark-color: $mls_plugin_dark_border_dark_color;
        --mls-Link-color: $mls_plugin_dark_link_color;
        --mls-LinkHover-color: $mls_plugin_dark_link_hover_color;
        --mls-Button-color: $mls_plugin_dark_button_color;
        --mls-ButtonBg-color: $mls_plugin_dark_button_bg_color;
        --mls-ButtonBorder-color: $mls_plugin_dark_button_border_color;
        --mls-ButtonHover-color: $mls_plugin_dark_button_hover_color;
        --mls-ButtonBgHover-color: $mls_plugin_dark_button_bg_hover_color;
        --mls-ButtonBorderHover-color: $mls_plugin_dark_button_border_hover_color;
        --mlsbannersearch-color: $mls_plugin_dark_banner_search_color;
        --mlsbannersearchbackground-color: $mls_plugin_dark_banner_search_bg_color;
        --mlsbannersearchbutton-color: $mls_plugin_dark_banner_search_btn_color;
        --mlsbannersearchbuttonbackground-color: $mls_plugin_dark_banner_search_btn_bg_color;
        --mlsbannersearchbuttonhoverbackground-color: $mls_plugin_dark_banner_search_btn_hover_bg_color;
		--mlsTabBg-color: $mls_plugin_dark_banner_search_tabbackgroundcolor;
		--mlsTab-color: $mls_plugin_dark_banner_search_tabcolor;
    }
</style>";
	}else{
		// Output CSS
    echo "<style>
        :root {
            --mls-Primary-color: $mls_plugin_primary_color;
            --mls-Secondary-color: $mls_plugin_secondary_color;
            --mls-Text-color: $mls_plugin_text_color;
            --mls-Black-color: $mls_plugin_black_color;
            --mls-BgGrey-color: $mls_plugin_bg_grey_color;
            --mls-BgWhite-color: $mls_plugin_bg_white_color;
            --mls-BgDark-color: $mls_plugin_bg_dark_color;
            --mls-Border-color: $mls_plugin_border_color;
            --mls-BorderDark-color: $mls_plugin_border_dark_color;
            --mls-Link-color: $mls_plugin_link_color;
            --mls-LinkHover-color: $mls_plugin_link_hover_color;
            --mls-Button-color: $mls_plugin_button_color;
            --mls-ButtonBg-color: $mls_plugin_button_bg_color;
            --mls-ButtonBorder-color: $mls_plugin_button_border_color;
            --mls-ButtonHover-color: $mls_plugin_button_hover_color;
            --mls-ButtonBgHover-color: $mls_plugin_button_bg_hover_color;
            --mls-ButtonBorderHover-color: $mls_plugin_button_border_hover_color;
            --mlsbannersearch-color: $mls_plugin_banner_search_color;
            --mlsbannersearchbackground-color: $mls_plugin_banner_search_bg_color;
            --mlsbannersearchbutton-color: $mls_plugin_banner_search_btn_color;
            --mlsbannersearchbuttonbackground-color: $mls_plugin_banner_search_btn_bg_color;
            --mlsbannersearchbuttonhoverbackground-color: $mls_plugin_banner_search_btn_hover_bg_color;
			--mlsTabBg-color: $mls_plugin_banner_search_tabbackgroundcolor;
		    --mlsTab-color: $mls_plugin_banner_search_tabcolor;
        }
    </style>";
	}
	
	$mls_plugin_fontfamily = get_option('mls_plugin_fontfamily');
	if($mls_plugin_fontfamily == 'google'){
		$mls_plugin_custom_font_family = get_option('mls_plugin_google_font');
	}elseif($mls_plugin_fontfamily == 'custom'){
		$mls_plugin_custom_font_family = 'CustomFont';
	}else{
		$mls_plugin_custom_font_family = '';
	}
	
	// Output CSS for font sizes
echo "<style>
    :root {
        --mls-fontfamily: " . esc_attr($mls_plugin_custom_font_family) . ";
		--mls-Paragraph-fontsize: " . esc_attr(get_option('mls_plugin_paragraph_fontsize', '18')) . "px;
        --mls-lg-fontsize: " . esc_attr(get_option('mls_plugin_lg_fontsize', '16')) . "px;
        --mls-md-fontsize: " . esc_attr(get_option('mls_plugin_md_fontsize', '14')) . "px;
        --mls-sm-fontsize: " . esc_attr(get_option('mls_plugin_sm_fontsize', '12')) . "px;
        --mls-Button-fontsize: " . esc_attr(get_option('mls_plugin_button_fontsize', '15')) . "px;
        --mls-FilterForm-heading: " . esc_attr(get_option('mls_plugin_filter_form_heading', '24')) . "px;
        --mls-PropertyList-heading: " . esc_attr(get_option('mls_plugin_property_list_heading', '24')) . "px;
        --mls-PropertyListPrice-heading: " . esc_attr(get_option('mls_plugin_property_list_price_heading', '22')) . "px;
        --mls-PropertySingle-heading: " . esc_attr(get_option('mls_plugin_property_single_heading', '28')) . "px;
        --mls-PropertySingleSection-heading: " . esc_attr(get_option('mls_plugin_property_single_section_heading', '22')) . "px;
        --mls-PropertySinglePrice-heading: " . esc_attr(get_option('mls_plugin_property_single_price_heading', '24')) . "px;
    }
</style>";
	
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
        'property-details' => array(
            'title' => 'Property Details',
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
	$prpdetail_page_slug = get_option('mls_plugin_property_detail_page_slug', '');
	$proplanghide = get_option('mls_plugin_style_proplanghide', '');
    
    if (empty($selected_page_id) ) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>Please select a property detail page in the MLS Plugin settings. Use the shortcode <code>[mls_property_details]</code> on the selected page.</p>';
        echo '</div>';
    }elseif ($proplanghide && !$prpdetail_page_slug ) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>Please enter a property detail page slug in the MLS Plugin settings. Use the shortcode <code>[mls_property_details]</code> on the selected page.</p>';
        echo '</div>';
    }
	
	if (isset($_POST['mls_plugin_save_prop_language_settings'])) {
    echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>Please Select Property types in <a href="?page=mls_plugin_settings">connection tab</a> to get data in your selected language.</p>';
        echo '</div>';
		
	}
	
	/* Trial Period Notice */
$trialperiodnotice = mls_plugin_check_license_status();
if ($trialperiodnotice) {
    $trialenabled = get_option('mls_plugin_trialenabled', '0'); 
	$expiration_date = get_option('mls_plugin_expiration_date', ''); 
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