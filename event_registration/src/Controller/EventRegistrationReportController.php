<?php

namespace Drupal\event_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventRegistrationReportController extends ControllerBase {

  protected $tempStoreFactory;

  public function __construct(PrivateTempStoreFactory $tempStoreFactory) {
    $this->tempStoreFactory = $tempStoreFactory;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private')
    );
  }

  public function content() {
    // Get the temporary store for event registration form values.
    $store = $this->tempStoreFactory->get('event_registration');

    // Get form values from the temporary store.
    $form_values = $store->get('form_values') ?: [];

    // Pass form values to the confirmation page.
    return [
      '#theme' => 'event_registration_report',
      '#registrations' => $form_values,
    ];
  }
  

}
