jQuery(document).ready(function(){
    jQuery("span.grid").addClass("active");
    jQuery(".mls-tab-container").parents(".mls-form").addClass("mls-form-tab");
	jQuery("span.list").click(function(){
      jQuery(this).parents(".mls-parent-wrapper").find(".mls-pro-list-wrapper").removeClass("pro-grid");
      jQuery(this).parents(".mls-parent-wrapper").find(".mls-pro-list-wrapper").addClass("pro-list");
      jQuery(this).parents(".layout-filter").find(".active").removeClass("active");
      jQuery(this).addClass("active");
    });
    jQuery("span.grid").click(function(){
      jQuery(this).parents(".mls-parent-wrapper").find(".mls-pro-list-wrapper").removeClass("pro-list");
      jQuery(this).parents(".mls-parent-wrapper").find(".mls-pro-list-wrapper").addClass("pro-grid");
      jQuery(this).parents(".layout-filter").find(".active").removeClass("active");
      jQuery(this).addClass("active");
    });
    jQuery(".mls-dropdown").hide();
    jQuery(".price-range-iput-block").click(function(){
      jQuery(this).parents(".mls-form-group").find(".mls-dropdown").toggle();
    });
    jQuery(".tour-info-toggle").hide();
    jQuery(".tour-info").hover(function(){
      jQuery(this).parents(".mls-form-group").find(".tour-info-toggle").toggle();
    });
    jQuery("input:checked").parent(".property-schedule-singledate-wrapper").addClass("check");
    jQuery("input").click(function(){
      jQuery(this).parents(".schedule-date-slider").find(".check").removeClass("check");
      jQuery(this).parent(".property-schedule-singledate-wrapper").toggleClass("check");
    });
    jQuery('.loc a').attr('href', 'javascript:void(0);');
});

jQuery(document).ready(function($){
	var sourceValue = $('.styledSelect').html();
	$(this).parent("td").find(".easySelect-option-area").html(sourceValue);
})

jQuery(document).ready(function(){
if (jQuery(window).width()<991) {
    jQuery(".mls-share-social-fn").click(function(){
      if (jQuery(this).hasClass('active'))
      {
          jQuery(this).removeClass("active");
      }
        else
        {
          jQuery(this).parents(".mls-pro-list-wrapper").find(".active").removeClass("active");
          jQuery(this).toggleClass("active");
        }
    });
    jQuery(".loc-fv-toggle").click(function(){
      if (jQuery(this).hasClass('active'))
      {
          jQuery(this).removeClass("active");
      }
        else
        {
          jQuery(this).parents(".mls-pro-list-wrapper").find(".active").removeClass("active");
          jQuery(this).toggleClass("active");
        }
    });
}
});
jQuery(".mls-pro-list-wrapper.no-post").parent(".mls-container").find(".mls-list-filter").hide();
jQuery(".mls-pro-list-wrapper.no-post").parent(".mls-container").find(".mls-pagination").hide();

jQuery(document).ready(function() {
    jQuery('.cn-area a').on('click', function(e) {
    e.preventDefault();
    jQuery('html, body').animate(
    {scrollTop: jQuery(jQuery(this).attr('href')).offset().top-109,}, 500, 'linear');});         
});

