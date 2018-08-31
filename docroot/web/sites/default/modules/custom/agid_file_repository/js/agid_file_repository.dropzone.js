/**
 * @file agid_file_repository.dropzone.js
 *
 * Reacts on DropzoneJs events.
 */

(function ($, Drupal, drupalSettings, Dropzone) {

  'use strict';

  /**
   * Agid file repository namespace definition.
   *
   * @type {{}}
   */
  Drupal.agid_file_repository = Drupal.agid_file_repository || {};

  /**
   * DropzonJs namespace definition.
   *
   * @type {{}}
   */
  Drupal.agid_file_repository.dropzone = Drupal.agid_file_repository.dropzone || {};

  /**
   * DropzoneJs instance.
   *
   * @type {any}
   */
  Drupal.agid_file_repository.dropzone.instance = Drupal.agid_file_repository.dropzone.instance || {};

  /**
   * An array containing exceeded files names.
   *
   * @type {Array}
   */
  Drupal.agid_file_repository.dropzone.exceededFiles = [];

  /**
   * Boolean indicating if the max files exceeded timeout has been set.
   *
   * @type {boolean}
   */
  Drupal.agid_file_repository.dropzone.maxfilesexceeded = false;

  /**
   * Reacts on "maxfilesexceeded" event.
   *
   * @type {{attach: Drupal.behaviors.agidFileRepositoryDropzonejs.attach}}
   */
  Drupal.behaviors.agidFileRepositoryDropzonejs = {
    attach: function (context) {
      Drupal.agid_file_repository.dropzone.instance = Dropzone.instances[0];
      Drupal.agid_file_repository.dropzone.instance.on('maxfilesexceeded', function (file) {
        Drupal.agid_file_repository.dropzone.exceededFiles.push(file.name);

        if (!Drupal.agid_file_repository.dropzone.maxfilesexceeded) {
          Drupal.agid_file_repository.dropzone.maxfilesexceeded = true;
          setTimeout(function () {
            Drupal.agid_file_repository.dropzone.showExceededFiles();
          }, 1000);
        }
      });
    }
  };

  /**
   * Show exceeded files error message.
   */
  Drupal.agid_file_repository.dropzone.showExceededFiles = function () {
    var message = Drupal.t('You can upload only @max files. The following files have been rejected: @files', {
      '@max': Drupal.agid_file_repository.dropzone.instance.options.maxFiles,
      '@files': Drupal.agid_file_repository.dropzone.exceededFiles.join(', ')
    });
    Drupal.agid_file_repository.dropzone.exceededFiles = [];
    Drupal.agid_file_repository.dropzone.maxfilesexceeded = false;
    alert(message);
  }

}(jQuery, Drupal, drupalSettings, Dropzone));
