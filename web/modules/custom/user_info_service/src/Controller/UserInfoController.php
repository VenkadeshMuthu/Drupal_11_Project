<?php

namespace Drupal\user_info_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user_info_service\Service\UserInfoService;

/**
 * Class UserInfoController.
 *
 * Demonstrates the use of the UserInfoService.
 */
class UserInfoController extends ControllerBase {

  /**
   * The user info service.
   *
   * @var \Drupal\user_info_service\Service\UserInfoService
   */
  protected $userInfoService;

  /**
   * Constructs a UserInfoController object.
   *
   * @param \Drupal\user_info_service\Service\UserInfoService $user_info_service
   *   The user info service.
   */
  public function __construct(UserInfoService $user_info_service) {
    $this->userInfoService = $user_info_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user_info_service.user_info')
    );
  }

  /**
   * Returns the current user's information.
   *
   * @return array
   *   A renderable array with the user's name and email.
   */
  public function displayUserInfo() {
    $username = $this->userInfoService->getCurrentUserName();
    $email = $this->userInfoService->getCurrentUserEmail();

    return [
      '#markup' => $this->t('Hello @name! Your email is @mail.', [
        '@name' => $username,
        '@mail' => $email,
      ]),
    ];
  }
}
