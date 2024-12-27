<?php

namespace Drupal\user_info_service\Service;

use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class UserInfoService.
 *
 * Provides methods to get the current user's name and email.
 */
class UserInfoService {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new UserInfoService object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user service.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Gets the current user's name.
   *
   * @return string
   *   The current user's username.
   */
  public function getCurrentUserName(): string {
    return $this->currentUser->getAccountName();
  }

  /**
   * Gets the current user's email.
   *
   * @return string
   *   The current user's email address.
   */
  public function getCurrentUserEmail(): string {
    return $this->currentUser->getEmail();
  }
}
