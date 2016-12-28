<?php
/**
 * @file
 * Contains \Drupal\custom_slogan\Controller\SloganController.
 */

namespace Drupal\custom_slogan\Controller;

use Drupal\Core\Controller\ControllerBase;

class SloganController extends ControllerBase {
  public function content() {
    return array(
        '#type' => 'markup',
        '#markup' => $this->t('Custom Slogan!'),
    );
  }
}
