var telInput = $("#phone"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({
  defaultCountry: "IN",
  autoPlaceholder: false,
  // customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
  //   return "e.g. " + selectedCountryPlaceholder;
  // },
  geoIpLookup: function(callback) {
    $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });
  }
  ,
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});

// on blur: validate
telInput.blur(function() {
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
      validMsg.addClass("hide");
    }
  }
});

// on keydown: reset
telInput.keydown(function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
});
