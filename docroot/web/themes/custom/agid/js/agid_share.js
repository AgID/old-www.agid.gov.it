function shareOpen() {}
function shareClose() {}

(function ($, D) {
  D.behaviors.agidShare = {
    attach: function (context, settings) {
      var $share_trigger      = $('.agid-share__trigger');
      var $share_trigger_icon = $('.agid-share__trigger__icon--hover, .agid-share__trigger__text');
      var $share_trigger_text = $('.agid-share__trigger__text');
      var $share_links_container = $('.agid-share__links-container');

      // Handle icon click
      $share_trigger_icon.click(function (evt) {
        $share_trigger_icon.toggleClass('Icon-close');
        $share_trigger.toggleClass('active');
        $share_trigger_text.toggle();
        $share_links_container.toggle();
      })
    }
  };
})(jQuery, Drupal);
