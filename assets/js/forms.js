(function ($) {
  "use strict";

  $(document).ready(function () {
    // Registration Form Validation
    $(".registration-form").on("submit", function (e) {
      var isValid = true;
      var form = $(this);

      // Remove previous error classes
      form.find(".form-group").removeClass("error");
      form.find(".error-message").remove();

      // Validate required fields
      form.find("[required]").each(function () {
        var field = $(this);
        var value = field.val().trim();

        if (!value) {
          showFieldError(field, "This field is required");
          isValid = false;
        }
      });

      // Validate email format
      var email = form.find('input[type="email"]');
      if (email.length && email.val()) {
        if (!isValidEmail(email.val())) {
          showFieldError(email, "Please enter a valid email address");
          isValid = false;
        }
      }

      // Validate phone numbers
      var phone = form.find('input[name="phone"]');
      if (phone.length && phone.val()) {
        if (!isValidPhone(phone.val())) {
          showFieldError(phone, "Please enter a valid phone number");
          isValid = false;
        }
      }

      // If form is not valid, prevent submission
      if (!isValid) {
        e.preventDefault();

        // Scroll to first error
        var firstError = form.find(".form-group.error").first();
        if (firstError.length) {
          $("html, body").animate(
            {
              scrollTop: firstError.offset().top - 100,
            },
            500
          );
        }

        return false;
      }

      // Show loading state
      var submitBtn = form.find('button[type="submit"]');
      submitBtn.prop("disabled", true).text("Submitting...");
    });

    // Real-time validation
    $(
      ".registration-form input, .registration-form select, .registration-form textarea"
    ).on("blur", function () {
      validateField($(this));
    });

    // Phone number formatting
    $('input[type="tel"]').on("input", function () {
      var value = $(this)
        .val()
        .replace(/[^\d+]/g, "");
      $(this).val(value);
    });

    // Auto-populate WhatsApp from phone if empty
    $('input[name="phone"]').on("blur", function () {
      var whatsapp = $('input[name="whatsapp"]');
      if (!whatsapp.val() && $(this).val()) {
        whatsapp.val($(this).val());
      }
    });
  });

  // Helper Functions
  function showFieldError(field, message) {
    var formGroup = field.closest(".form-group");
    formGroup.addClass("error");

    if (!formGroup.find(".error-message").length) {
      formGroup.append('<span class="error-message">' + message + "</span>");
    }
  }

  function validateField(field) {
    var formGroup = field.closest(".form-group");
    var value = field.val().trim();

    // Remove existing errors
    formGroup.removeClass("error");
    formGroup.find(".error-message").remove();

    // Check if required field is empty
    if (field.prop("required") && !value) {
      showFieldError(field, "This field is required");
      return false;
    }

    // Validate email
    if (field.attr("type") === "email" && value && !isValidEmail(value)) {
      showFieldError(field, "Please enter a valid email address");
      return false;
    }

    // Validate phone
    if (field.attr("name") === "phone" && value && !isValidPhone(value)) {
      showFieldError(field, "Please enter a valid phone number");
      return false;
    }

    return true;
  }

  function isValidEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function isValidPhone(phone) {
    // Egyptian phone number validation (basic)
    var regex = /^(\+20|0)?1[0-2,5]\d{8}$/;
    return regex.test(phone.replace(/[\s-]/g, ""));
  }
})(jQuery);
