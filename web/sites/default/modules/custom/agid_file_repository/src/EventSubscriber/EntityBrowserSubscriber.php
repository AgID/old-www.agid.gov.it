<?php

namespace Drupal\agid_file_repository\EventSubscriber;

use Drupal\entity_browser\Events\RegisterJSCallbacks;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityBrowserSubscriber.
 *
 * @package Drupal\agid_file_repository
 */
class EntityBrowserSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return ['entity_browser.register_js_callbacks' => 'registerJsCallbacks'];
  }

  /**
   * Register JS Callbacks.
   *
   * @param \Drupal\entity_browser\Events\RegisterJSCallbacks $event
   *   The subscribed event used to register javascript callbacks.
   */
  public function registerJsCallbacks(RegisterJSCallbacks $event) {
    $event->registerCallback('Drupal.agid_file_repository.selectionCompleted');
  }

}
