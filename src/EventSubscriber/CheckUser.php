<?php

namespace Drupal\ezrole_tester\EventSubscriber;

use Drupal\Core\Entity\EntityFieldManager;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Url;

/**
 * Provides the subscriber for matching sessions.
 */
class CheckUser implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public function checkCurrentUser(GetResponseEvent $event) {
    if (isset($_SESSION['ezTestAccount']) && !empty($_SESSION['ezTestAccount'])) {
      \Drupal::currentUser()->setAccount($_SESSION['ezTestAccount']);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkCurrentUser'];
    return $events;
  }

}
