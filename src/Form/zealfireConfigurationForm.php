<?php

/**
 * @file
 * Contains \Drupal\zealfire\Form\zealfireConfigurationForm
 */

namespace Drupal\zealfire\Form;

use Drupal\zealfire\zealfireEntityManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides shared configuration form for all zealfire formats.
 */
class zealfireConfigurationForm extends FormBase {

  /**
   * The zealfire entity manager.
   *
   * @var \Drupal\zealfire\zealfireEntityManagerInterface
   */
  protected $zealfireEntityManager;

  /**
   * Constructs a new form object.
   *
   * @param \Drupal\zealfire\zealfireEntityManagerInterface $zealfire_entity_manager
   *   The zealfire entity manager.
   */
  public function __construct(zealfireEntityManagerInterface $zealfire_entity_manager) {
    $this->zealfireEntityManager = $zealfire_entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('zealfire.entity_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'zealfire_configuration';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $zealfire_format = NULL) {

    // Allow users to choose what entities zealfire is enabled for.
    $form['zealfire_entities'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('zealfire Enabled Entities'),
      '#description' => $this->t('Select the entities that zealfire support should be enabled for.'),
      '#options' => array(),
      '#default_value' => array(),
    );
    // Build the options array.
    foreach($this->zealfireEntityManager->getCompatibleEntities() as $entity_type => $entity_definition) {
      $form['zealfire_entities']['#options'][$entity_type] = $entity_definition->getLabel();
    }
    // Build the default values array.
    foreach($this->zealfireEntityManager->getzealfireEntities() as $entity_type => $entity_definition) {
      $form['zealfire_entities']['#default_value'][] = $entity_type;
    }

    // Provide option to open zealfire page in a new tab/window.
    $form['open_target_blank'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Open in New Tab'),
      '#description' => $this->t('Open the zealfire version in a new tab/window.'),
      '#default_value' => $this->config('zealfire.settings')->get('open_target_blank'),
    );

    // Allow users to include CSS from the current theme.
    $form['css_include'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('CSS Include'),
      '#description' => $this->t('Specify an additional CSS file to include. Relative to the root of the Drupal install. The token <em>[theme:theme_machine_name]</em> is available.'),
      '#default_value' => $this->config('zealfire.settings')->get('css_include'),
    );

    // Provide option to turn off link extraction.
    $form['extract_links'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Extract Links'),
      '#description' => $this->t('Extract any links in the content, e.g. "Some Link (http://drupal.org)'),
      '#default_value' => $this->config('zealfire.settings')->get('extract_links'),
    );

    $form['show'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('zealfire.settings')->set('zealfire_entities', $form_state['value']['zealfire_entities'])->save();
    $this->config('zealfire.settings')->set('open_target_blank', $form_state['value']['open_target_blank'])->save();
    $this->config('zealfire.settings')->set('css_include', $form_state['value']['css_include'])->save();
    $this->config('zealfire.settings')->set('extract_links', $form_state['value']['extract_links'])->save();
  }
}
