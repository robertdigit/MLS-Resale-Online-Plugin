<?php

// Handle AJAX lead form submission
function mls_plugin_handle_lead_form_submission() {
    
    // List of required fields
$required_fields = ['property_ref', 'user', 'email', 'phone', 'scheduledate'];

// Loop through each required field and check if it's empty
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        wp_send_json_error(['message' => mls_plugin_translate('error','mls_propertdetail_form_submitrequiredmissing') ]);
        return; // Stop further execution
    }
}

    global $wpdb;
    $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

    // Sanitize form data
    $user_name = sanitize_text_field($_POST['user']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phonenumbercode']) . sanitize_text_field($_POST['phone']);
    $comments = sanitize_textarea_field($_POST['comment']);
    $property_ref = sanitize_textarea_field($_POST['property_ref']);
    $personvideo = sanitize_textarea_field($_POST['personvideo']);
    $lead_time = sanitize_textarea_field($_POST['lead_time']);
    $scheduledate = sanitize_textarea_field($_POST['scheduledate']);
    $buyerseller = sanitize_textarea_field($_POST['buyerseller']);
	$preferredlanguage = sanitize_textarea_field($_POST['preferred_language']);

    // Insert form data into the database
    $wpdb->insert($table_name, array(
        'user_name' => $user_name,
        'email' => $email,
        'phone' => $phone,
        'comments' => $comments,
        'personvideo' => $personvideo,
        'lead_time' => $lead_time,
        'scheduledate' => $scheduledate,
        'buyerseller' => $buyerseller,
		'preferredlanguage' => $preferredlanguage,
        'referenceid' => $property_ref
    ));

    // Prepare mail content
    $mls_plugin_leadformmailheaderlogo = sanitize_text_field(get_option('mls_plugin_leadformmailheaderlogo', ''));
    $mls_plugin_leadformmailheadertext = sanitize_text_field(get_option('mls_plugin_leadformmailheadertext', 'Lead Form Submission'));
    $mls_plugin_leadformmailfootertext = sanitize_text_field(get_option('mls_plugin_leadformmailfootertext', '© 2024 Your Site Name, All Rights Reserved.'));
    $mls_plugin_blue_color = sanitize_text_field(get_option('mls_plugin_blue_color', '#0073e1'));

    $mailtemp = plugin_dir_path(__DIR__) . 'public/mls-mail-template.php';
    if (file_exists($mailtemp)) {
        ob_start();
        include $mailtemp;
        $message = ob_get_clean();
    } else {
        $message = "Name: $user_name\nEmail: $email\nPhone: $phone\nComments: $comments\nPerson or Video: $personvideo\nLead Time: $lead_time\nSchedule Date: $scheduledate\nBuyer or Seller: $buyerseller\nProperty Reference ID: $property_ref";
    }

    // Send email to admin
    $admin_email = get_option('mls_plugin_leadformtomail') ?: get_bloginfo('admin_email');
    $cc_email = get_option('mls_plugin_leadformccmail');
    $subject = sanitize_text_field(get_option('mls_plugin_leadformmailsubject', 'New Lead Form Submission'));
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (!empty($cc_email)) {
        $headers[] = 'Cc: ' . $cc_email;
    }

    $sent = wp_mail($admin_email, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success(['message' => mls_plugin_translate('error','mls_propertdetail_form_submitsuccess') ]);
    } else {
        wp_send_json_error(['message' => mls_plugin_translate('error','mls_propertdetail_form_submiterror') ]);
    }

    wp_die(); // Required to terminate the AJAX call properly
}
add_action('wp_ajax_mls_plugin_handle_lead_form', 'mls_plugin_handle_lead_form_submission');
add_action('wp_ajax_nopriv_mls_plugin_handle_lead_form', 'mls_plugin_handle_lead_form_submission');

add_action('wp_ajax_update_dates', 'update_date_slider');
add_action('wp_ajax_nopriv_update_dates', 'update_date_slider');

