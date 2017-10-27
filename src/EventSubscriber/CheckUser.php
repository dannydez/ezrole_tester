<?php

namespace Drupal\ezrole_tester\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
