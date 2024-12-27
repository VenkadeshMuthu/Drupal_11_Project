<?php
namespace Drupal\my_drupal_tasks\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class EditTaskForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'edit_task_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $connection = Database::getConnection();
    $task = $connection->select('tasks', 't')
      ->fields('t', ['task_name', 'description', 'task_date', 'status'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$task) {
      $this->messenger()->addError($this->t('Task not found.'));
      return [];
    }

    $form['task_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Task Name'),
      '#default_value' => $task['task_name'],
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $task['description'],
      '#required' => TRUE,
    ];

    $form['task_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Task Date'),
      '#default_value' => $task['task_date'],
      '#required' => TRUE,
    ];

    $form['status'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status'),
      '#default_value' => $task['status'],
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $id,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update Task'),
    ];

    $form['actions']['cancel'] = [
        '#type' => 'submit',
        '#value' => $this->t('Cancel'),
        '#submit' => ['::cancelForm'],
      ];
  

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $connection->update('tasks')
      ->fields([
        'task_name' => $form_state->getValue('task_name'),
        'description' => $form_state->getValue('description'),
        'task_date' => $form_state->getValue('task_date'),
        'status' => $form_state->getValue('status'),
      ])
      ->condition('id', $form_state->getValue('id'))
      ->execute();

    $this->messenger()->addMessage($this->t('Task updated successfully.'));
    $form_state->setRedirect('my_drupal_tasks.display_tasks');
  }
}
