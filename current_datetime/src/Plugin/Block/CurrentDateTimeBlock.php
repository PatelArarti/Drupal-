<?php

namespace Drupal\current_datetime\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CurrentDateTime' block.
 *
 * @Block(
 *   id = "current_datetime_block",
 *   admin_label = @Translation("CurrentDateTime"),
 *   category = @Translation("Custom")
 * )
 */
class CurrentDateTimeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the current datetime.
    $current_date_time = \Drupal::service('datetime.time')->getCurrentTime();
    $formatted_date_time = date('Y-m-d H:i:s', $current_date_time);

    // Return the block content.
    return [
      '#markup' => $formatted_date_time,
    ];
  }

}
