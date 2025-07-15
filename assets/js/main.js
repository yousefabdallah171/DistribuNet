(function ($) {
  "use strict";

  $(document).ready(function () {
    // Mobile Navigation Toggle
    $(".mobile-menu-toggle").on("click", function () {
      $(".main-navigation").toggleClass("active");
    });

    // Smooth Scrolling for Internal Links
    $('a[href^="#"]').on("click", function (e) {
      var target = $(this.getAttribute("href"));
      if (target.length) {
        e.preventDefault();
        $("html, body")
          .stop()
          .animate(
            {
              scrollTop: target.offset().top - 100,
            },
            1000
          );
      }
    });

    // Archive Filter Auto-submit
    $(".filter-form select").on("change", function () {
      $(this).closest("form").submit();
    });

    // Contact Button Interactions
    $(".contact-btn").on("click", function () {
      $(this).addClass("clicked");
      setTimeout(() => {
        $(this).removeClass("clicked");
      }, 300);
    });

    // Card Hover Effects
    $(".distributor-card").hover(
      function () {
        $(this).addClass("hovered");
      },
      function () {
        $(this).removeClass("hovered");
      }
    );

    // Back to Top Button
    if ($(".back-to-top").length) {
      $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
          $(".back-to-top").fadeIn();
        } else {
          $(".back-to-top").fadeOut();
        }
      });

      $(".back-to-top").on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, 800);
        return false;
      });
    }

    // Initialize any tooltips or popovers
    if (typeof $.fn.tooltip !== "undefined") {
      $('[data-toggle="tooltip"]').tooltip();
    }

    // Search form enhancements
    $('.search-form input[type="search"]')
      .on("focus", function () {
        $(this).parent().addClass("focused");
      })
      .on("blur", function () {
        $(this).parent().removeClass("focused");
      });

    // === PXL Header Interactivity ===
    // Burger menu open/close
    $(".pxl-header__burger").on("click", function () {
      $(".pxl-burger-menu").addClass("open");
      $("body").addClass("pxl-menu-open");
      $(".pxl-burger-menu__close").focus();
    });
    $(".pxl-burger-menu__close").on("click", function () {
      $(".pxl-burger-menu").removeClass("open");
      $("body").removeClass("pxl-menu-open");
      $(".pxl-header__burger").focus();
    });
    // Close menu on outside click
    $(document).on("mousedown touchstart", function (e) {
      if (
        $(".pxl-burger-menu").hasClass("open") &&
        !$(e.target).closest(".pxl-burger-menu, .pxl-header__burger").length
      ) {
        $(".pxl-burger-menu").removeClass("open");
        $("body").removeClass("pxl-menu-open");
      }
    });
    // Search overlay open/close
    $(".pxl-header__search").on("click", function () {
      $(".pxl-search-overlay").addClass("open");
      $("body").addClass("pxl-search-open");
      $(".pxl-search-overlay__close").focus();
    });
    $(".pxl-search-overlay__close").on("click", function () {
      $(".pxl-search-overlay").removeClass("open");
      $("body").removeClass("pxl-search-open");
      $(".pxl-header__search").focus();
    });
    // Close overlay on outside click
    $(document).on("mousedown touchstart", function (e) {
      if (
        $(".pxl-search-overlay").hasClass("open") &&
        !$(e.target).closest(".pxl-search-overlay__content, .pxl-header__search").length
      ) {
        $(".pxl-search-overlay").removeClass("open");
        $("body").removeClass("pxl-search-open");
      }
    });
    // ESC key closes menu/overlay
    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        if ($(".pxl-burger-menu").hasClass("open")) {
          $(".pxl-burger-menu").removeClass("open");
          $("body").removeClass("pxl-menu-open");
          $(".pxl-header__burger").focus();
        }
        if ($(".pxl-search-overlay").hasClass("open")) {
          $(".pxl-search-overlay").removeClass("open");
          $("body").removeClass("pxl-search-open");
          $(".pxl-header__search").focus();
        }
      }
    });
    // Trap focus inside open overlays for accessibility
    function trapFocus(container) {
      var focusable = container.find(
        'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
      ).filter(":visible");
      if (!focusable.length) return;
      var first = focusable.first();
      var last = focusable.last();
      container.on("keydown", function (e) {
        if (e.key === "Tab") {
          if (e.shiftKey) {
            if (document.activeElement === first[0]) {
              last[0].focus();
              e.preventDefault();
            }
          } else {
            if (document.activeElement === last[0]) {
              first[0].focus();
              e.preventDefault();
            }
          }
        }
      });
    }
    trapFocus($(".pxl-burger-menu"));
    trapFocus($(".pxl-search-overlay"));
  });
})(jQuery);
