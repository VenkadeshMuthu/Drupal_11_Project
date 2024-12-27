<?php

namespace Drupal\user_info_service\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\user_info_service\Event\MyCustomEvent;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller to trigger custom event.
 */
class MyCustomEventController extends ControllerBase {

  protected $eventDispatcher;

  public function __construct(EventDispatcherInterface $event_dispatcher) {
    $this->eventDispatcher = $event_dispatcher;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher')
    );
  }

  /**
   * Dispatches the custom event.
   */
  public function triggerEvent() {
    $message = 'Hello from MyCustomEvent!';
    $event = new MyCustomEvent($message);
    $this->eventDispatcher->dispatch($event, MyCustomEvent::EVENT_NAME);

    return [
      '#markup' => $this->t('Custom event has been triggered.'),
    ];
  }
}
