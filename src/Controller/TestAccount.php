<?php

namespace Drupal\ezrole_tester\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    $original = \Drupal::request()->get('original');
    $role = \Drupal::request()->get('role');

    $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties([
      'name' => $account_name,
    ]);

    if ($account_name == 'ez-anonymous') {
      $users = [\Drupal::entityTypeManager()->getStorage('user')->load(0)];
    }

    if (!empty($users)) {
      if ($original == TRUE) {
        unset($_SESSION['ezCurrentAccount']);
        unset($_SESSION['ezTestAccount']);
        $user = reset($users);
        drupal_set_message(t('Succesfully switched to %user', ['%user' => $user->label()]));
      }
      else {
        if (!isset($_SESSION['ezCurrentAccount'])) {
          $_SESSION['ezCurrentAccount'] = \Drupal::currentUser()->id();
        }

        $user = reset($users);
        $_SESSION['ezTestAccount'] = $user;
        drupal_set_message(t('Succesfully switched to %role', ['%role' => $role]));
      }
    }
    else {
      drupal_set_message('Something went wrong', 'notice');
    }

    $response = new RedirectResponse($destination);
    return $response->send();
  }

}