jQuery(document).ready(function(jQuery){
  jQuery(".mls-project-listing-slider").slick({
    dots: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 5000,
    margin: 0,
    arrows: true
  });

    jQuery('.mls-project-slider').slick({
        dots: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      focusOnSelect: false,
      asNavFor: '.mls-project-nav-slider'
    });
    jQuery('.mls-project-nav-slider').slick({
      slidesToShow: 9,
      slidesToScroll: 1,
      asNavFor: '.mls-project-slider',
      dots: false,
      centerMode: false,
      focusOnSelect: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 7
          }
          },
        {
          breakpoint: 700,
          settings: {
            slidesToShow: 6
          }
          },
        {
          breakpoint: 550,
          settings: {
            slidesToShow: 5
          }
          },
        {
          breakpoint: 380,
          settings: {
            slidesToShow: 3
          }
          }
        ]	
    });
    
      jQuery(".schedule-date-slider").slick({
        dots: false,
        infinite: true,
        slidesToShow: 8,
        slidesToScroll: 5,
        autoplay: false,
        margin: 0,
        arrows: true,
        prevArrow:'<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></div>',
        nextArrow:'<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></div>',
          responsive: [
            {
              breakpoint: 1300,
              settings: {
                slidesToShow: 7
              }
              },
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 6
              }
              },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 7
              }
              },
            {
              breakpoint: 750,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 6
              }
              },
            {
              breakpoint: 575,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 5
              }
              },
            {
              breakpoint: 450,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 4
              }
              },
            {
              breakpoint: 380,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 3
              }
              },
            {
              breakpoint: 340,
              settings: {
				slidesToScroll: 1,
                slidesToShow: 2
              }
              }
            ]	
      });
});

jQuery(document).ready(function(jQuery) 
{
    lightGallery(document.getElementById('lightgallery'), {
        plugins: [lgZoom, lgThumbnail],
        speed: 500,
        download: false,
        selector: '.mls-project-li-wrapper',
        mobileSettings: {
            controls: true,
            showCloseIcon: true
        }
    });
	
//     lightGallery(document.getElementById('lightgallery2'), {
//         plugins: [lgZoom, lgThumbnail],
//         speed: 500,
//         download: false,
//         selector: '.mls-list-li-wrapper',
//         mobileSettings: {
//             controls: true,
//             showCloseIcon: true
//         }
//     });
//    jQuery(".img-count").click(function() {
//        jQuery(this).parents(".mls-property-img").children("#lightgallery2 .mls-list-li-wrapper:first-child > img").trigger("click");
//    });
});


jQuery(document).ready(function ($) {
        // Initialize LightGallery
        //const videoGallery = lightGallery(document.getElementById('video-gallery'), {
			lightGallery(document.getElementById('open-video-tour-pop'), {
            selector: 'iframe',
            plugins: [lgVideo],
            download: false,
				counter : false
        });
			lightGallery(document.getElementById('open-virtual-tour-pop'), {
            selector: 'iframe',
            plugins: [lgVideo],
            download: false,
				counter : false
        });
	
	$('#open-video-tour').on('click', function() {
	   $('#open-video-tour-pop iframe').trigger('click');
	});
	$('#open-virtual-tour').on('click', function() {
	   $('#open-virtual-tour-pop iframe').trigger('click');
	});
	
//         jQuery('a#open-video-tour').on('click', function () {
//             jQuery(this).parents(".mls-virtual-btn").children('#open-video-gallery a:first-child').trigger('click'); 
//         });
    });

        // Open the video on button click
//         jQuery('#open-video-tour').on('click', function () {
//             jQuery(this).parentsUntil(".wrapper").find('#video-gallery a:first-child').trigger('click'); 
//         });

// jQuery(document).on('click', '#open-video-tour', function(e) {
//     jQuery('#open-video-gallery a:first-child').trigger('click');
// 	alert('triggered download');
// });

// jQuery(document).ready(function($) {
// $('#open-video-tour').on('click', function () {
//     const videoLink = $(this).parents('.mls-prj-left').find('#video-gallery > a:first-child');
//     console.log('Video link found:', videoLink.length > 0);
//     videoLink.trigger('click');
// });
// });

//jQuery(".img-count").on("click", function() {
//    jQuery(this).parentsUntil(".mls-property-img").find("#lightgallery2 .mls-list-li-wrapper:first-child > img").trigger("click");
//});


