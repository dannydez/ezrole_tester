<?php

namespace Drupal\ezrole_tester\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Access\AccessResult;

/**
 * Class AdyenTest.
 */
class TestAccount extends ControllerBase {

  /**
   * Provides the queued collecting mechanism.
   */
  public function set() {
    $account_name = \Drupal::request()->get('account');
    $destination = \Drupal::request()->get('destination');
    $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties([
      'name' => $account_name,
    ]);

    if (!empty($users)) {
      $user = reset($users);

      $this->userLogout($user);
    }
    else {
      drupal_set_message('Something went wrong', 'notice');
    }

    $response = new RedirectResponse($destination);
    return $response->send();
  }

  /**
   * Logs the current user out.
   */
  private function userLogout($new_user) {
    $user = \Drupal::currentUser();

    \Drupal::logger('user')->notice('Session closed for %name.', ['%name' => $user->getAccountName()]);

    \Drupal::moduleHandler()->invokeAll('user_logout', [$user]);

    \Drupal::service('session_manager')->destroy();
    $url = user_pass_reset_url($new_user);

    $response = new RedirectResponse($url . '/login?destination=' . \Drupal::request()->get('destination'));
    return $response->send();
  }

  /**
   * Custom access function.
   */
  public function access() {
    $access = FALSE;

    $roles = user_roles();

    foreach ($roles as $role => $row) {
      if (\Drupal::currentUser()->getUsername() == 'ez-' . $role) {
        $access = TRUE;
      }
    }

    if (\Drupal::currentUser()->id() == 1 || $access == TRUE) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }

}
