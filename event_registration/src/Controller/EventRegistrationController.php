<?php

namespace Drupal\event_registration\Controller;

use Drupal\Core\Controller\ControllerBase;

class EventRegistrationController extends ControllerBase {

  public function confirmation() {
    // Build and return the confirmation page.
    return [
      '#markup' => $this->t('Thank you for registering for the event.'),
    ];
  }

}