<?php

namespace Drupal\user_info_service\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Custom event triggered when a node is created.
 */
class NodeCreatedEvent extends Event {

  // Define the event name as a constant.
  public const EVENT_NAME = 'mymodule.node_created';

  /**
   * The node that was created.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * Constructor.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   */
  public function __construct($node) {
    $this->node = $node;
  }

  /**
   * Get the created node.
   *
   * @return \Drupal\node\NodeInterface
   *   The node entity.
   */
  public function getNode() {
    return $this->node;
  }
}
