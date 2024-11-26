jQuery(document).ready(function($){
        var mediaUploader;

        // Open media library on button click
        $('#mls_plugin_leadformmailheaderlogo_button').click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Select Header Logo',
                button: {
                    text: 'Use this logo'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#mls_plugin_leadformmailheaderlogo').val(attachment.url);
                $('#mls_plugin_leadformmailheaderlogo_preview').attr('src', attachment.url).show();
                $('#mls_plugin_leadformmailheaderlogo_remove').show();
            });

            mediaUploader.open();
        });

        // Remove image button functionality
        $('#mls_plugin_leadformmailheaderlogo_remove').click(function(e) {
            e.preventDefault();
            $('#mls_plugin_leadformmailheaderlogo').val('');
            $('#mls_plugin_leadformmailheaderlogo_preview').hide();
            $(this).hide();
        });
    });

/**Lead form submission popup**/
jQuery(document).ready(function($) {
    const modal = $('#mls-lead-details-modal');
    const modalBody = $('#mls-lead-details-body');

    // Show modal and load data
    $('.mls-view-details').on('click', function(e) {
        e.preventDefault();
        const leadId = $(this).data('lead-id');

        // Fetch data based on lead ID
        const leadDetails = leadsData[leadId];
        if (leadDetails) {
            modalBody.html(`
                <p><strong>Reference ID:</strong> ${leadDetails.referenceid}</p>
                <p><strong>Name:</strong> ${leadDetails.user_name}</p>
                <p><strong>Email:</strong> ${leadDetails.email}</p>
                <p><strong>Phone:</strong> ${leadDetails.phone}</p>
                <p><strong>Scheduled Time:</strong> ${leadDetails.lead_time}</p>
                <p><strong>Scheduled Date:</strong> ${leadDetails.scheduledate}</p>
                <p><strong>Tour Type:</strong> ${leadDetails.personvideo}</p>
                <p><strong>Type of Visitor:</strong> ${leadDetails.buyerseller}</p>
				<p><strong>Preferred Language:</strong> ${leadDetails.preferredlanguage}</p>
                <p><strong>Date Submitted:</strong> ${leadDetails.date_submitted}</p>
				<p><strong>Comments:</strong> ${leadDetails.comments}</p>
            `);

            modal.show();
        }
    });

    // Close modal
    $('.mls-close-modal').on('click', function() {
        modal.hide();
    });
});


/*Qualified Leads Ajax*/
jQuery(document).ready(function($) {
    $('.mls-toggle-qualified').on('click', function(e) {
        e.preventDefault();
        
        var leadId = $(this).data('lead-id');
        var qualified = $(this).data('qualified');
        var icon = $(this).find('.dashicons');
        var row = $(this).closest('.mls-qualified-lead'); // Get the current table row
		 var tooltip = $(this).find('.mls-qualified-info-toggle');

        // Send AJAX request to toggle qualified status
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'mls_toggle_lead_status',
                lead_id: leadId,
                qualified: qualified,
            },
            success: function(response) {
                if (response.success) {
                    var newStatus = response.data.new_status;
                    // Update the star icon based on the new status
                    if (newStatus) {
                        icon.removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
						tooltip.text('Remove from Qualified Lead');
                    } else {
                        icon.removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
						tooltip.text('Add to Qualified Lead');
                        // Remove row if in the "Qualified Leads" table
                        row.remove();
                    }
                    // Update the qualified data attribute
                    icon.closest('.mls-toggle-qualified').data('qualified', newStatus);
                }
            }
        });
    });
});

jQuery(document).ready(function($){
 $("#mls_property_types").easySelect({
     buttons: true, // 
     search: true,
     placeholder: 'Type',
     placeholderColor: '',
     selectColor: '#524781',
     itemTitle: 'Car selected',
     showEachItem: true,
     width: '100%',
     dropdownMaxHeight: '450px',
 })
 $("#mls_avail_time").easySelect({
     buttons: true, // 
     search: true,
     placeholder: 'Type',
     placeholderColor: '',
     selectColor: '#524781',
     itemTitle: 'Car selected',
     showEachItem: true,
     width: '100%',
     dropdownMaxHeight: '450px',
 })
});

jQuery(document).ready(function($){
    $(".mls-admin-info-toggle").hide();
    $(".mls-admin-info-btn").hover(function(){
      $(this).siblings(".mls-admin-info-toggle").toggle();
    });
    $(".mls-qualified-info-toggle").hide();
    $(".mls-toggle-qualified").hover(function(){
      $(this).find(".mls-qualified-info-toggle").toggle();
    });
	$(".dashicons-star-filled").next(".mls-qualified-info-toggle").text("Remove from Qualified Leads")
	$(".dashicons-star-empty").next(".mls-qualified-info-toggle").text("Add to Qualified Leads")
});

jQuery(document).ready(function($) {
    $(function() {
        $('#mls_plugin_custom_languages').tagsInput({
            'delimiter': [',', ';'],
            'unique': true,
            'minChars': 1,
            'maxChars': null,
            'limit': null,
            placeholder:'Add Language',
        });
    });
});

jQuery(document).ready(function($) {
	console.log('inq');
    $('#deactivate-license-btn').on('click', function(e) {
        e.preventDefault();
console.log('int');
        // Confirm before proceeding
        if (confirm("Are you sure you want to deactivate your license?")) {
            // Send AJAX request
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'deactivate_license',
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(response.data.message || 'An error occurred while deactivating the license.');
                    }
                },
                error: function() {
                    alert('Failed to deactivate license. Please try again.');
                }
            });
        }
    });
});

jQuery(document).ready(function($) {
    $(".styledSelect").click(function(){
		if ($(this).next("ul.options").is(":visible"))
			{
            $(this).parents("form").find(".styledSelect").next("ul.options").hide();
			$(this).removeClass("active");
            $(this).next("ul.options").show();
			}
		else
			{
			$(this).addClass("active");
            $(this).next("ul.options").hide();
			}
    })
});
jQuery(document).ready(function ($) {
    $('.mls_plugin_refresh_btn').on('click', function (e) {
        e.preventDefault();

        const $button = $(this);
        $button.text('Refreshing...').prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mls_refresh_locations'
            },
            success: function (response) {
                if (response.success) {
					const currentTime = new Date().toLocaleString();
//                     $('#mls-last-refresh').text(`Last refreshed on: ${currentTime}`);
                    alert(response.data);
					window.location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
                $button.text('Refresh').prop('disabled', false);
            },
            error: function () {
                alert('An error occurred while refreshing the location cache.');
                $button.text('Refresh').prop('disabled', false);
            }
        });
    });
});