function update_date_slider() {
	// Get the current year, month, and day
$currentYear = (int) date('Y');
$currentMonth = (int) date('n'); // Numeric representation of month (1-12)
$currentDay = (int) date('j');  // Current day of the 
	
    // Get the selected month and year from the AJAX request
    $month = isset($_POST['month']) ? intval($_POST['month']) : 0;
    $year = isset($_POST['year']) ? intval($_POST['year']) : 0;

    if (!$month || !$year) {
        echo '<p>Error: Invalid Month or Year.</p>';
        wp_die();
    }

	// Start from the current day for the current month and year
     $startDay = ($month === $currentMonth && $year === $currentYear) ? $currentDay : 1;
	
    // Calculate the number of days in the selected month and year
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Generate HTML for the date slider in slick slider format
    $output = '';

    for ($day = 1; $day <= $totalDays; $day++) { 
        $date = DateTime::createFromFormat('Y-n-j', "$year-$month-$day");

        $dayName = $date->format('D'); // Day name (e.g., Mon, Tue)
        $dayNum = $date->format('d');  // Day number (e.g., 01, 02)
        $monthName = $date->format('M');   // Short month name (e.g., Jan)
        $formattedDate = $date->format('d/m/Y');
        $dayFullName = $date->format('l'); // Full day name

        $fullValue = $formattedDate . ' (' . $dayFullName . ')';
		$pastdayscls = ($day < $startDay) ? 'pastdayscls' : '';
		$currentdaycls = ($day == $currentDay && $month === $currentMonth && $year === $currentYear) ? 'currentdaycls' : '';
		
        // Add each day as a slick-slide
       $output .= '<div class="slick-slide ' . $pastdayscls . ' ' . $currentdaycls . '" style="width: 106px;">';
        $output .= '  <div class="property-schedule-singledate-wrapper">';
        $output .= '    <input type="radio" id="scheduledate" name="scheduledate" value="' . esc_attr($fullValue) . '">';
        $output .= '    <div>';
        $output .= '      <span class="day-name">' . esc_html($dayName) . '</span>';
        $output .= '      <span class="day-num">' . esc_html($dayNum) . '</span>';
        $output .= '      <span class="day-month">' . esc_html($monthName) . '</span>';
        $output .= '    </div>';
        $output .= '  </div>';
        $output .= '</div>';
    }

    $output .= ''; // Close slick-list and slick-track

    // Return the full slick slider HTML
    echo $output;
    wp_die();
}



// Function to display the language option in the settings page
function mls_plugin_display_language_setting_options(){
	$languages = get_option('mls_plugin_languages', [1]); // Default to English
    $custom_languages = get_option('mls_plugin_custom_languages', '');

    // Predefined languages
    $predefined_languages = [
        1 => 'English',
        2 => 'Spanish',
        3 => 'German',
        4 => 'French',
        5 => 'Dutch',
        6 => 'Danish',
        7 => 'Russian',
        8 => 'Swedish',
        9 => 'Polish',
        10 => 'Norwegian',
        11 => 'Turkish',
        13 => 'Finnish',
        14 => 'Hungarian'
    ];
	
	$output = '';
    foreach ($predefined_languages as $key => $label) {
        $output .= '<div class="mls-custom-checkbox">';
        $output .= '<input type="checkbox" name="mls_plugin_languages[]" value="' . esc_attr($key) . '"';
        $output .= checked(in_array($key, (array)$languages), true, false);
        $output .= '><label>';
        $output .= esc_html($label);
        $output .= '</label></div>';
    }

    $output .= '<br><br>';
    $output .= '<label>Custom Language(s): </label>';
    $output .= '<input type="text" id="mls_plugin_custom_languages" name="mls_plugin_custom_languages" value="' . esc_attr($custom_languages) . '" placeholder="e.g., Italian, Arabic">';
    $output .= '<p class="description">Add comma-separated custom languages.</p>';

    echo $output;
}

