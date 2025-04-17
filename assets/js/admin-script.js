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

jQuery(document).ready(function ($) {
    $('#mls_upload_custom_font').on('click', function (e) {
        e.preventDefault();

        // Open WordPress media uploader with restrictions
        var file_frame = wp.media({
            title: 'Select or Upload Custom Font',
            button: {
                text: 'Use This Font',
            },
            multiple: false,
        });

        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Validate the file type again (fallback validation)
            if (['woff', 'woff2', 'ttf', 'otf'].some(ext => attachment.url.endsWith(ext))) {
                $('#mls_custom_font_url').val(attachment.url); // Set the font URL in the input field
            } else {
                alert('Please select a valid font file (WOFF, WOFF2, TTF, or OTF).');
            }
        });

        file_frame.open();
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

jQuery(document).ready(function(jQuery){
 jQuery("#mls_property_types").easySelect({
     buttons: true, 
    search: true,
   placeholder: 'Type',
    placeholderColor: '',
   selectColor: '#524781',
    itemTitle: 'Car selected',
     showEachItem: true,
   width: '100%',
   dropdownMaxHeight: '214px',
 })
 jQuery("#mls_avail_time").easySelect({
     buttons: true, // 
     search: true,
     placeholder: 'Type',
     placeholderColor: '',
     selectColor: '#524781',
     itemTitle: 'Car selected',
     showEachItem: true,
     width: '100%',
     dropdownMaxHeight: '214px',
 })
});

jQuery(document).ready(function(jQuery){
    jQuery(".mls-adminsc-info-toggle").hide();
    jQuery(".mls-shortcode").hover(function(){
      jQuery(this).siblings(".mls-adminsc-info-toggle").toggle();
    });
    jQuery(".mls-admin-info-toggle").hide();
    jQuery(".mls-admin-info-btn").hover(function(){
      jQuery(this).siblings(".mls-admin-info-toggle").toggle();
    });
    jQuery(".mls-qualified-info-toggle").hide();
    jQuery(".mls-toggle-qualified").hover(function(){
      jQuery(this).find(".mls-qualified-info-toggle").toggle();
    });
	jQuery(".dashicons-star-filled").next(".mls-qualified-info-toggle").text("Remove from Qualified Leads")
	jQuery(".dashicons-star-empty").next(".mls-qualified-info-toggle").text("Add to Qualified Leads")
});

jQuery(document).ready(function(jQuery) {
    jQuery(function() {
        jQuery('#mls_plugin_custom_languages').tagsInput({
            'delimiter': [',', ';'],
            'unique': true,
            'minChars': 1,
            'maxChars': null,
            'limit': null,
            placeholder:'Add Language',
        });
    });
});

jQuery(document).ready(function ($) {
    // Separate function for SweetAlert confirmation
    function showSweetAlertConfirmation(message, callback) {
        Swal.fire({
            title: 'Confirm Deactivation',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, deactivate it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                callback(true); // Proceed with the deactivation
            } else {
                callback(false); // Do nothing
            }
        });
    }

    $('#deactivate-license-btn').on('click', function (e) {
        e.preventDefault();

        // Call SweetAlert for confirmation
        showSweetAlertConfirmation("Are you sure you want to deactivate your license?", function (confirmDeactivate) {
            if (confirmDeactivate) {
                // Send AJAX request
                $.ajax({
                    url: ajaxurl, // WordPress AJAX URL
                    type: 'POST',
                    data: {
                        action: 'deactivate_license',
                    },
                    success: function (response) {
                        if (response.success) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.data.message || 'An error occurred while deactivating the license.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to deactivate license. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    },
                });
            }
        });
    });
});


jQuery(document).ready(function(jQuery) {
    jQuery(".styledSelect").click(function(){
		if (jQuery(this).next("ul.options").is(":visible"))
			{
            jQuery(this).parents("form").find(".styledSelect").next("ul.options").hide();
			jQuery(this).removeClass("active");
            jQuery(this).next("ul.options").show();
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
			}
		else
			{
			jQuery(this).addClass("active");
            jQuery(this).next("ul.options").hide();
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
			}
    })
});
jQuery(document).ready(function ($) {
    $('.mls_plugin_refresh_btn').on('click', function (e) {
        e.preventDefault();

        const $button = $(this);
        $button.text('Syncing...').prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mls_refresh_locations'
            },
            success: function (response) {
                const currentTime = new Date().toLocaleString();

                if (response.success) {
                    // Display success message with SweetAlert
                    Swal.fire({
                        title: 'Success',
                        text: response.data,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload(); // Reload the page after confirmation
                    });
                } else {
                    // Display error message with SweetAlert
                    Swal.fire({
                        title: 'Error',
                        text: response.data || 'An error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }

                $button.text('Sync').prop('disabled', false);
            },
            error: function () {
                // Display error message with SweetAlert
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while refreshing the cache.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });

                $button.text('Sync').prop('disabled', false);
            }
        });
    });
});


