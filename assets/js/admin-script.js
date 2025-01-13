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
 $("#mls_avail_time").easySelect({
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

jQuery(document).ready(function($){
    $(".mls-adminsc-info-toggle").hide();
    $(".mls-shortcode").hover(function(){
      $(this).siblings(".mls-adminsc-info-toggle").toggle();
    });
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


jQuery(document).ready(function($) {
    $(".styledSelect").click(function(){
		if ($(this).next("ul.options").is(":visible"))
			{
            $(this).parents("form").find(".styledSelect").next("ul.options").hide();
			$(this).removeClass("active");
            $(this).next("ul.options").show();
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
			}
		else
			{
			$(this).addClass("active");
            $(this).next("ul.options").hide();
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


jQuery(document).ready(function($) {
    $(document).ready(function() {
		$('input.mulpitply_checkbox_style').on('change', function() {
//			if ($(this).is(':checked')) {
				$('input.searchInputeasySelect').val(''); // Clear the search box value
				$('.scrolableDiv > li').show();
//			}
		});
	});
});



// jQuery(function($){
//    $('.switch input').on("click",function(){
//       $(this).parent().toggleClass('active');
//    });
// });

jQuery(document).ready(function ($) {	
  // Initialize the class based on the initial state
  $('input[type="checkbox"]').each(function () {
    if ($(this).prop('checked')) {
      $(this).parent('.switch').addClass('active');
    }
  });
  $('.proplang-note').hide();
  $('.tog-propdetailpage-row-show').hide();
  $('.tog-dark-row').hide();
  $('input[type="checkbox"]#tog-timing-hide').each(function () {
    if ($(this).prop('checked')) {
      $(this).parents("table").find('.tog-timing-row').hide();
    }
  });
  $('input[type="checkbox"]#tog-lang-hide').each(function () {
    if ($(this).prop('checked')) {
     $(this).parents("table").find('.tog-lang-row').hide();
    }
  });
  $('input[type="checkbox"]#tog-proplang-hide').each(function () {
    if ($(this).prop('checked')) {
     $(this).parents("table").find('.tog-proplang-row').hide();
     $(this).parents("table").find('.proplang-note').show();
    }
  });
  $('input[type="checkbox"]#tog-propdetailpage-hide').each(function () {
    if ($(this).prop('checked')) {
     $(this).parents("table").find('.tog-propdetailpage-row').hide();
     $(this).parents("table").find('.tog-propdetailpage-row-show').show();
    }
  });
  $('input[type="checkbox"]#tog-darklight-hide').each(function () {
    if ($(this).prop('checked')) {
     $(this).parents("table").find('.tog-light-row').hide();
     $(this).parents("table").find('.tog-dark-row').show();
    }
  });
	
  // Add toggle functionality for clicks
  $('.switch input[type="checkbox"]').on("change", function () {
    $(this).parent('.switch').toggleClass('active', $(this).prop('checked'));
  });
	
});

jQuery(document).ready(function($) {
	$('input[type="checkbox"]#tog-timing-hide').change(function() {
        if($(this).is(':checked')) {
  		    $(this).parents("table").find('.tog-timing-row').hide();
		} else {
  		    $(this).parents("table").find('.tog-timing-row').show();
		}
	});
	$('input[type="checkbox"]#tog-lang-hide').change(function() {
        if($(this).is(':checked')) {
  		    $(this).parents("table").find('.tog-lang-row').hide();
		} else {
  		    $(this).parents("table").find('.tog-lang-row').show();
		}
	});
	$('input[type="checkbox"]#tog-proplang-hide').change(function() {
        if($(this).is(':checked')) {
  		    $(this).parents("table").find('.tog-proplang-row').hide();
            $(this).parents("table").find('.proplang-note').show();
		} else {
  		    $(this).parents("table").find('.tog-proplang-row').show();
            $(this).parents("table").find('.proplang-note').hide();
		}
	});
	$('input[type="checkbox"]#tog-propdetailpage-hide').change(function() {
        if($(this).is(':checked')) {
  		    $(this).parents("table").find('.tog-propdetailpage-row').hide();
            $(this).parents("table").find('.tog-propdetailpage-row-show').show();
		} else {
  		    $(this).parents("table").find('.tog-propdetailpage-row').show();
            $(this).parents("table").find('.tog-propdetailpage-row-show').hide();
		}
	});
	$('input[type="checkbox"]#tog-darklight-hide').change(function() {
        if($(this).is(':checked')) {
  		    $(this).parents("table").find('.tog-light-row').hide();
            $(this).parents("table").find('.tog-dark-row').show();
		} else {
  		    $(this).parents("table").find('.tog-light-row').show();
            $(this).parents("table").find('.tog-dark-row').hide();
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