//jQuery(document).ready(function() {
//  const jQuerylg = jQuery('#lightgallery');
//
//  jQuerylg.lightGallery({ pager: true });
//
//  // Fired after the gallery has been closed
//  jQuery('#lightgallery').on('onCloseAfter.lg',function(event){
//    // Destroy old gallery and re-initialize with default settings:
//    jQuerylg.data('lightGallery').destroy(true);
//    jQuerylg.lightGallery({ pager: true });
//  });
//  
//
//  jQuery("li.img-count").on("click", function(){
//
//    // Destroy old gallery and initialize with autoplay === true
//    jQuerylg.data('lightGallery').destroy(true);
//    setTimeout(function(){
//      jQuerylg.lightGallery({ autoplay: true });
//      jQuery("#lightgallery a:first-child > img").trigger("click");
//    },0)
//    
//  });
//});

/*Search Form Submit ajax functino*/
//  jQuery(document).ready(function(jQuery) {
//     jQuery('.mls-proplist-search-form').on('submit', function(e) {
//         e.preventDefault(); // Prevent the form from submitting normally

//         var formData = jQuery(this).serialize(); // Serialize the form data

//         jQuery.ajax({
//             type: 'POST',
//             url: mls_ajax_obj.ajax_url, // Use the admin-ajax URL
//             data: formData + '&action=mls_plugin_search_properties', // Append action to data
//             success: function(response) {
//                 // Display the result after the search container
//                 jQuery('.search-results').html(response);
//             },
//             error: function(xhr, status, error) {
//                 console.log('Error: ' + error);
//             }
//         });
//     });
// });

jQuery(document).ready(function(){
 jQuery(".mls_area_sel").easySelect({
     buttons: true,
     search: true,
     placeholder: mlsTranslations.search_area,
     placeholderColor: '',
     selectColor: '#524781',
     itemTitle: 'Car selected',
     showEachItem: true,
     width: '100%',
     dropdownMaxHeight: '350px',
 })
 jQuery(".mls_type_sel").easySelect({
     buttons: true,
     search: true,
     placeholder: mlsTranslations.search_property_type,
     placeholderColor: '',
     selectColor: '#524781',
     itemTitle: 'Car selected',
     showEachItem: true,
     width: '100%',
     dropdownMaxHeight: '350px',
 })
//  jQuery("#mls_search_area_ban").easySelect({
//      buttons: true,
//      search: true,
//      placeholder: 'Area',
//      placeholderColor: '',
//      selectColor: '#524781',
//      itemTitle: 'Car selected',
//      showEachItem: true,
//      width: '100%',
//      dropdownMaxHeight: '350px',
//  })
//  jQuery("#mls_search_type_ban").easySelect({
//      buttons: true, 
//      search: true,
//      placeholder: 'Type',
//      placeholderColor: '',
//      selectColor: '#524781',
//      itemTitle: 'Car selected',
//      showEachItem: true,
//      width: '100%',
//      dropdownMaxHeight: '350px',
//  })
});


//jQuery(document).ready(function(){
//const rangeInput = document.querySelectorAll(".range-input input"),
//  priceInput = document.querySelectorAll(".price-input input"),
//  priceeInput = document.querySelectorAll(".price-input1 input"),
//  range = document.querySelector(".slider .progress");
//let priceGap = 1000;
//    
//priceeInput.forEach((input) => {
//  input.addEventListener("input", (e) => {
//    let minPricee = parseInt(priceeInput[0].value),
//      maxPricee = parseInt(priceeInput[1].value);
//
//    if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
//      if (e.target.className === "input-min1") {
//        rangeInput[0].value = minPricee;
//        range.style.left = (minPricee / rangeInput[0].max) * 100 + "%";
//      } else {
//        rangeInput[1].value = maxPricee;
//        range.style.right = 100 - (maxPricee / rangeInput[1].max) * 100 + "%";
//      }
//    }
//  });
//});
//
//priceInput.forEach((input) => {
//  input.addEventListener("input", (e) => {
//    let minPrice = parseInt(priceInput[0].value),
//      maxPrice = parseInt(priceInput[1].value);
//
//    if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
//      if (e.target.className === "input-min") {
//        rangeInput[0].value = minPrice;
//        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
//      } else {
//        rangeInput[1].value = maxPrice;
//        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
//      }
//    }
//  });
//});
//    
//    
//rangeInput.forEach((input) => {
//  input.addEventListener("input", (e) => {
//    let minVal = parseInt(rangeInput[0].value),
//      maxVal = parseInt(rangeInput[1].value);
//
//    if (maxVal - minVal < priceGap) {
//      if (e.target.className === "range-min") {
//        rangeInput[0].value = maxVal - priceGap;
//      } else {
//        rangeInput[1].value = minVal + priceGap;
//      }
//    } else {
//      priceInput[0].value = minVal;
//      priceInput[1].value = maxVal;
//      range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
//      range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
//    }
//  });
//});
//});