jQuery(document).ready(function() {
    jQuery("input.searchInputeasySelect").keyup(function() {
        var jQueryinput = jQuery(this);
        var value = jQueryinput.val().toLowerCase();
        var jQueryoptions = jQueryinput.parents(".options");
        var jQuerylistItems = jQueryoptions.find(".scrolableDiv > li");
        
        jQueryoptions.find(".no_results").hide(); // Hide no results initially
        var hasResults = false; // Flag to track if there are matching results

        jQuerylistItems.each(function() {
            var content = jQuery(this).find('label').text();
            if (content.toLowerCase().indexOf(value) === -1) {
                jQuery(this).hide(); // Hide non-matching item
            } else {
                jQuery(this).show(); // Show matching item
                hasResults = true; // Set flag to true if there's a match
            }
        });

        // Show no results message if no matches found
        if (!hasResults) {
            jQueryoptions.find(".no_results").show();
        }
    });
});


jQuery(document).ready(function(jQuery) {
    jQuery(document).ready(function() {
		jQuery('input.mulpitply_checkbox_style').on('change', function() {
//			if (jQuery(this).is(':checked')) {
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
//			}
		});
	});
});



// jQuery(function($){
//    $('.switch input').on("click",function(){
//       $(this).parent().toggleClass('active');
//    });
// });

jQuery(document).ready(function (jQuery) {	
  // Initialize the class based on the initial state
  jQuery('input[type="checkbox"]').each(function () {
    if (jQuery(this).prop('checked')) {
      jQuery(this).parent('.mls-switch').addClass('active');
    }
  });
  jQuery('.proplang-note').hide();
  jQuery('.tog-propdetailpage-row-show').hide();
  jQuery('.tog-dark-row').hide();
  jQuery('input[type="checkbox"]#tog-timing-hide').each(function () {
    if (jQuery(this).prop('checked')) {
      jQuery(this).parents("table").find('.tog-timing-row').hide();
    }
  });
  jQuery('input[type="checkbox"]#tog-lang-hide').each(function () {
    if (jQuery(this).prop('checked')) {
     jQuery(this).parents("table").find('.tog-lang-row').hide();
    }
  });
  jQuery('input[type="checkbox"]#tog-proplang-hide').each(function () {
    if (jQuery(this).prop('checked')) {
     jQuery(this).parents("table").find('.tog-proplang-row').hide();
     jQuery(this).parents("table").find('.proplang-note').show();
    }
  });
  jQuery('input[type="checkbox"]#tog-propdetailpage-hide').each(function () {
    if (jQuery(this).prop('checked')) {
     jQuery(this).parents("table").find('.tog-propdetailpage-row').hide();
     jQuery(this).parents("table").find('.tog-propdetailpage-row-show').show();
    }
  });
  jQuery('input[type="checkbox"]#tog-darklight-hide').each(function () {
    if (jQuery(this).prop('checked')) {
     jQuery(this).parents("table").find('.tog-light-row').hide();
     jQuery(this).parents("table").find('.tog-dark-row').show();
    }
  });
	
  // Add toggle functionality for clicks
  jQuery('.mls-switch input[type="checkbox"]').on("change", function () {
    jQuery(this).parent('.mls-switch').toggleClass('active', jQuery(this).prop('checked'));
  });
	
});

