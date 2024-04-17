# Event Registration Module

## Description
The Event Registration module allows users to register for events by providing their full name, email address, and selecting the event they want to attend.

## Installation
1. Download or clone the module into your Drupal modules directory (`/modules/custom`).
2. Enable the module via the Drupal administration interface or using Drush: `drush en event_registration`.

## Usage

1. After enabling the module, Create custom entity named "Event" from '/admin/content/event'
2. Once the "Event" entities are created, you can Register the event from '/event/registration'.
3. Fill in the required fields (full name, email address, and event selection) and submit the form to register for an event.
4. Registered users can view their event registrations on the confirmation page.
5. View list of the registered event from '/admin/event_registration/report'

## Dependencies
- Drupal 8 or later
- Symfony Session component

## Configuration
Create Event type from '/admin/structure/event_types/add'

## Troubleshooting
- If you encounter any issues, please check the Drupal logs for error messages and ensure that all module dependencies are met.
