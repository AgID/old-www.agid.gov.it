// Add Modernizr.inputtypes because needed by Webform module to work properly
// as Italiagov theme use a custom version of Modernizr
// See: https://www.drupal.org/project/webform/issues/2905479
(function ($, Modernizr, Drupal) {
  Drupal.behaviors.webformMultiStepFix = {
    attach: function (context, settings) {
      if (typeof window.Modernizr !== "undefined") {
        if (typeof Modernizr.inputtypes === "undefined") {
          // Taken from https://github.com/Modernizr/Modernizr/blob/master/feature-detects/inputtypes.js
          var docElement = document.documentElement;
          var inputElem = document.createElement('input');
          var inputtypes = 'search tel url email datetime date month week time datetime-local number range color'.split(' ');
          var inputs = {};

          Modernizr.inputtypes = (function(props) {
            var len = props.length;
            var smile = '1)';
            var inputElemType;
            var defaultView;
            var bool;

            for (var i = 0; i < len; i++) {

              inputElem.setAttribute('type', inputElemType = props[i]);
              bool = inputElem.type !== 'text' && 'style' in inputElem;

              // We first check to see if the type we give it sticks..
              // If the type does, we feed it a textual value, which shouldn't be valid.
              // If the value doesn't stick, we know there's input sanitization which infers a custom UI
              if (bool) {

                inputElem.value         = smile;
                inputElem.style.cssText = 'position:absolute;visibility:hidden;';

                if (/^range$/.test(inputElemType) && inputElem.style.WebkitAppearance !== undefined) {

                  docElement.appendChild(inputElem);
                  defaultView = document.defaultView;

                  // Safari 2-4 allows the smiley as a value, despite making a slider
                  bool =  defaultView.getComputedStyle &&
                    defaultView.getComputedStyle(inputElem, null).WebkitAppearance !== 'textfield' &&
                    // Mobile android web browser has false positive, so must
                    // check the height to see if the widget is actually there.
                    (inputElem.offsetHeight !== 0);

                  docElement.removeChild(inputElem);

                } else if (/^(search|tel)$/.test(inputElemType)) {
                  // Spec doesn't define any special parsing or detectable UI
                  //   behaviors so we pass these through as true

                  // Interestingly, opera fails the earlier test, so it doesn't
                  //  even make it here.

                } else if (/^(url|email)$/.test(inputElemType)) {
                  // Real url and email support comes with prebaked validation.
                  bool = inputElem.checkValidity && inputElem.checkValidity() === false;

                } else {
                  // If the upgraded input component rejects the :) text, we got a winner
                  bool = inputElem.value !== smile;
                }
              }

              inputs[ props[i] ] = !!bool;
            }
            return inputs;
          })(inputtypes);
        }
      }
    }
  }
})(jQuery, Modernizr, Drupal);
