<?php

namespace Drupal\user_info_service\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user_info_service\Event\MyCustomEvent;

/**
 * Subscribes to the custom event.
 */
class MyCustomEventSubscriber implements EventSubscriberInterface {

  /**
   * Responds to the custom event.
   */
  public function onCustomEvent(MyCustomEvent $event) {
    \Drupal::messenger()->addMessage('Custom Event Triggered: ' . $event->getMessage());
  }

  /**
   * Registers the events to listen to.
   */
  public static function getSubscribedEvents() {
    return [
      MyCustomEvent::EVENT_NAME => 'onCustomEvent',
    ];
  }
}
    