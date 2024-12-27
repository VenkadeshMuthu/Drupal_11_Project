<?php
namespace Drupal\my_drupal_tasks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;

class TaskController extends ControllerBase {

  /**
   * Displays a list of tasks with an "Add Task" button.
   */
  public function displayTasks() {
    $connection = Database::getConnection();
    $query = $connection->select('tasks', 't')
      ->fields('t')
      ->execute();

    $rows = [];
    $i = 0;

    // Loop through the result and prepare the rows for display
    foreach ($query as $record) {
      // Create edit and delete links
      $edit_url = Url::fromRoute('my_drupal_tasks.task_edit', ['id' => $record->id]);
      $edit_link = Link::fromTextAndUrl($this->t('Edit'), $edit_url)->toRenderable();
      $edit_link['#attributes'] = ['class' => ['btn', 'btn-success', 'btn-sm']];

      $delete_url = Url::fromRoute('my_drupal_tasks.task_delete', ['id' => $record->id]);
      $delete_link = Link::fromTextAndUrl($this->t('Delete'), $delete_url)->toRenderable();
      $delete_link['#attributes'] = [
        'class' => ['btn', 'btn-danger', 'btn-sm'],
        'onclick' => 'return confirm("Are you sure you want to delete this task?");',
        ];


      // Add row data
      $rows[] = [
        ++$i,
        $record->task_name,
        $record->description,
        $record->task_date,
        $record->status,
        [
          'data' => $edit_link,
          'class' => ['text-center'],
        ],
        [
          'data' => $delete_link,
          'class' => ['text-center'],
        ],
      ];
    }

    // Define table headers
    $header = [
      'S.No',
      'Task Name',
      'Description',
      'Date',
      'Status',
      [
        'data' => $this->t('Operations'),
        'colspan' => 2,
        'class' => ['text-center'],
      ],
    ];

    $build['add_task'] = [
        '#markup' => '<a href="create_task" class="btn-add-task">Add Task</a>',
      ];
    // Add the table
    $build['tasks_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No tasks found.'),
    ];

    return $build;
  }
  public function deleteTask($id) {
    // Get the database connection.
    $connection = Database::getConnection();

    // Delete the task with the given ID.
    $connection->delete('tasks')
      ->condition('id', $id)
      ->execute();

    // Add a success message.
    $this->messenger()->addMessage($this->t('Task deleted successfully.'));

    // Redirect to the task list page.
    return new RedirectResponse('/admin/tasks');
  }
}
