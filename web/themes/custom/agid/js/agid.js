/**
 * @file
 * A JavaScript file for the theme.
 */

(function ($, Drupal, window, document) {
  'use strict';

  jQuery(document).ready(function() {
    var body = $('body');

    jQuery(document).on('click', function (e) {
      if (!e.target.closest('.Megamenu-item')) {

        jQuery('.Megamenu .is-active').each(function (index, el) {
          $(el).removeClass('is-not-highlighted');
        });

        $('.Megamenu-item').each(function() {
          $(this).removeClass('menu-opened');
        });

        $('.main-overlay').hide();
      }
    });

    jQuery('.Megamenu-item > a').on('click', function () {

      var li = $(this).parent();

      if ( !li.children('.Megamenu-subnav').length ) {
        jQuery('.Megamenu .is-active').each(function (index, el) {
          $(el).removeClass('is-not-highlighted');
        });
        $('.Megamenu-item').each(function() {
          $(this).removeClass('menu-opened');
        });
        $('.main-overlay').hide();
        return;
      }

      if (li.hasClass('menu-opened')) {
        li.removeClass('menu-opened');
        $('.main-overlay').hide();

        jQuery('.Megamenu .is-active').each(function (index, el) {
          $(el).removeClass('is-not-highlighted');
        });
      } else {
        $('.Megamenu-item').each(function() {
          $(this).removeClass('menu-opened');
        });
        li.addClass('menu-opened');
        $('.main-overlay').show();

        if (!li.hasClass('is-active')) {
          jQuery('.Megamenu .is-active').each(function (index, el) {
            $(el).addClass('is-not-highlighted');
          });
        } else {
          jQuery('.Megamenu .is-active').each(function (index, el) {
            $(el).removeClass('is-not-highlighted');
          });
        }
      }
    });

    jQuery('.Megamenu-item').each(
      function (index, elem) {
        if (jQuery(elem).find('a').length == 1)
          return;
        var link_orig = jQuery(elem).find('a').first();
        var link_copy = jQuery('<a/>');
        if (window.location.pathname.indexOf(link_orig.attr('href')) == 0) {
          jQuery(elem).addClass('is-active');
        }
        link_copy.attr('href', link_orig.attr('href'));
        link_copy.html(link_orig.html());
        var div = jQuery(elem).find('div').first();
        var li = jQuery('<li/>');
        li.append(link_copy);
        var ul = jQuery('<ul/>', {class: 'Megamenu-subnavGroup Megamenu-pageLink'});
        ul.append(li);
        div.append(ul);

        jQuery(link_orig).click(function (e) {
          e.preventDefault();
        });

      }
    );
  });

  Drupal.behaviors.themeJS = {
    attach: function (context, settings) {
      if (typeof context['location'] !== 'undefined') {

        jQuery(document).ready(function() {

          jQuery('ul.sidebarnav-menu > li').each(function(index, elem) {
            var found = false;
            jQuery(elem).find('a').each(function(i,e) {
              if ( jQuery(e).hasClass('is-active')) {
                found = true;
              }
            });
            if ( !found) {
              jQuery(elem).attr('style', 'display: none !important');
            }
          });

          jQuery('ul.contextual-menu-mobile > li').each(function(index, elem) {
            var found = false;
            jQuery(elem).find('a').each(function(i,e) {
              if ( jQuery(e).hasClass('is-active')) {
                found = true;
              }
            });
            if ( !found) {
              jQuery(elem).attr('style', 'display: none !important');
            }
          });

          jQuery("#block-domandefrequenti-2 .Linklist li.hasFocus > a").children('span').remove();
          jQuery("#block-domandefrequenti-2 .Linklist a.is-active").children('span').remove();
          jQuery("#block-domandefrequenti-2 .Linklist a.is-active").wrap('<span class="sidebar-title"></span>').contents().unwrap();

          jQuery("#block-navigazioneprincipale .Linklist li.hasFocus > a").children('span').remove();
          jQuery("#block-navigazioneprincipale .Linklist a.is-active").children('span').remove();
          jQuery("#block-navigazioneprincipale .Linklist a.is-active").wrap('<span class="sidebar-title"></span>').contents().unwrap();

          jQuery('.accordion-open-all').on('click', function() {

            if (jQuery(this).hasClass('expanded')) {
              jQuery(this).removeClass('expanded');
              jQuery(this).html('Apri tutte');

              jQuery('.Accordion').find('.Accordion-header').each(function (index, elem) {
                jQuery(elem).attr('aria-expanded', false);
                jQuery(elem).attr('aria-selected', false);
              });
              jQuery('.Accordion').find('.Accordion-panel').each(function (index, elem) {
                jQuery(elem).attr('aria-hidden', true);
                jQuery(elem).css('height', '0');
              });
            } else {
              jQuery('.Accordion').find('.Accordion-header').each(function (index, elem) {
                jQuery(elem).attr('aria-expanded', true);
                jQuery(elem).attr('aria-selected', true);
              });
              jQuery('.Accordion').find('.Accordion-panel').each(function (index, elem) {
                jQuery(elem).attr('aria-hidden', false);
                jQuery(elem).css('height', 'auto');
              });
              jQuery(this).addClass('expanded');
              jQuery(this).html('Chiudi tutte');
            }
          });

          jQuery(".faq-menu-see-all > a").on('click', function() {
            jQuery(".faq-cell-hide").each(function(item, elem) {
              jQuery(elem).removeClass('faq-cell-hide');
            });
            jQuery(".faq-menu-see-all").hide();
          });


          if ( jQuery(".webform-submission-contact-form").length ) {

            $('.webform-submission-contact-form .form-item-ufficio select').on('change', function() {
              if (this.value != '') {
                jQuery(".webform-submission-contact-form .form-item-name").show();
                jQuery(".webform-submission-contact-form .form-item-email").show();
                jQuery(".webform-submission-contact-form .form-item-message .Form-label").show();
                jQuery(".webform-submission-contact-form .form-item-message textarea").prop('disabled', false);
                jQuery(".webform-submission-contact-form .form-submit").prop('disabled', false);
              } else {
                jQuery(".webform-submission-contact-form .form-item-name").hide();
                jQuery(".webform-submission-contact-form .form-item-email").hide();
                jQuery(".webform-submission-contact-form .form-item-message .Form-label").hide();
                jQuery(".webform-submission-contact-form .form-item-message textarea").prop('disabled', true);
                jQuery(".webform-submission-contact-form .form-submit").prop('disabled', true);
              }
            })

            $('.webform-submission-contact-form .form-item-ufficio select').trigger('change');
          }

        });

      }
    }
  };

})(jQuery, Drupal, this, this.document);