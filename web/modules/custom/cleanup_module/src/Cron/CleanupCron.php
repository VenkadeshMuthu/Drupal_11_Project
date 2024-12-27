<?php

namespace Drupal\cleanup_module\Cron;

use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Defines a cron task to clean expired data.
 */
class CleanupCron {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructs a CleanupCron object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   */
  public function __construct(Connection $database, LoggerChannelFactoryInterface $logger_factory) {
    $this->database = $database;
    $this->logger = $logger_factory->get('cleanup_module');
  }

  /**
   * Executes the cron task.
   */
  public function execute() {
    // Define the threshold timestamp (e.g., 30 days ago).
    $threshold = strtotime('-30 days');

    // Delete expired rows from the custom table.
    $rows_deleted = $this->database->delete('custom_table')
      ->condition('created', $threshold, '<')
      ->execute();

    // Log the number of rows deleted.
    $this->logger->notice('Deleted @count expired records from the custom table.', ['@count' => $rows_deleted]);
  }
}
