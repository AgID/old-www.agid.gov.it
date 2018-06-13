(function ($, Modernizr, Drupal) {
  Drupal.behaviors.webformMultiStepFix = {
    attach: function (context, settings) {
      if (typeof window.Modernizr !== "undefined") {
        if (typeof Modernizr.inputtypes === "undefined") {
          Modernizr.inputtypes = {
            date: false
          };
        }
      }
    }
  }
})(jQuery, Modernizr, Drupal);