function mls_plugin_display_language_options() {
    $languages = get_option('mls_plugin_languages', [1]); // Default to English
    $custom_languages = get_option('mls_plugin_custom_languages', '');

    // Predefined languages
    $predefined_languages = [
        1 => 'English',
        2 => 'Spanish',
        3 => 'German',
        4 => 'French',
        5 => 'Dutch',
        6 => 'Danish',
        7 => 'Russian',
        8 => 'Swedish',
        9 => 'Polish',
        10 => 'Norwegian',
        11 => 'Turkish',
        13 => 'Finnish',
        14 => 'Hungarian'
    ];

    // Gather selected predefined languages
    $language_options = [];
    foreach ((array)$languages as $key) {
        if (isset($predefined_languages[$key])) {
            $language_options[] = $predefined_languages[$key];
        }
    }

    // Add custom languages if provided
    if (!empty($custom_languages)) {
        $custom_languages_array = explode(',', $custom_languages);
        $language_options = array_merge($language_options, array_map('trim', $custom_languages_array));
    }

    // Output the dropdown on the form
    echo '<select name="preferred_language">';
    foreach ($language_options as $language) {
        echo '<option value="' . esc_attr($language) . '">' . esc_html($language) . '</option>';
    }
    echo '</select>';
}


// Function to display the timing select box in the settings page
function mls_plugin_leadform_timing() {
    // Generate timings in 12-hour format
    $timings = array();
    for ($i = 0; $i < 24; $i++) {
        $hour = ($i < 12) ? ($i == 0 ? 12 : $i) : ($i - 12 == 0 ? 12 : $i - 12);
        $formatted_hour = sprintf('%02d', $hour); // Ensure two-digit formatting

        $time = $formatted_hour . ':00 ' . ($i < 12 ? 'am' : 'pm');
        $half = $formatted_hour . ':30 ' . ($i < 12 ? 'am' : 'pm');

        $timings[] = $time;
        $timings[] = $half;
    }

    // Get saved timings from the database
    $selected_timings = get_option('mls_plugin_available_timings', array());

    echo '<select name="mls_plugin_available_timings[]" id="mls_avail_time" multiple class="sel-app">';
    foreach ($timings as $time) {
        echo '<option value="' . esc_attr($time) . '" ' . (in_array($time, $selected_timings) ? 'selected' : '') . '>' . esc_html($time) . '</option>';
    }
    echo '</select>';
}


// Function to display the available timings on the frontend
function mls_plugin_display_available_timings() {
    $available_timings = get_option('mls_plugin_available_timings', array());

    if (!empty($available_timings)) {
        echo '<select name="lead_time" id="lead_time">';
        echo '<option value="0">Please select the time</option>';
        foreach ($available_timings as $time) {
            echo '<option value="' . esc_attr($time) . '">' . esc_html($time) . '</option>';
        }
        echo '</select>';
    } else {
        echo '<p>No available timings set.</p>';
    }
}


// Add Lead Form submenu under plugin settings
function mls_plugin_add_lead_form_submenu() {
    add_submenu_page(
        'mls_plugin_settings', // Parent slug
        'Lead Form',           // Page title
        'Lead Form',           // Menu title
        'manage_options',      // Capability
        'mls-plugin-lead-form', // Menu slug
        'mls_plugin_display_lead_form_data' // Callback function
    );
}
add_action('admin_menu', 'mls_plugin_add_lead_form_submenu');