jQuery(document).ready(function(jQuery) {
  function updateFontFamilyVisibility() {
    const selectedValue = jQuery('.mls-custom-radio input[type="radio"]:checked').val();
    if (selectedValue === 'google') {
      jQuery('.mls-gfonts-wrap').show();
      jQuery('.mls-cfonts-wrap').hide();
    } else if (selectedValue === 'custom') {
      jQuery('.mls-gfonts-wrap').hide();
      jQuery('.mls-cfonts-wrap').show();
    } else if (selectedValue === 'Default') {
      jQuery('.mls-gfonts-wrap').hide();
      jQuery('.mls-cfonts-wrap').hide();
    }
  }

  // Run the function on page load
  updateFontFamilyVisibility();

  // Update visibility on radio button change
  jQuery('.mls-custom-radio input[type="radio"]').change(function() {
    updateFontFamilyVisibility();
  });
	jQuery('input[type="checkbox"]#tog-timing-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('.tog-timing-row').hide();
		} else {
  		    jQuery(this).parents("table").find('.tog-timing-row').show();
		}
	});
	jQuery('input[type="checkbox"]#tog-lang-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('.tog-lang-row').hide();
		} else {
  		    jQuery(this).parents("table").find('.tog-lang-row').show();
		}
	});
	jQuery('input[type="checkbox"]#tog-proplang-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('.tog-proplang-row').hide();
            jQuery(this).parents("table").find('.proplang-note').show();
		} else {
  		    jQuery(this).parents("table").find('.tog-proplang-row').show();
            jQuery(this).parents("table").find('.proplang-note').hide();
		}
	});
	jQuery('input[type="checkbox"]#tog-propdetailpage-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('.tog-propdetailpage-row').hide();
            jQuery(this).parents("table").find('.tog-propdetailpage-row-show').show();
		} else {
  		    jQuery(this).parents("table").find('.tog-propdetailpage-row').show();
            jQuery(this).parents("table").find('.tog-propdetailpage-row-show').hide();
		}
	});
	jQuery('input[type="checkbox"]#tog-darklight-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('.tog-light-row').hide();
            jQuery(this).parents("table").find('.tog-dark-row').show();
		} else {
  		    jQuery(this).parents("table").find('.tog-light-row').show();
            jQuery(this).parents("table").find('.tog-dark-row').hide();
		}
	});
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('mls-shortcode')) {
        const shortcodeElement = e.target;
        const textToCopy = shortcodeElement.innerText;

        // Copy to clipboard
        const tempTextarea = document.createElement('textarea');
        tempTextarea.value = textToCopy;
        document.body.appendChild(tempTextarea);
        tempTextarea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextarea);

        // Show 'Copied!' message
        const statusElement = shortcodeElement.nextElementSibling; // Get the adjacent span
        if (statusElement && statusElement.classList.contains('mls-shortcode-copy-status')) {
            statusElement.style.display = 'inline';

            // Hide the message after 2 seconds
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 2000);
        }
    }
});

jQuery(document).ready(function($){
jQuery(document).on('click', 'a[data-plugin-deactivate="mls-plugin"]', function(e) {
                e.preventDefault();
                jQuery('#mls-plugin-deactivate-popup').fadeIn();
            });

            jQuery('#mls-plugin-cancel-deactivate').on('click', function() {
                jQuery('#mls-plugin-deactivate-popup').fadeOut();
            });

			jQuery('#mls-plugin-confirm-deactivate').on('click', function() {
    const deleteData = jQuery('#mls-plugin-deactivate-form input[name="delete_data"]:checked').val();
    jQuery.post(ajaxurl, {
        action: 'mls_plugin_handle_deactivation',
        delete_data: deleteData,
    }, function() {
        window.location.href = mlsPluginAdmin.pluginsPageUrl;
    });
});       
});


jQuery(document).ready(function() {
	
if (jQuery("input#weblink_advanced").is(":checked")) {
  jQuery("input#weblink_advanced").parents(".basadv-col").removeClass("basic").addClass("advanced");
}

else if (jQuery("input#weblink_basic").is(":checked")) {
  jQuery("input#weblink_basic").parents(".basadv-col").removeClass("advanced").addClass("basic");
}
 });	
	jQuery(document).ready(function() {
  jQuery('input[name="mls_plugin_weblink_structure"]').change(function() {
    var parent = jQuery(this).closest('.basadv-col');

    if (jQuery(this).attr('id') === 'weblink_advanced') {
      parent.removeClass('basic').addClass('advanced');
    } else if (jQuery(this).attr('id') === 'weblink_basic') {
      parent.removeClass('advanced').addClass('basic');
    }
  });
});