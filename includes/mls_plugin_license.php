<?php
function my_plugin_register_settings() {
    register_setting('mls_plugin_options_group', 'mls_plugin_license_key');
}
add_action('admin_init', 'my_plugin_register_settings');
// Add submenu for license.
function mls_plugin_add_license_submenu() {
    add_submenu_page(
        'mls_plugin_settings',    // Parent slug (the main menu slug)
        'MLS Plugin License', // Page title
        'License',          // Menu title
        'manage_options',         // Capability required
        'mls_plugin_license', // Menu slug for the submenu
        'mls_plugin_license_page' // Function to display the page content
    );
}

add_action('admin_menu', 'mls_plugin_add_license_submenu');


function mls_plugin_license_page() {
    $license_key = get_option('mls_plugin_license_key');
    $is_license_valid = mls_plugin_is_license_valid(); // Check if the license is valid
    $message = '';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if verify button was clicked
        if (isset($_POST['verify_license'])) {
            $license_key = sanitize_text_field($_POST['mls_plugin_license_key']);
            update_option('mls_plugin_license_key', $license_key);
            // Verify the license here (replace with your verification logic)
            $is_license_valid = mls_plugin_is_license_valid(); // Re-check license validity
            if ($is_license_valid) {
                $message = 'License key successfully activated. The MLS Plugin is now enabled and ready to use.';
            } 
        }

    }

    // Display the settings form
    ?>
    <div class="wrap licence-style">
        <h1 class="mls-ap-heading-style1"><?php esc_attr_e('Plugin Settings', 'mls-plugin'); ?></h1>
        
        <?php if (!empty($message)) : ?>
            <div class="notice notice-success"><p><?php echo esc_html($message); ?></p></div>
        <?php endif; 
		
		if ($message = get_transient('mls_license_deactivation_message')) {
    echo '<div class="notice notice-success licence-style"><p>' . esc_html($message) . '</p></div>';
    delete_transient('mls_license_deactivation_message'); // Clear the message
}

		?>
		
<div class="mls-section-style1 mls-licence-tab">
        <form method="post" action="">
            <?php
            settings_fields('mls_plugin_options_group');
            do_settings_sections('mls_plugin_options_group');
            ?>
            <table class="form-table easySel-style">
                <tr valign="top">
                    <th scope="row"><?php esc_attr_e('License Key', 'mls-plugin'); ?></th>
                    <td>
                        <p>Already purchased? Simply enter your license key below to enable MLS Plugin</p>
                        <input type="text" name="mls_plugin_license_key" value="<?php echo esc_attr($license_key); ?>" />

                        <?php if (!$is_license_valid) : ?>
                            <input type="submit" name="verify_license" class="button vd-btn" value="Verify">
                        <?php else : ?>    
                        <input type="submit" id="deactivate-license-btn" class="button vd-btn deact-btn" value="Remove License">
						<div style="color:#00a32a;" class="mls-lkv-msg">License Key Validated. The MLS Plugin is now active and fully functional.</div>
                        <?php endif; ?>
                        
                        <?php if ($license_key && !$is_license_valid) : ?>
                            <div style="color:#d63638;" class="mls-lkv-msg"> Invalid License Key. Please check your entry or purchase a valid license to enable the MLS Plugin. </div>
                        <?php endif; ?>

                        <p>If you have not purchased, Please <a href="https://resales-online-sync.es/download-resales-online-plugin/" target="_blank">Purchase Here </a>or <a href="https://crm.clarkdigital.es/mls-profile/" target="_blank">Manage Licenses</a></p>
                    </td>
                </tr>
            </table>
            <p class="submit">
        <input type="submit" name="verify_license" value="Save Changes" class="button button-primary" />
		</p>
        </form>
		</div>
    </div>
    <?php
}
add_action('wp_ajax_deactivate_license', 'mls_plugin_deactivate_license');
function mls_plugin_deactivate_license() {
    
    // Perform deactivation logic
    delete_option('mls_plugin_license_key');
    $license_key = '';
    $is_license_valid = false; // Update the validity status
    set_transient('mls_license_deactivation_message', 'License key removed from this website. The MLS Plugin is now deactivated.', 30);


    // Return a response
    wp_send_json_success(['message' => 'License key removed from this website. The MLS Plugin is now deactivated.']);
}