function mls_plugin_display_lead_form_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

    // Fetch all form submissions
    $leads = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_submitted DESC");

    ?>
    <div class="wrap">
        <h1 class="mls-ap-heading-style1">Lead Form Submissions</h1>
        <div class="mls-table-reponsive">
            <table class="wp-list-table widefat fixed mls-table-theme">
                <thead>
                    <tr>
                        <th><span class="mls-table-icon mls-id"></span>Entry ID</th>
                        <th><span class="mls-table-icon mls-id"></span>Reference ID</th>
                        <th><span class="mls-table-icon mls-name"></span>Name</th>
                        <th><span class="mls-table-icon mls-email"></span>Email</th>
						<th><span class="mls-table-icon mls-datetime"></span>Date Submitted</th>
                        <th><span class="mls-table-icon mls-actions"></span>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($leads){
	foreach ($leads as $lead) { ?>
                    <tr>
                        <td><?php echo esc_html($lead->id); ?></td>
                        <td><?php echo esc_html($lead->referenceid); ?></td>
                        <td><?php echo esc_html($lead->user_name); ?></td>
                        <td><?php echo esc_html($lead->email); ?></td>
						<td><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $lead->date_submitted ) ) ); ?></td>

                        <td>
							<a href="#" class="mls-toggle-qualified" data-lead-id="<?php echo esc_attr( $lead->id ); ?>" data-qualified="<?php echo esc_attr( $lead->is_qualified ); ?>">
        <?php if ($lead->is_qualified) { ?>
            <span class="dashicons dashicons-star-filled"></span>
        <?php } else { ?>
            <span class="dashicons dashicons-star-empty"></span>
        <?php } ?>
		<span class="mls-qualified-info-toggle">Add Qualified Leads</span>
    </a>
                            <a href="#" class="button mls-view-details" data-lead-id="<?php echo esc_attr($lead->id); ?>">View</a>
                           <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=mls_delete_lead&id=' . $lead->id), 'mls_delete_lead_' . $lead->id)); ?>" class="button button-danger" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
														
                        </td>
                    </tr>
                <?php } }else{ echo '<tr class="mls-table-no-data"><td colspan="6">No data found</td></tr>'; } ?>
                </tbody>
            </table>
        </div>

        <!-- Common Modal -->
        <div id="mls-lead-details-modal" class="mls-lead-details-modal" style="display:none;">
            <div class="mls-lead-details-content">
                <div id="mls-lead-header">
                    <h2>Lead Details</h2>
                    <button class="mls-close-modal">×</button>
                </div>
                <div id="mls-lead-details-body">
                    <!-- Data will be dynamically injected here -->
                </div>
            </div>
        </div>
    </div>
    <?php
    // Pass PHP data to JavaScript
    $leads_data = [];
    foreach ($leads as $lead) {
        $leads_data[$lead->id] = [
            'referenceid'   => esc_html($lead->referenceid),
            'user_name'     => esc_html($lead->user_name),
            'email'         => esc_html($lead->email),
            'phone'         => esc_html($lead->phone),
            'comments'      => esc_html(stripslashes($lead->comments)),
            'lead_time'     => esc_html($lead->lead_time),
            'scheduledate'  => esc_html($lead->scheduledate),
            'personvideo'   => esc_html($lead->personvideo),
            'buyerseller'   => esc_html($lead->buyerseller),
			'preferredlanguage'   => esc_html($lead->preferredlanguage),
            'date_submitted'=> esc_html( date_i18n( 'd/m/Y H:i', strtotime( $lead->date_submitted ) ) ),
        ];
    }
    ?>
    <script>
        var leadsData = <?php echo json_encode($leads_data); ?>;
    </script>
    <?php
}

// Register AJAX handlers For Qualified Leads
add_action('wp_ajax_mls_toggle_lead_status', 'mls_plugin_toggle_lead_status');

function mls_plugin_toggle_lead_status() {
    global $wpdb;
    $lead_id = intval($_POST['lead_id']);
    $qualified = intval($_POST['qualified']); // 0 for not qualified, 1 for qualified

    $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

    // Toggle the qualified status
    $new_status = $qualified ? 0 : 1;
    $wpdb->update($table_name, ['is_qualified' => $new_status], ['id' => $lead_id]);

    // Return the new status
    wp_send_json_success(['new_status' => $new_status]);
}