jQuery(document).ready(function(){
    
	jQuery("#min_price,#max_price").on('change', function () {
	  jQuery('#price-range-submit').show();
	  var min_price_range = parseInt(jQuery("#min_price").val());
	  var max_price_range = parseInt(jQuery("#max_price").val());
	  if (min_price_range > max_price_range) {
		jQuery('#max_price').val(min_price_range);
	  }
	  jQuery("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });
	});

	jQuery("#min_price,#max_price").on("paste keyup", function () {                                        
	  var min_price_range = parseInt(jQuery("#min_price").val());
	  var max_price_range = parseInt(jQuery("#max_price").val());
	  if(min_price_range == max_price_range){
			max_price_range = min_price_range + 100;
			jQuery("#min_price").val(min_price_range);		
			jQuery("#max_price").val(max_price_range);
	  }
	  jQuery("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });
	});

	jQuery(function () {
	  jQuery("#slider-range").slider({
		range: true,
		orientation: "horizontal",
		min: 0,
		max: 10000,
		values: [0, 10000],
		step: 100,
		slide: function (event, ui) {
		  if (ui.values[0] == ui.values[1]) {
			  return false;
		  }
		  jQuery("#min_price").val(ui.values[0]);
		  jQuery("#max_price").val(ui.values[1]);
		}
	  });

	  jQuery("#min_price").val(jQuery("#slider-range").slider("values", 0));
	  jQuery("#max_price").val(jQuery("#slider-range").slider("values", 1));
	});

	jQuery("#slider-range,#price-range-submit").click(function () {
	  var min_price = jQuery('#min_price').val();
	  var max_price = jQuery('#max_price').val();
	  jQuery("#pricerangeResults").val("jQuery" + min_price  +" "+ "to" + " "+ max_price);
	  jQuery("#pricerangeDisplay").val("jQuery" + min_price  +" "+ "to" + " "+ max_price);
	});
    jQuery("#price-range-reset").click(function () {
	  jQuery("#slider-range").slider('option',{min: 0, max: 10000});
    });

});

jQuery(window).scroll(function(){
  if (jQuery(window).scrollTop() >= 200) {
    jQuery('header').addClass('fixed');
   }
   else {
    jQuery('header').removeClass('fixed');
   }
});

 jQuery(document).ready(function() {
	let stickyHeaderHeight = parseInt(mlsTranslations.mls_plugin_prop_detailsidebaroffset, 10);
	 console.log(mlsTranslations.mls_plugin_prop_detailsidebaroffset);
   jQuery('.mls-prj-sidebar').stickySidebar({
        topSpacing: stickyHeaderHeight,
        container: '.mls-prj-detail-full',
        sidebarInner: '.mls-prj-sidebar-inner',
        callback: function() {
            console.log('Sticky sidebar fired!');
        }
    });
 });


jQuery(document).ready(function(jQuery) {
    // Check if the URL contains the anchor #contact
    if (window.location.hash === '#contact-from') {
        // Set a small delay to ensure the browser has already scrolled
        setTimeout(function() {
            // Get the position of the contact section
            var jQuerycontactSection = jQuery('#contact-from');
            if (jQuerycontactSection.length) {
                // Define the offset (e.g., 100px)
                var offset = 110;

                // Get the element's position and scroll to it with the offset
                var elementPosition = jQuerycontactSection.offset().top;
                var offsetPosition = elementPosition - offset;

                // Scroll to the position with smooth behavior
                jQuery('html, body').animate({
                    scrollTop: offsetPosition
                }, 800); // Adjust the duration of animation as needed (800ms in this case)
            }
        }, 500); // Adjust the delay as needed
    }
});

jQuery(document).ready(function(jQuery) {
    jQuery(".styledSelect").click(function(){
		if (jQuery(this).next("ul.options").is(":visible"))
			{
            jQuery(this).parents("body").find(".styledSelect").next("ul.options").hide();
			jQuery(this).removeClass("active");
            jQuery(this).next("ul.options").show();
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
``			}
		else
			{
			jQuery(this).addClass("active");
            jQuery(this).next("ul.options").hide();
				jQuery('input.searchInputeasySelect').val(''); // Clear the search box value
				jQuery('.scrolableDiv > li').show();
			}
    })
})

//jQuery(document).ready(function() {
//  jQuery("input.searchInputeasySelect").on("keyup", function(e) {
//      e.stopPropagation();
//    var value = jQuery(this).val().toLowerCase();
//
//    jQuery(this).parents(".options").find(".scrolableDiv > li").show().filter(function() { 
//      return jQuery(this).find('label').text().toLowerCase().indexOf(value) === -1;
//    }).hide(); 
//  });
//});


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

jQuery(document).ready(function() {
    jQuery(function() {
        jQuery('#mls_search_keyword').tagsInput({
            'delimiter': [',', ';'],
            'unique': true,
            'minChars': 1,
            'maxChars': null,
            'limit': null,
            placeholder: mlsTranslations.search_reference_id,
        });
        jQuery('#mls_search_keyword_ban').tagsInput({
            'delimiter': [',', ';'],
            'unique': true,
            'minChars': 1,
            'maxChars': null,
            'limit': null,
            placeholder: mlsTranslations.search_reference_id,
        });
    });
});

jQuery(document).ready(function(jQuery) {
    jQuery('#mls-lead-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
		
		// Reset error messages
        jQuery('.error-message').text('');
		var phonenumbercode = $('.iti__selected-dial-code').text();
        var isValid = true;
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phonePattern = /^\d{4,15}$/; // Example for a 9,15-digit phone number

        // Validate scheduledate
        if (!jQuery('input[name="scheduledate"]:checked').val()) {
    jQuery('#scheduledateError').text(mlsTranslations.mls_propertdetail_form_scheduledate);
    isValid = false;
}

        // Validate name
        if (jQuery('#user').val().trim() === '') {
            jQuery('#userError').text(mlsTranslations.mls_propertdetail_form_name);
            isValid = false;
        }

        // Validate email
        if (jQuery('#email').val().trim() === '') {
            jQuery('#emailError').text(mlsTranslations.mls_propertdetail_form_email);
            isValid = false;
        } else if (!emailPattern.test(jQuery('#email').val().trim())) {
            jQuery('#emailError').text(mlsTranslations.mls_propertdetail_form_valid_email);
            isValid = false;
        }

        // Validate phone
        if (jQuery('#phone').val().trim() === '') {
    jQuery('#phoneError').text(mlsTranslations.mls_propertdetail_form_phone_number);
    isValid = false;
} else if (!phonePattern.test(jQuery('#phone').val().trim())) {
    jQuery('#phoneError').text(mlsTranslations.mls_propertdetail_form_valid_phone_number);
    isValid = false;
} else {
    jQuery('#phoneError').text(''); // Clear the error message if valid
}
		
		if (isValid) {
		
        var formData = jQuery(this).serialize(); // Serialize form data
		formData += '&phonenumbercode=' + encodeURIComponent(phonenumbercode);
		console.log(formData);
        jQuery.ajax({
            url: mls_ajax_obj.ajax_url, // AJAX handler
            type: 'POST',
            data: formData + '&action=mls_plugin_handle_lead_form', // Add action to data
			beforeSend: function() {
                    jQuery('#mlsSubmitButton').prop('disabled', true).val(mlsTranslations.mls_propertdetail_form_submitting);
                },
            success: function(response) {
				jQuery('#mlsSubmitButton').prop('disabled', false).val(mlsTranslations.mls_propertdetail_form_submitrequest);
                if (response.success) {
                    jQuery('#form-response-message').html('<p class="success-message">' + response.data.message + '</p>');
                    jQuery('#mls-lead-form')[0].reset(); // Reset form fields on success
                } else {
                    jQuery('#form-response-message').html('<p class="error-message">' + response.data.message + '</p>');
                }
            },
            error: function() {
                jQuery('#form-response-message').html('<p class="error-message">'+ mlsTranslations.mls_propertdetail_form_submiterror +'</p>');
            }
        });
	}else {
                    jQuery('#form-response-message').html('<p class="error-message">'+ mlsTranslations.mls_propertdetail_form_submitrequiredmissing +'</p>');
                }
    });
});

jQuery(document).ready(function() {
   var input = document.querySelector(".phone-field #phone");
   window.intlTelInput(input,({
	  countrySearch:true,
	   initialCountry:"es",
	   separateDialCode: true
   }));        
});

jQuery(document).ready(function($) {
	$("ul.srh-tab-nav").each(function(){
        var countLi = $(this).find("li").length;
        if(countLi == 4 ){
           $(this).parents("form").find("ul.srh-tab-nav").addClass("c4");
         }
        if(countLi == 3 ){
           $(this).parents("form").find("ul.srh-tab-nav").addClass("c3");
         }
        if(countLi == 2 ){
           $(this).parents("form").find("ul.srh-tab-nav").addClass("c2");
         }
        if(countLi == 1 ){
           $(this).parents("form").find("ul.srh-tab-nav").addClass("c1");
         }
    });

});
function updateDateSlider() {
    const monthSelect = document.getElementById('month-select');
    const yearSelect = document.getElementById('year-select');
    const dateSlider = jQuery('#date-slider'); // Use jQuery for better integration

    const selectedMonth = monthSelect.value;
    const selectedYear = yearSelect.value;

    if (!selectedMonth || !selectedYear) {
        dateSlider.html('<p>Please select both Month and Year.</p>');
        return;
    }

    // AJAX request using jQuery
    jQuery.ajax({
        url: mls_ajax_obj.ajax_url,
        type: 'POST',
        data: {
            action: 'update_dates',
            month: selectedMonth,
            year: selectedYear
        },
        success: function (response) {
            // Destroy the previous slick slider instance
            if (dateSlider.hasClass('slick-initialized')) {
                dateSlider.slick('unslick');
            }

            // Update the slider content
            dateSlider.html(response);

            // Reinitialize the slick slider
            dateSlider.slick({
        dots: false,
        infinite: true,
        slidesToShow: 8,
        slidesToScroll: 5,
        autoplay: false,
        margin: 0,
        arrows: true,
        prevArrow:'<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i></div>',
        nextArrow:'<button type="button" class="slick-next"><i class="fa-solid fa-chevron-right"></i></div>',
          responsive: [
            {
              breakpoint: 1300,
              settings: {
                slidesToShow: 7
              }
              },
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 6
              }
              },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 7
              }
              },
            {
              breakpoint: 750,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 6
              }
              },
            {
              breakpoint: 575,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 5
              }
              },
            {
              breakpoint: 450,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 4
              }
              },
            {
              breakpoint: 380,
              settings: {
				slidesToScroll: 2,
                slidesToShow: 3
              }
              },
            {
              breakpoint: 340,
              settings: {
				slidesToScroll: 1,
                slidesToShow: 2
              }
              }
            ]	
      });
        },
        error: function () {
            console.error('Failed to fetch dates');
        }
    });
}
