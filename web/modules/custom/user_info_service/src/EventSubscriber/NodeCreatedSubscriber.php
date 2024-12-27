<?php

namespace Drupal\user_info_service\EventSubscriber;

use Drupal\user_info_service\Event\NodeCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Event subscriber for node created events.
 */
class NodeCreatedSubscriber implements EventSubscriberInterface {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * React to the node created event.
   *
   * @param \Drupal\user_info_service\Event\NodeCreatedEvent $event
   *   The custom event.
   */
  public function onNodeCreated(NodeCreatedEvent $event) {
    $node = $event->getNode();
    $this->messenger->addStatus(t('A new node "@title" has been created.', [
      '@title' => $node->getTitle(),
    ]));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      NodeCreatedEvent::EVENT_NAME => 'onNodeCreated',
    ];
  }
}