// Handle lead deletion
function mls_plugin_handle_lead_deletion() {
    if (isset($_GET['id']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'mls_delete_lead_' . $_GET['id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

        // Delete the lead
        $wpdb->delete($table_name, array('id' => intval($_GET['id'])));

        // Redirect back to the leads page
        wp_redirect(admin_url('admin.php?page=mls-plugin-lead-form'));
        exit;
    }
}
add_action('admin_post_mls_delete_lead', 'mls_plugin_handle_lead_deletion');

// Add submenu for qualified leads
add_action('admin_menu', function() {
    add_submenu_page(
        'mls_plugin_settings', 
        'Qualified Leads', 
        'Qualified Leads', 
        'manage_options', 
        'mls_qualified_leads', 
        'mls_plugin_display_qualified_leads'
    );
});

function mls_plugin_display_qualified_leads() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mls_plugin_lead_form';

    // Fetch only qualified leads
    $leads = $wpdb->get_results("SELECT * FROM $table_name WHERE is_qualified = 1 ORDER BY date_submitted DESC");

    ?>
    <div class="wrap">
        <h1 class="mls-ap-heading-style1">Qualified Leads</h1>
		<div class="mls-table-reponsive">
        <table class="wp-list-table widefat fixed mls-table-theme">
            <thead>
                <tr>
                    <th><span class="mls-table-icon mls-id"></span>Entry ID</th>
                    <th><span class="mls-table-icon mls-id"></span>Property ID</th>
                    <th><span class="mls-table-icon mls-name"></span>Name</th>
                    <th><span class="mls-table-icon mls-email"></span>Email</th>
                    <th><span class="mls-table-icon mls-datetime"></span>Date Submitted</th>
					<th><span class="mls-table-icon mls-actions"></span>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($leads){
		foreach ($leads as $lead) { ?>
                <tr class="mls-qualified-lead">
                    <td><?php echo esc_html($lead->id); ?></td>
                    <td><?php echo esc_html($lead->referenceid); ?></td>
                    <td><?php echo esc_html($lead->user_name); ?></td>
                    <td><?php echo esc_html($lead->email); ?></td>
                    <td><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $lead->date_submitted ) ) ); ?></td>
					<td>
						<a href="#" class="mls-toggle-qualified" data-lead-id="<?php echo esc_attr($lead->id); ?>" data-qualified="1"><span class="dashicons dashicons-star-filled"></span> <span class="mls-qualified-info-toggle">Remove from Qualified Leads</span></a>
						
                            <a href="#" class="button mls-view-details" data-lead-id="<?php echo esc_attr($lead->id); ?>">View</a>
                           							
                        </td>
                </tr>
            <?php } }else{ echo '<tr class="mls-table-no-data"><td colspan="6">No data found</td></tr>'; } ?>
            </tbody>
        </table>
		</div>
		 <!-- Common Modal -->
        <div id="mls-lead-details-modal" class="mls-lead-details-modal" style="display:none;">
            <div class="mls-lead-details-content">
                <div id="mls-lead-header">
                    <h2>Lead Details</h2>
                    <button class="mls-close-modal">×</button>
                </div>
                <div id="mls-lead-details-body">
                    <!-- Data will be dynamically injected here -->
                </div>
            </div>
        </div>
    </div>
	<?php
    // Pass PHP data to JavaScript
    $leads_data = [];
    foreach ($leads as $lead) {
        $leads_data[$lead->id] = [
            'referenceid'   => esc_html($lead->referenceid),
            'user_name'     => esc_html($lead->user_name),
            'email'         => esc_html($lead->email),
            'phone'         => esc_html($lead->phone),
            'comments'      => esc_html(stripslashes($lead->comments)),
            'lead_time'     => esc_html($lead->lead_time),
            'scheduledate'  => esc_html($lead->scheduledate),
            'personvideo'   => esc_html($lead->personvideo),
            'buyerseller'   => esc_html($lead->buyerseller),
			'preferredlanguage'   => esc_html($lead->preferredlanguage),
            'date_submitted'=> esc_html( date_i18n( 'd/m/Y H:i', strtotime( $lead->date_submitted ) ) ),
        ];
    }
    ?>
    <script>
        var leadsData = <?php echo json_encode($leads_data); ?>;
    </script>
    <?php
}