<?php

/**
 * @file
 * Contains \Drupal\custom_slogan\Form\exampleSettingsForm
 */

namespace Drupal\custom_slogan\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityInterface;

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
 * Implements hook_form_FORM_ID_alter().
 */
  public function custom_slogan_form_taxonomy_form_term_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
    if (!user_access('set custom slogan')) return;

    if (variable_get('custom_slogan_vocab_' . $form['#vocabulary']->vid . '_showfield', 0)) {
      $form['advanced']['custom_slogan'] = array(
      '#type' => 'textfield',
      '#title' => t('Custom Slogan'),
      '#default_value' => isset($form['tid']) ? custom_slogan_load_slogan($form['tid']['#value'], 'term') : '',
      '#size' => 60,
      '#maxlength' => 255,
      '#weight' => -20,
    );
    }
  }

/**
 * Implements hook_form_FORM_ID_alter().
 */
  public function custom_slogan_form_node_type_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {

    if (!user_access('administer custom slogan')) return;

    $form['custom_slogan'] = array(
      '#type' => 'fieldset',
      '#title' => 'Custom Slogan Setting',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
      '#group' => 'additional_settings',
    );
    $form['custom_slogan']['show_field'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Custom Slogan Field'),
      '#description' => 'If checked it will enable custom slogan for this content type.',
      '#options' => array('show_field' => t('Show field')),
      '#default_value' => variable_get('custom_slogan_type_' . $form['#node_type']->type . '_showfield', 0) ? array('show_field') : array(),
    );
    $form['custom_slogan']['pattern'] = array(
      '#type'  =>  'textfield',
      '#title'  =>  t('Custom slogan pattern'),
      '#default_value'  =>  variable_get('custom_slogan_type_' . $form['#node_type']->type, ''),
      '#description'  =>  t('Enter custom Slogan pattern'),
      '#maxlength' => 255,
    );
  // Add the token help to a collapsed fieldset at the end of the configuration page.
    $form['custom_slogan']['token_help'] = array(
      '#type'  =>  'fieldset',
      '#title'  =>  t('Available Tokens List'),
      '#collapsible'  =>  TRUE,
      '#collapsed'  =>  TRUE,
    );
    $form['custom_slogan']['token_help']['content'] = array(
      '#theme'  =>  'token_tree',
      '#token_types'  =>  array(),
    );
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

  /**
 * Implements hook_entity_load().
 */
  public function custom_slogan_entity_load($entities) {
    foreach ($nodes as $node) {

    }
  }

  /**
 * Implement hook_node_insert().
 */
  public function custom_slogan_node_insert(EntityInterface $node) {
    if (user_access('set custom slogan') && isset($node->custom_slogan) && drupal_strlen(trim($node->custom_slogan)) > 0) {
      db_insert('custom_slogan')
        ->fields(array(
        'type' => 'node',
        'id' => $node->nid,
        'custom_slogan' => $node->custom_slogan
      ))
        ->execute();
    }
  }

  /**
 * Implement hook_node_update().
 */
  public function custom_slogan_node_update(EntityInterface $node) {
    if (user_access('set custom slogan') && isset($node->custom_slogan)) {
      if (drupal_strlen(trim($node->page_title)) > 0) {
        db_merge('custom_slogan')
          ->key(array('type' => 'node', 'id' => $node->nid))
          ->fields(array('custom_slogan' => $node->custom_slogan))
          ->execute();
      }
      else {
        custom_slogan_node_delete(EntityInterface $node);
      }
    }
  }

  /**
 * Implement hook_node_delete().
 */
  public function custom_slogan_node_delete(EntityInterface $node) {
    db_delete('custom_slogan')
      ->condition('type', 'node')
      ->condition('id', $node->nid)
      ->execute();
  }

/**
 * Implement hook_taxonomy_term_insert().
 */
  public function custom_slogan_taxonomy_term_insert(Drupal taxonomy Term $term) {
    if (user_access('set custom slogan') && isset($term->custom_slogan) && drupal_strlen(trim($term->custom_slogan)) > 0) {
      db_insert('custom_slogan')
        ->fields(array(
        'type' => 'term',
        'id' => $term->tid,
        'custom_slogan' => $term->custom_slogan
      ))
        ->execute();
    }
  }


/**
 * Implement hook_taxonomy_term_update().
 */
  public function custom_slogan_taxonomy_term_update(Drupal taxonomy Term $term) {
    if (user_access('set custom slogan')) {
      if (isset($term->custom_slogan) && drupal_strlen(trim($term->custom_slogan)) > 0) {
        db_merge('custom_slogan')
          ->key(array('type' => 'term', 'id' => $term->tid))
          ->fields(array('custom_slogan' => $term->custom_slogan))
          ->execute();
      }
      else {
        custom_slogan_taxonomy_term_delete(Drupal taxonomy Term $term);
      }
    }
  }

  /**
 * Implement hook_taxonomy_term_delete().
 */
  public function custom_slogan_taxonomy_term_delete(Drupal taxonomy Term $term) {
    db_delete('custom_slogan')
      ->condition('type', 'term')
      ->condition('id', $term->tid)
      ->execute();
  }
}
