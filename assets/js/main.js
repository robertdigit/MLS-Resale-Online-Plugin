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

jQuery(document).ready(function(jQuery){
var sourceValue = jQuery('.styledSelect').html();
jQuery(this).parent("td").find(".easySelect-option-area").html(sourceValue);
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


jQuery(document).ready(function (jQuery) {
// Initialize LightGallery

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

jQuery('#open-video-tour').on('click', function() {
   jQuery('#open-video-tour-pop iframe').trigger('click');
});
jQuery('#open-virtual-tour').on('click', function() {
   jQuery('#open-virtual-tour-pop iframe').trigger('click');
});

});


/*Search Form Submit ajax functino*/

jQuery(function($){
 if(window.MLSSelecttypes) MLSSelecttypes.init(".mls_type_sel");
	if(window.MLSSelectLocation) {
		MLSSelectLocation.init(".mls_area_sel");
		MLSSelectLocation.init(".feature_sel")
	};
});


// price field filter script start
jQuery(document).ready(function () {
  // Initialize sliders for each instance
  jQuery(".slider-range-wrapper").each(function () {
    var sliderWrapper = jQuery(this);
    var defaultMinPrice = parseInt(sliderWrapper.data("min")) || 0;
    var defaultMaxPrice = parseInt(sliderWrapper.data("max")) || 10000000;
    var currency = sliderWrapper.data("currency") || "EUR";
    
    // Add error styling
    jQuery('head').append(`
      <style>
        .price-range-invalid { border: 2px solid #ff9800 !important; transition: border-color 0.3s !important; }
        .price-range-error {
          color: #d9534f;
          font-size: 12px;
          margin-top: 5px;
          display: none;
        }
      </style>
    `);

    // Currency symbols and formatting options
    var currencyFormats = {
      EUR: { symbol: "€", code: "EUR", locale: "de-DE" },
      GBP: { symbol: "£", code: "GBP", locale: "en-GB" },
      USD: { symbol: "$", code: "USD", locale: "en-US" },
      RUB: { symbol: "₽", code: "RUB", locale: "ru-RU" },
      TRY: { symbol: "₺", code: "TRY", locale: "tr-TR" },
      SAR: { symbol: "ر.س", code: "SAR", locale: "ar-SA" },
    };

    var currencyFormat = currencyFormats[currency] || currencyFormats.EUR;

    var sliderRange = sliderWrapper.find(".slider-range");
    var priceInputField = sliderWrapper.closest(".mls-form-group").find(".price-input");
	 var minInput = sliderWrapper.closest(".mls-form-group").find(".min_price");
    var maxInput = sliderWrapper.closest(".mls-form-group").find(".max_price");
    var priceRangeResults = sliderWrapper.closest(".mls-form-group").find(".pricerangeResults");
    var priceRangeDisplay = sliderWrapper.closest(".mls-form-group").find(".pricerangeDisplay");

    // Add error message elements
    priceInputField.after('<div class="price-range-error min-error"></div>');
    priceInputField.after('<div class="price-range-error max-error"></div>');
    var errorElements = priceInputField.nextAll('.price-range-error');
	var minError = errorElements.filter('.min-error');
	var maxError = errorElements.filter('.max-error');


    // Set placeholders
    minInput.attr("placeholder", defaultMinPrice.toLocaleString(currencyFormat.locale, {
      style: "currency",
      currency: currencyFormat.code,
      maximumFractionDigits: 0
    }));
    maxInput.attr("placeholder", defaultMaxPrice.toLocaleString(currencyFormat.locale, {
      style: "currency",
      currency: currencyFormat.code,
      maximumFractionDigits: 0
    }));

    // Get user-selected values from inputs (if any)
    var userMinPrice = parseInt(minInput.val()) || defaultMinPrice;
    var userMaxPrice = parseInt(maxInput.val()) || defaultMaxPrice;

    // Ensure user values are within the default range
    userMinPrice = Math.max(userMinPrice, defaultMinPrice);
    userMaxPrice = Math.min(userMaxPrice, defaultMaxPrice);

    // Clear input fields if values are default
    if (userMinPrice === defaultMinPrice) {
      minInput.val("");
    }
    if (userMaxPrice === defaultMaxPrice) {
      maxInput.val("");
    }

    // Initialize slider
    sliderRange.slider({
      range: true,
      orientation: "horizontal",
      min: defaultMinPrice,
      max: defaultMaxPrice,
      values: [userMinPrice, userMaxPrice],
      step: 1,
      slide: function (event, ui) {
        if (ui.values[0] == ui.values[1]) {
          return false;
        }
        minInput.val(ui.values[0]);
        maxInput.val(ui.values[1]);
        updatePriceRangeDisplay(minInput, maxInput, priceRangeResults, priceRangeDisplay, currencyFormat, defaultMinPrice, defaultMaxPrice);
      }
    });

    // Set initial values in inputs (if not default)
    if (userMinPrice !== defaultMinPrice) {
      minInput.val(userMinPrice);
    }
    if (userMaxPrice !== defaultMaxPrice) {
      maxInput.val(userMaxPrice);
    }
    updatePriceRangeDisplay(minInput, maxInput, priceRangeResults, priceRangeDisplay, currencyFormat, defaultMinPrice, defaultMaxPrice);

    // NEW INPUT HANDLER - Removed instant validation between fields
    minInput.add(maxInput).on('input', function () {
      var input = jQuery(this);
      var isMinInput = input.hasClass("min_price");
      var currentVal = input.val();
      
      // Clear errors while typing
      input.removeClass('price-range-invalid');
      isMinInput ? minError.hide() : maxError.hide();

      if (currentVal === "") {
        return;
      }

      // Auto-correct if beyond absolute min/max
      var numVal = parseInt(currentVal) || (isMinInput ? defaultMinPrice : defaultMaxPrice);
      var corrected = false;
      
		var validate = validatePriceRange();
      if (validate == true) {
		jQuery("input[type='submit']").prop('disabled', false);
      }
	  
//       if (isMinInput && numVal < defaultMinPrice) {
//         numVal = defaultMinPrice;
//         corrected = true;
//       } else if (!isMinInput && numVal > defaultMaxPrice) {
//         numVal = defaultMaxPrice;
//         corrected = true;
//       }
      
//       if (corrected) {
//         input.val(numVal);
//         input.addClass('input-corrected');
//         setTimeout(function() {
//           input.removeClass('input-corrected');
//         }, 1000);
//       }
    });

    // Validate on blur (only absolute min/max, not cross-field)
    minInput.add(maxInput).on('blur', function () {
      var input = jQuery(this);
      var isMinInput = input.hasClass("min_price");
      var currentVal = input.val();
      
      if (currentVal === "") {
        updateSliderAndDisplay();
        return;
      }

      var numVal = parseInt(currentVal) || (isMinInput ? defaultMinPrice : defaultMaxPrice);
      
//       if (isMinInput && numVal < defaultMinPrice) {
//         input.val(defaultMinPrice);
//       } else if (!isMinInput && numVal > defaultMaxPrice) {
//         input.val(defaultMaxPrice);
//       }
      
      updateSliderAndDisplay();
	  var validate = validatePriceRange();
      if (validate == true) {
		jQuery("input[type='submit']").prop('disabled', false);
      }
    });

    // NEW VALIDATION FUNCTION FOR DONE BUTTON
    function validatePriceRange() {
      var minVal = parseInt(minInput.val()) || defaultMinPrice;
      var maxVal = parseInt(maxInput.val()) || defaultMaxPrice;
      var isValid = true;

      // Clear previous errors
      minInput.removeClass('price-range-invalid');
      maxInput.removeClass('price-range-invalid');
      minError.hide();
      maxError.hide();

      // Validate against absolute min/max
      if (minVal < defaultMinPrice) {
        minInput.addClass('price-range-invalid');
        minError.text(`* Minimum cannot be less than ${defaultMinPrice.toLocaleString(currencyFormat.locale, {
          style: "currency",
          currency: currencyFormat.code,
          maximumFractionDigits: 0
        })}`).show();
        isValid = false;
      }

      if (maxVal > defaultMaxPrice) {
        maxInput.addClass('price-range-invalid');
        maxError.text(`* Maximum cannot exceed ${defaultMaxPrice.toLocaleString(currencyFormat.locale, {
          style: "currency",
          currency: currencyFormat.code,
          maximumFractionDigits: 0
        })}`).show();
        isValid = false;
      }

      // Validate min <= max only if absolute checks passed
      if (isValid && minVal > maxVal) {
        minInput.addClass('price-range-invalid');
        maxInput.addClass('price-range-invalid');
        minError.text('* Minimum cannot exceed maximum').show();
        isValid = false;
      }
	  if(isValid == false) {
  		jQuery("input[type='submit']").prop('disabled', true);
	  }

      if (isValid) {
        sliderRange.slider("values", [minVal, maxVal]);
        updatePriceRangeDisplay(minInput, maxInput, priceRangeResults, priceRangeDisplay, currencyFormat, defaultMinPrice, defaultMaxPrice);
      }
      
      return isValid;
    }

    // Update Done button handler
    jQuery(".price-range-done").click(function(event) {
      event.stopPropagation();
      var validate = validatePriceRange();
      if (validate == true) {
        jQuery(this).closest(".mls-form-group").find(".mls-dropdown").hide();
		jQuery("input[type='submit']").prop('disabled', false);
      }
    });

    // Reset functionality
    sliderWrapper.closest(".mls-form-group").find(".price-range-reset").click(function (e) {
      e.preventDefault();
      minInput.val("");
      maxInput.val("");
      minError.hide();
      maxError.hide();
      updateSliderAndDisplay();
    });

    function updateSliderAndDisplay() {
      var minVal = parseInt(minInput.val()) || defaultMinPrice;
      var maxVal = parseInt(maxInput.val()) || defaultMaxPrice;
      
      // Update slider
      sliderRange.slider("values", [minVal, maxVal]);
      updatePriceRangeDisplay(minInput, maxInput, priceRangeResults, priceRangeDisplay, currencyFormat, defaultMinPrice, defaultMaxPrice);
    }
  });

  // Price range display function (unchanged)
  function updatePriceRangeDisplay(minInput, maxInput, priceRangeResults, priceRangeDisplay, currencyFormat, defaultMinPrice, defaultMaxPrice) {
    var minPrice = parseInt(minInput.val()) || defaultMinPrice;
    var maxPrice = parseInt(maxInput.val()) || defaultMaxPrice;
    var defaultMin = parseInt(defaultMinPrice);
    var defaultMax = parseInt(defaultMaxPrice);
    var displayMinPrice = (minPrice === defaultMin) ? "" : minPrice;
    var displayMaxPrice = (maxPrice === defaultMax) ? "" : maxPrice;

    var formattedMinPrice = displayMinPrice ? displayMinPrice.toLocaleString(currencyFormat.locale, {
        style: "currency",
        currency: currencyFormat.code,
        maximumFractionDigits: 0
    }) : "";

    var formattedMaxPrice = displayMaxPrice ? displayMaxPrice.toLocaleString(currencyFormat.locale, {
        style: "currency",
        currency: currencyFormat.code,
        maximumFractionDigits: 0
    }) : "";

    var minMaxPriceText = mlsTranslations.search_pricerange;
    if (!formattedMinPrice && !formattedMaxPrice) {
        minMaxPriceText = mlsTranslations.search_pricerange_select;
    } else if (!formattedMinPrice) {
        minMaxPriceText = mlsTranslations.search_pricerange_upto + formattedMaxPrice;
    } else if (!formattedMaxPrice) {
        minMaxPriceText = mlsTranslations.search_pricerange_startsfrom + formattedMinPrice;
    } else {
        minMaxPriceText = formattedMinPrice + mlsTranslations.search_pricerange_to + formattedMaxPrice;
    }

    priceRangeResults.val(minMaxPriceText);
    priceRangeDisplay.val(minMaxPriceText);
  }
});
// price field filter script ends

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
 
 jQuery('.mls-prj-sidebar').stickySidebar({
      topSpacing: stickyHeaderHeight,
      container: '.mls-prj-detail-full',
      sidebarInner: '.mls-prj-sidebar-inner',
      callback: function() {
//           console.log('Sticky sidebar fired!');
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
  var phonenumbercode = jQuery('.iti__selected-dial-code').text();
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

// jQuery(document).ready(function() {
//  var input = document.querySelector(".phone-field #phone");
//  window.intlTelInput(input,({
//   countrySearch:true,
//   initialCountry:"es",
//   separateDialCode: true
//  }));        
// });

jQuery(document).ready(function() {
  var input = document.querySelector(".phone-field #phone");
  if (input) {
    window.intlTelInput(input, {
      initialCountry: "es",
      separateDialCode: true,
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" // optional but recommended
    });
  } 
//   else {
//     console.error("Phone input field not found.");
//   }
});


jQuery(document).ready(function(jQuery) {
jQuery("ul.srh-tab-nav").each(function(){
      var countLi = jQuery(this).find("li").length;
      if(countLi == 4 ){
         jQuery(this).parents("form").find("ul.srh-tab-nav").addClass("c4");
       }
      if(countLi == 3 ){
         jQuery(this).parents("form").find("ul.srh-tab-nav").addClass("c3");
       }
      if(countLi == 2 ){
         jQuery(this).parents("form").find("ul.srh-tab-nav").addClass("c2");
       }
      if(countLi == 1 ){
         jQuery(this).parents("form").find("ul.srh-tab-nav").addClass("c1");
       }
  });
});

jQuery(window).on('load', function () {
  // Get the current day index
  const targetIndex = jQuery('.currentdaycls');

  // Focus on the slide if it exists
  if (targetIndex !== -1) {
      jQuery('.schedule-date-slider').slick('slickGoTo', targetIndex);
  }
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
jQuery('.date-field.dmy').addClass('updatedateloading');
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
jQuery('.date-field.dmy').removeClass('updatedateloading');
  // Find the index of the current date (element with class 'currentdaycls')
const currentDayElement = jQuery('.currentdaycls');

if (currentDayElement.length > 0) {
  // Get the `data-slick-index` value of the element
  const currentIndex = currentDayElement.closest('.slick-slide').data('slick-index');

  if (currentIndex !== undefined) {
      dateSlider.slick('slickGoTo', currentIndex); // Navigate to the correct slide
  } else {
//       console.log('The current date does not have a valid slick index.');
  }
} else {
//   console.log('No current day found in the loaded dates.');
}

    
      },
      error: function () {
          console.error('Failed to fetch dates');
    jQuery('.date-field.dmy').removeClass('updatedateloading');
      }
  });
}

jQuery(document).ready(function () {
  jQuery(".mls-dropdown").hide();

  jQuery(".price-range-iput-block").click(function (event) {
      jQuery(this).parents(".mls-form-group").find(".mls-dropdown").toggle(); // Toggle dropdown visibility
  jQuery(this).parents(".mls-form").find("ul.options").hide();
      event.stopPropagation(); // Prevent click from propagating to document
  });

  jQuery(document).click(function (event) {
      if (!jQuery(event.target).closest(".mls-dropdown, input.price-range-iput-block").length) {
          jQuery(".mls-dropdown").hide(); // Hide dropdown if click is outside
      }
  });
//  jQuery(".price-range-done").click(function (event) {
//       jQuery(this).closest(".mls-form-group").find(".mls-dropdown").hide();
//    event.stopPropagation();
//     });
  jQuery(".styledSelect").click(function () {
      jQuery(this).parents(".mls-form").find(".mls-dropdown").hide(); // Toggle dropdown visibility
  });


});

// Sort Order type form Scripts
document.addEventListener('DOMContentLoaded', function() {
  // Get all elements with the class 'order_search'
  var orderSearchElements = document.querySelectorAll('.order_search');
  
  // Loop through each 'order_search' element
  orderSearchElements.forEach(function(orderSearch) {
      var searchForm = document.querySelector('.mls-proplist-search-form');
      var searchFormSortType = searchForm.querySelector('.search_form_sorttype');
      var submitButton = searchForm.querySelector('input[name="mls_search_submit"]');

      if (orderSearch && searchFormSortType && searchForm && submitButton) {
          orderSearch.addEventListener('change', function() {
              // Update the hidden field in the search form with the selected value
              searchFormSortType.value = this.value;

              // Trigger the submit button click
              submitButton.click();
          });
      }
  });
});

jQuery(document).ready(function () {
jQuery(".mls-add-feat-btn").click(function () {
  // event.stopPropagation();
      jQuery(this).parents("form").find(".mls-search-features-dropdown").toggleClass("active");
      jQuery(this).parents("form").find(".mls-search-features-dropdown-bg").toggleClass("active");
      jQuery(this).parents("form").find(".mls-af-accordian").toggle();
      jQuery(this).parents("form").find(".options").hide();
      jQuery(this).parents("form").find(".mls-feat-top-search").show();
      jQuery(this).parents("body").toggleClass("mls-sfd-of");
  });
});

jQuery(document).ready(function(){
jQuery('#mls-navtabs1').scrollTabs();
});


///////////Accordian///////////////

/*jQuery.fn.ashCordian = function() {
var container = jQuery(this);
container.find('h2.mls-af-accodian-title').click(function() {
  if(jQuery(this).siblings('.mls-af-accodian-cnts').css('display') == 'block'){
     container.find('.mls-af-accodian-cnts').slideUp(150);
  } else {
    container.find('.mls-af-accodian-cnts').slideUp(150);
     jQuery(this).siblings('.mls-af-accodian-cnts').slideDown(150);
  }
});
};

jQuery('#mls-af-accord1').ashCordian();

jQuery(document).ready(function(){
  jQuery(".mls-af-accordian div:first-child .mls-af-accodian-title").addClass('active');
  jQuery(".mls-af-accordian div:first-child .mls-af-accodian-cnts").css('display','block');
  jQuery(".mls-af-accodian-title").click(function(){
      if(jQuery(this).hasClass("active"))
      {
          jQuery(this).removeClass("active");
      }
      else
      {
          jQuery(this).parents(".mls-af-accordian").find(".mls-af-accodian-title").removeClass("active");
          jQuery(this).toggleClass("active");
      }
  })
})*/

jQuery(document).ready(function(){
  jQuery(".mls-af-accodian-title").click(function(){
    var thiss = jQuery(this);
    var content = thiss.next(".mls-af-accodian-cnts");

    if(content.is(":visible")){
      content.slideUp();
      thiss.removeClass("active");
    } else {
      jQuery(".mls-af-accodian-cnts").slideUp();
      jQuery(".mls-af-accodian-title").removeClass("active");
      content.slideDown();
      thiss.addClass("active");
    }
  });

  // Open first
  jQuery(".mls-af-accodian-title").first().addClass("active").next(".mls-af-accodian-cnts").show();
});

jQuery(document).ready(function () {
// Function to handle close button clicks
function handleCloseButtonClick() {
    let label = jQuery(this).parent(); // Get the label
    let checkboxValue = label.attr("data-value"); // Get the unique identifier
    let parentContainer = label.closest(".mls-af-sel-wrap"); // Get the parent container

    label.remove(); // Remove the label
    parentContainer.find(`.mls-af-checkbox[value="${checkboxValue}"]`).prop("checked", false); // Uncheck the checkbox
}

// Bind close button functionality to initial labels
jQuery(".mls-af-selected-labels .mls-af-close-btn").on("click", handleCloseButtonClick);

// Handle checkbox changes
jQuery(document).on("change", ".mls-af-checkbox", function () {
    let parentContainer = jQuery(this).closest(".mls-af-sel-wrap"); // Get closest parent container
    let selectedLabelsContainer = parentContainer.find(".mls-af-selected-labels");

    // Clear only the label corresponding to the changed checkbox
    let checkboxValue = jQuery(this).val();
    selectedLabelsContainer.find(`[data-value="${checkboxValue}"]`).remove();

    // If the checkbox is checked, add its label
    if (jQuery(this).is(":checked")) {
        let labelText = jQuery(this).next("label").text();
        let label = jQuery("<span>")
            .addClass("mls-af-label-badge")
            .attr("data-value", checkboxValue) // Use a unique identifier
            .append(labelText);

        // Create Close Button
        let closeBtn = jQuery("<span>")
            .addClass("mls-af-close-btn")
            .attr("aria-label", "Remove " + labelText) // Accessibility
            .html("&times;")
            .on("click", handleCloseButtonClick); // Bind close button functionality

        label.append(closeBtn);
        selectedLabelsContainer.append(label);
    }
});
});

jQuery(document).ready(function () {
  // Add a click event listener to the "Done" button
  jQuery('input[name="mls_search_features_donebtn"]').click(function (event) {
      event.preventDefault(); // Prevent form submission

      // Get all selected checkboxes
      const selectedLabels = [];
      jQuery('.mls-af-checkbox:checked').each(function () {
          const label = jQuery('label[for="' + jQuery(this).attr('id') + '"]');
          if (label.length) {
              selectedLabels.push(label.text().trim());
          }
      });

      // Clear and update the selected features div
      const selectedFeaturesDiv = jQuery('.mls_selected_features');
      selectedFeaturesDiv.empty();

      if (selectedLabels.length > 0) {
          selectedLabels.forEach(label => {
              selectedFeaturesDiv.append(jQuery('<span>').text(label));
          });
      }
  
      jQuery(this).parents("form").find(".mls-search-features-dropdown").toggleClass("active");
      jQuery(this).parents("form").find(".mls-search-features-dropdown-bg").toggleClass("active");
      jQuery(this).parents("form").find(".mls-af-accordian").toggle();
      jQuery(this).parents("form").find(".options").hide();
      jQuery(this).parents("body").toggleClass("mls-sfd-of");
      jQuery(this).parents("form").find(".mls-feat-top-search").hide();
  
  });

// Reset Button
  jQuery('input[name="mls_search_features_resetbtn"]').click(function (event) {
      event.preventDefault();

      jQuery('.mls-af-checkbox').prop('checked', false);
      jQuery('.mls_selected_features').empty();
       jQuery('.mls-af-selected-labels').empty();
  });
});


jQuery(document).ready(function($) {
    'use strict';
	
    function MLSInitGroupedLocationSelection() {
        var $container = $('.mls-location-group-container');
        if ($container.length === 0) {
            return;
        }

        var groups = $container.data('groups');
        var selectedParent = $container.data('selected-parent');
        var selectedChildren = $container.data('selected-children');
        var $parentSelect = $('#mls_search_parent_area');
        var $childSelect = $('#mls_search_child_area');
        var $hiddenField = $('#mls_search_area_hidden');

        var groupsMap = {};
        $.each(groups, function(index, group) {
            groupsMap[group.parent] = group.children;
        });

        // Parent change
        $parentSelect.on('change', function() {
            var parentArea = $(this).val();

            $childSelect.find('option:not([value=""])').remove(); // clear children except placeholder
            $hiddenField.val('');

            if (parentArea && groupsMap[parentArea] && groupsMap[parentArea].length > 0) {
                // Add placeholder again
                $childSelect.append($('<option>', {
                    value: '',
                    text: '— All Areas —',
                    disabled: true
                }));

                $.each(groupsMap[parentArea], function(index, child) {
                    var isSelected = $.inArray(child, selectedChildren) !== -1;
                    $childSelect.append($('<option>', {
                        value: child,
                        text: child,
                        selected: isSelected
                    }));
                });
            }
			
			if (window.MLSSelectLocation) {
  window.MLSSelectLocation.init('.mls-multiselect');
//   console.log('MLSSelect initialized on .mls-multiselect');
}
            updateHiddenAreaField();
        });

        // Child change
        $childSelect.on('change', function() {
            updateHiddenAreaField();
        });

        // Update hidden field with parent + children
        function updateHiddenAreaField() {
            var parentValue = $parentSelect.val() || '';
            var childValues = $childSelect.val() || [];

            var values = [];

            if (parentValue) {
                if (childValues.length > 0) {
                    // ✅ Child selected → use children only
                    values = childValues;
                } else if (groupsMap[parentValue] && groupsMap[parentValue].length > 0) {
                    // ✅ Parent only → use all children
                    values = groupsMap[parentValue];
                } else {
                    // ✅ Parent with no children → use parent itself
                    values.push(parentValue);
                }
            }

            $hiddenField.val(values.join(','));
        }

        // Initial state
        function handleInitialState() {
            if (selectedParent) {
                $parentSelect.val(selectedParent).trigger('change');
            }

            if (selectedChildren.length > 0) {
                $childSelect.find('option').each(function() {
                    if ($.inArray($(this).val(), selectedChildren) !== -1) {
                        $(this).prop('selected', true);
                    }
                });
            }

            updateHiddenAreaField();
        }

        handleInitialState();
    }

    MLSInitGroupedLocationSelection();
	
	
});



