(function ($, D) {
  D.behaviors.sitemapCollapse = {
    attach: function (context, settings) {
      // Get expandable elements
      var $expanded = $('.sitemap-menu');

      // Handle click - collapse and visit link
      $('.sitemap-menu li').not('.leaf').click(function (e) {
        $(this).children('ul').toggle();
        $(this).toggleClass('clicked');
        e.stopPropagation();
      });
    }
  }
})(jQuery, Drupal);