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

jQuery(function($){
 if(window.MLSSelecttypes) MLSSelecttypes.init(".mls_type_sel");
 if(window.MLSSelectLocation) MLSSelectLocation.init("#mls_avail_time");
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
  jQuery('.mls_tog_thirdpartyform_row').hide();
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
	jQuery('input[type="checkbox"]#tog-leadform-hide').each(function () {
    if (jQuery(this).prop('checked')) {
     jQuery(this).parents("table").find('tr:not(:first)').hide();
	 jQuery(this).parents('tr').show();
     jQuery(this).parents("table").find('.mls_tog_thirdpartyform_row').show();
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
	jQuery('input[type="checkbox"]#tog-leadform-hide').change(function() {
        if(jQuery(this).is(':checked')) {
  		    jQuery(this).parents("table").find('tr:not(:first)').hide();
			jQuery(this).parents('tr').show();
            jQuery(this).parents("table").find('.mls_tog_thirdpartyform_row').show();
		} else {
  		    jQuery(this).parents("table").find('tr:not(:first)').show();
			jQuery(this).parents('tr').show();
            jQuery(this).parents("table").find('.mls_tog_thirdpartyform_row').hide();
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

jQuery(document).on('click', '.scrolableDiv li label', function () {
  const label = jQuery(this).text().trim(); 
  const input = jQuery(this).find('input'); 
  const isChecked = input.is(':checked'); 

  // If it's a parent
  const selectOption = jQuery(`#mls_property_types option:contains("${label}")`);
  if (selectOption.attr('data-role') === 'parent') {
    const group = selectOption.data('group');
    jQuery(`#mls_property_types option[data-group="${group}"].child-option`).each(function () {
      jQuery(this).prop('selected', isChecked);
    });

    // Update plugin
    jQuery('#mls_property_types').trigger('change');
  }
});


// Location group manager
// Location group manager - improved custom parent toggle (checkbox)
jQuery(function ($) {
  const $tableBody = $('#mls-lg-table tbody');

  // helper: initialize row UI based on hidden parent_type value
  function syncRowUI($row) {
    const $parentTypeHidden = $row.find('.mls_parent_type_hidden');
    const type = ($parentTypeHidden.length && $parentTypeHidden.val() === 'custom') ? 'custom' : 'select';
    const $selectWrap = $row.find('.mls-select-wrap');
    const $customWrap = $row.find('.mls-custom-wrap');
    const $checkbox = $row.find('.mls_parent_toggle_checkbox');
    const $select = $row.find('.mls_location_parent');
    const $custom = $row.find('.mls_location_parent_custom');
    const $hidden = $row.find('.mls_location_parent_hidden');

    if (type === 'custom') {
      $checkbox.prop('checked', true);
      $selectWrap.hide();
      $customWrap.show();
      // canonical parent value is from custom input (if present)
      $hidden.val($custom.val() ? $custom.val() : '');
      $parentTypeHidden.val('custom');
    } else {
      $checkbox.prop('checked', false);
      $selectWrap.show();
      $customWrap.hide();
      // canonical parent value from select
      $hidden.val($select.val() ? $select.val() : '');
      $parentTypeHidden.val('select');
    }
  }

  // when checkbox toggles
  $tableBody.on('change', '.mls_parent_toggle_checkbox', function () {
    const $row = $(this).closest('tr.mls-lg-row');
    const checked = $(this).is(':checked');
    const $selectWrap = $row.find('.mls-select-wrap');
    const $customWrap = $row.find('.mls-custom-wrap');
    const $parentTypeHidden = $row.find('.mls_parent_type_hidden');
    const $hidden = $row.find('.mls_location_parent_hidden');

    if (checked) {
      $selectWrap.hide();
      $customWrap.show();
      $parentTypeHidden.val('custom');
      // set canonical parent to custom input value (if any)
      const cval = $row.find('.mls_location_parent_custom').val();
      $hidden.val(cval ? cval : '');
    } else {
      $selectWrap.show();
      $customWrap.hide();
      $parentTypeHidden.val('select');
      const sval = $row.find('.mls_location_parent').val();
      $hidden.val(sval ? sval : '');
    }
  });

  // when select or custom input changes, update canonical hidden parent
  $tableBody.on('change input', '.mls_location_parent, .mls_location_parent_custom', function () {
    const $row = $(this).closest('tr.mls-lg-row');
    const $hidden = $row.find('.mls_location_parent_hidden');
    $hidden.val($(this).val() ? $(this).val() : '');
  });

  // on form submit ensure each row canonical parent & parent_type are synced
  $(document).on('submit', 'form', function () {
    $tableBody.find('tr.mls-lg-row').each(function () {
      syncRowUI($(this));
    });
    // continue submit
  });

  // add new row (clone first)
  $('#mls-lg-add').on('click', function (e) {
    e.preventDefault();
    const $rows = $tableBody.find('tr.mls-lg-row');
    if (!$rows.length) return;
    const index = $rows.length;
    const $clone = $rows.first().clone();

    // reset values & update names to new index
    $clone.find('input, select').each(function () {
      const $el = $(this);
      const name = $el.attr('name');
      if (name) {
        $el.attr('name', name.replace(/\[\d+]/, '[' + index + ']'));
      }

      // reset visible values
      if ($el.is('select')) {
        $el.prop('selectedIndex', 0);
      } else if ($el.hasClass('mls_location_parent_custom')) {
        $el.val('');
      } else if ($el.hasClass('mls_location_parent_hidden')) {
        $el.val('');
      } else if ($el.hasClass('mls_parent_type_hidden')) {
        $el.val('select'); // default
      } else if ($el.is(':checkbox')) {
        $el.prop('checked', false);
      } else if ($el.is(':text')) {
        // any other text input reset
        $el.val('');
      }
    });

    // ensure UI starts with select shown
    $clone.find('.mls-select-wrap').show();
    $clone.find('.mls-custom-wrap').hide();

    $tableBody.append($clone);
    syncRowUI($clone);
	  
	if (window.MLSSelectLocation) {
  	window.MLSSelectLocation.init('.mls-multiselect');
  	console.log('MLSSelectLocation initialized on .mls-multiselect');
	}

  });

  // remove row
  $tableBody.on('click', '.mls-lg-remove, .dashicons-no-alt', function (e) {
    e.preventDefault();
    const $rows = $tableBody.find('tr.mls-lg-row');
    if ($rows.length > 1) {
      $(this).closest('tr.mls-lg-row').remove();
    }
  });

  // predefined locations: mlsPredefinedLocations should be an object like { "Parent A": ["Child1", "Child2"], ... }
 
	$('#mls-lg-add-predefined').on('click', function (e) {
  e.preventDefault();
  const $btn = $(this);
  if (typeof mlsPredefinedLocations === 'undefined') return;

  // disable button & show spinner
  $btn.prop('disabled', true).addClass('loading');
  const originalText = $btn.text();
  $btn.data('original-text', originalText);
  $btn.html('<span class="spinner" style="display:inline-block;width:16px;height:16px;border:2px solid #ccc;border-top-color:#333;border-radius:50%;animation:spin 0.8s linear infinite;margin-right:6px;vertical-align:middle;"></span> Loading...');

  // small async delay to allow UI to repaint before processing heavy loop
  setTimeout(function () {
    $.each(mlsPredefinedLocations, function (parent, children) {
      const $base = $tableBody.find('tr.mls-lg-row').first();
      const index = $tableBody.find('tr.mls-lg-row').length;
      const $clone = $base.clone();

      // update names
      $clone.find('input, select').each(function () {
        const $el = $(this);
        const name = $el.attr('name');
        if (name) {
          $el.attr('name', name.replace(/\[\d+]/, '[' + index + ']'));
        }
        if ($el.is('select')) {
          $el.prop('selectedIndex', 0);
        } else if ($el.hasClass('mls_location_parent_custom')) {
          $el.val('');
        } else if ($el.hasClass('mls_location_parent_hidden')) {
          $el.val('');
        } else if ($el.hasClass('mls_parent_type_hidden')) {
          $el.val('select');
        } else if ($el.is(':checkbox')) {
          $el.prop('checked', false);
        }
      });

      const $parentSelect = $clone.find('.mls_location_parent');
      const $parentCustom = $clone.find('.mls_location_parent_custom');
      const $parentHidden = $clone.find('.mls_location_parent_hidden');
      const $parentTypeHidden = $clone.find('.mls_parent_type_hidden');
      const $selectWrap = $clone.find('.mls-select-wrap');
      const $customWrap = $clone.find('.mls-custom-wrap');

      if ($parentSelect.find(`option[value="${parent}"]`).length) {
        $parentSelect.val(parent);
        $selectWrap.show();
        $customWrap.hide();
        $parentHidden.val(parent);
        $parentTypeHidden.val('select');
      } else {
//      showMissingNotice(parent, child);
        $parentCustom.val(parent);
        $selectWrap.hide();
        $customWrap.show();
        $parentHidden.val(parent);
        $parentTypeHidden.val('custom');
        $clone.find('.mls_parent_toggle_checkbox').prop('checked', true);
      }

      const $childSel = $clone.find('.mls_location_childgroups');
      if (Array.isArray(children)) {
        children.forEach(function (child) {
          if ($childSel.find(`option[value="${child}"]`).length) {
            $childSel.find(`option[value="${child}"]`).prop('selected', true);
          }else {
//             showMissingNotice(parent, child);
          }
        });
      }

      $tableBody.append($clone);
      syncRowUI($clone);
      if (window.MLSSelectLocation) {
        window.MLSSelectLocation.init('.mls-multiselect');
      }
    });

    // re-enable button & remove spinner
    $btn.prop('disabled', false).removeClass('loading').text(originalText);
  }, 100);
});
 
  // show missing notice
  function showMissingNotice(parent, child) {
    const msg = child
      ? `⚠️ Missing predefined Child "${child}" under Parent "${parent}"`
      : `⚠️ Missing predefined Parent "${parent}"`;
    if (!$('#mls-lg-missing').length) {
      $('#mls-lg-table').before('<div id="mls-lg-missing" class="notice notice-warning"><ul></ul></div>');
    }
    $('#mls-lg-missing ul').append('<li>' + msg + '</li>');
  }

  // init: sync existing rows on load
  $tableBody.find('tr.mls-lg-row').each(function () {
    syncRowUI($(this));
  });
});


