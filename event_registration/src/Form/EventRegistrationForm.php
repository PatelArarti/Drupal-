<?php

namespace Drupal\event_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Mail\MailManagerInterface; 
use Drupal\Core\TempStore\PrivateTempStoreFactory;


/**
 * Form to register for an event.
 */
class EventRegistrationForm extends FormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  protected $mailManager;
  protected $tempStoreFactory;

  /**
   * Constructs a new EventRegistrationForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */

  public function __construct(EntityTypeManagerInterface $entity_type_manager, MailManagerInterface $mail_manager, PrivateTempStoreFactory $tempStoreFactory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->mailManager = $mail_manager;
    $this->tempStoreFactory = $tempStoreFactory;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.mail'),
      $container->get('tempstore.private')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $event_options = $this->getEventOptions();
    // Add Full Name field.
    $form['full_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Full Name'),
        '#required' => TRUE,
        '#name' => 'full_name', // Add unique name attribute.
    ];
    
    // Add Email Address field.
    $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('Email Address'),
        '#required' => TRUE,
        '#name' => 'email', // Add unique name attribute.
    ];
    
    // Add Event Selection dropdown.
    $form['event_selection'] = [
        '#type' => 'select',
        '#title' => $this->t('Event Selection'),
        '#options' => $event_options,
        '#required' => TRUE,
        '#name' => 'event_selection', // Add unique name attribute.
    ];

    // Add submit button.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }
  /**
   * Retrieves the list of available events.
   *
   * @return array
   *   An array of event options.
   */
  protected function getEventOptions() {
    $events = $this->entityTypeManager->getStorage('event')->loadMultiple();
    $event_options = [];
    foreach ($events as $event) {
      $event_options[$event->id()] = $event->label();
    }
    return $event_options;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Perform validation for maximum attendees here.
    // For demonstration purposes, assume the maximum attendees is 5.
    if ($form_state->getValue('event_selection') == 'Event 1') {
      $registered_attendees_count = 3; // Example: Get the count of already registered attendees.
      if ($registered_attendees_count >= 5) {
        $form_state->setErrorByName('event_selection', $this->t('The selected event is full.'));
      }
    }
  }

/**
 * {@inheritdoc}
 */
public function submitForm(array &$form, FormStateInterface $form_state) {
  // Get the submitted form values.
  $values = $form_state->getValues();

  // Get the temporary store for event registration form values.
  $store = $this->tempStoreFactory->get('event_registration');

  // Get existing form submissions from the temporary store.
  $form_values = $store->get('form_values') ?: [];

  // Append the current form submission to the existing form submissions.
  $form_values[] = $values;

  // Store the updated form submissions in the temporary store.
  $store->set('form_values', $form_values);

  // Send confirmation email.
  $this->sendConfirmationEmail($values);

  // Redirect to the confirmation page.
  $form_state->setRedirect('event_registration.confirmation');
}


  /**
   * Sends a confirmation email upon successful event registration.
   *
   * @param array $values
   *   The submitted form values.
   */
  protected function sendConfirmationEmail(array $values) {
    // Prepare email content.
    $subject = t('Event Registration Confirmation');
    $body = t('Thank you for registering for the event.');
    // Add more details from the form values as needed.

    // Send email using MailManager service.
    $to = $values['email'];
    $params = [
      'subject' => $subject,
      'message' => $body,
    ];
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;
    $result = $this->mailManager->mail('event_registration', 'registration_confirmation', $to, $langcode, $params, NULL, $send);
    return $result['result'];
  }


}
