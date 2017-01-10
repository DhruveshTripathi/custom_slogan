<?php

/**
 * @file
 * Contains \Drupal\custom_slogan\Form\exampleSettingsForm
 */

namespace Drupal\custom_slogan\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Custom Slogan settings for this site.
 */
class customsloganSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_slogan_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_slogan.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_slogan.settings');

    $form['custom_slogan'] = array(
      '#type' => 'fieldset',
      '#title' => t('Custom Slogan Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => empty($custom_slogan),
      '#group' => 'additional_settings',
    );

    $form['custom_slogan']['custom_slogan'] = array(
      '#type' => 'textfield',
      '#title' => t('Custom Slogan'),
      '#default_value' => $custom_slogan,
      '#size' => 60,
      '#maxlength' => 255,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
 * {@inheritdoc}
 */
  public function submitForm(array &$form, array &$form_state) {
    $this->config('custom_slogan.settings')
      ->set('element', $form_state['values']['custom_slogan_wrapping_element'])
      ->save();

    parent::submitForm($form, $form_state);
  }
}
