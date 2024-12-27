<?php

namespace Drupal\user_info_service\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Defines the custom event.
 */
class MyCustomEvent extends Event {
  const EVENT_NAME = 'user_info_service.custom_event';

  protected $message;

  public function __construct($message) {
    $this->message = $message;
  }

  public function getMessage() {
    return $this->message;
  }
}
