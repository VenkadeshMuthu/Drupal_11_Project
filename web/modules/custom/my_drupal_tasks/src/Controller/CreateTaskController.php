<?php

namespace Drupal\my_drupal_tasks\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Controller for creating a new task.
 */
class CreateTaskController extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'my_drupal_task_create';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['task_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Task Name'),
      '#required' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
    ];

    // $form['image'] = [
    //   '#type' => 'file',
    //   '#title' => $this->t('Screenshot'),
    //   '#required' => TRUE,
    //   '#upload_validators' => [
    //     'file_validate_extensions' => ['png', 'jpg', 'jpeg'],
    //     'file_validate_size' => [25600000], // Max size: 25 MB.
    //   ],
    // ];

    $form['task_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Task Date'),
      '#required' => TRUE,
    ];

    $form['status'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Status'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Task'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the database connection.
    $connection = Database::getConnection();

    // // Handle file upload.
    // $file = file_save_upload('image', [
    //   'file_validate_extensions' => ['png', 'jpg', 'jpeg'],
    //   'file_validate_size' => [25600000], // Max size: 25 MB.
    // ]);

    // // Initialize the image path.
    // $image_path = NULL;

    // if ($file) {
    //   // Ensure the file is valid.
    //   if ($file instanceof \Drupal\file\FileInterface) {
    //     // Make the file permanent so it can be used on the site.
    //     $file->setPermanent();
    //     $file->save();
    //     $image_path = $file->getFileUri();
    //   }
    //   else {
    //     // Add a message if the file is invalid.
    //     $this->messenger()->addError($this->t('The uploaded file is not valid.'));
    //   }
    // }
    // else {
    //   // Add a message if no file was uploaded.
    //   $this->messenger()->addError($this->t('No file was uploaded.'));
    // }

    // Insert data into the tasks table.
    $connection->insert('tasks')
      ->fields([
        'task_name' => $form_state->getValue('task_name'),
        'description' => $form_state->getValue('description'),
        'task_date' => $form_state->getValue('task_date'),
        'status' => $form_state->getValue('status'),
        // 'image' => $image_path, // Store the image URI or NULL if no file.
      ])
      ->execute();

    // Display a success message.
    $this->messenger()->addMessage($this->t('Task has been saved successfully.'));
    $form_state->setRedirect('my_drupal_tasks.display_tasks');
  }

}
