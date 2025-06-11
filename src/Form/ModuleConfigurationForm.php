<?php

namespace Drupal\permissions_by_path\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the configuration form for the Permissions by Path module.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * Constructs the configuration form object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typed_config
   *   The typed config manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, TypedConfigManagerInterface $typed_config) {
    parent::__construct($config_factory, $typed_config);
  }

  /**
   * Dependency injection factory for this form.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed')
    );
  }

  /**
   * Returns the unique form ID.
   */
  public function getFormId() {
    return 'permissions_by_path_settings';
  }

  /**
   * Returns the names of the editable configuration objects.
   */
  protected function getEditableConfigNames(): array {
    return ['permissions_by_path.settings'];
  }

  /**
   * Builds the configuration form.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Load current config and form state for path_permissions.
    $config = $this->config('permissions_by_path.settings');
    $path_permissions = $form_state->get('path_permissions');
    if ($path_permissions === NULL) {
      $path_permissions = $config->get('path_permissions') ?: [];
      $form_state->set('path_permissions', $path_permissions);
    }

    // Checkbox to enable or disable the module.
    $form['module_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable module'),
      '#default_value' => $config->get('module_enable'),
    ];

    // Textarea for listing unaffected roles (one per line).
    $form['unaffected_roles'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Roles not affected'),
      '#default_value' => implode("\n", $config->get('unaffected_roles')),
      '#description' => $this->t('One role ID per line.'),
    ];

    // Textarea for listing affected content types (one per line).
    $form['affected_node_forms'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Content types affected'),
      '#default_value' => implode("\n", $config->get('affected_node_forms')),
      '#description' => $this->t('One content type machine name per line.'),
    ];

    // Table for dynamic path-to-users mappings.
    $form['path_permissions'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Path'),
        $this->t('Usernames (one per line)'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('No path mappings yet.'),
    ];

    // Populate the table with existing mappings and add remove buttons.
    foreach ($path_permissions as $delta => $item) {
      $form['path_permissions'][$delta]['path'] = [
        '#type' => 'textfield',
        '#default_value' => $item['path'] ?? '',
        '#size' => 30,
        '#required' => TRUE,
      ];
      $form['path_permissions'][$delta]['users'] = [
        '#type' => 'textarea',
        '#default_value' => isset($item['users']) ? implode("\n", $item['users']) : '',
        '#rows' => 2,
        '#required' => TRUE,
      ];
      // Button to remove a mapping row.
      $form['path_permissions'][$delta]['remove'] = [
        '#type' => 'submit',
        '#name' => 'remove_path_' . $delta,
        '#value' => $this->t('Remove'),
        '#submit' => ['::removePathMapping'],
        '#limit_validation_errors' => [],
        '#ajax' => [
          'callback' => '::ajaxRefresh',
          'wrapper' => 'permissions-by-path-form-wrapper',
        ],
      ];
    }

    // Button to add a new empty mapping row.
    $form['add_path'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another mapping'),
      '#submit' => ['::addPathMapping'],
      '#limit_validation_errors' => [],
      '#ajax' => [
        'callback' => '::ajaxRefresh',
        'wrapper' => 'permissions-by-path-form-wrapper',
      ],
    ];

    // Wrap the form for AJAX updates.
    return [
      '#type' => 'container',
      '#attributes' => ['id' => 'permissions-by-path-form-wrapper'],
      'form' => parent::buildForm($form, $form_state),
    ];
  }

  /**
   * Handler for adding a new path-to-users mapping row.
   */
  public function addPathMapping(array &$form, FormStateInterface $form_state) {
    $path_permissions = $form_state->get('path_permissions') ?: [];
    $path_permissions[] = ['path' => '', 'users' => []];
    $form_state->set('path_permissions', $path_permissions);
    $form_state->setRebuild();
  }

  /**
   * Handler for removing a path-to-users mapping row.
   */
  public function removePathMapping(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $name = $triggering_element['#name'];
    $delta = str_replace('remove_path_', '', $name);
    $path_permissions = $form_state->get('path_permissions') ?: [];
    unset($path_permissions[$delta]);
    $form_state->set('path_permissions', array_values($path_permissions));
    $form_state->setRebuild();
  }

  /**
   * AJAX callback to refresh the form wrapper.
   */
  public function ajaxRefresh(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * Form submission handler.
   * Saves all configuration values to the config system.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    $path_permissions = [];
    if (isset($values['path_permissions'])) {
      foreach ($values['path_permissions'] as $item) {
        $path_permissions[] = [
          'path' => $item['path'],
          'users' => array_filter(array_map('trim', explode("\n", $item['users']))),
        ];
      }
    }
    $this->config('permissions_by_path.settings')
      ->set('module_enable', (bool) $values['module_enable'])
      ->set('unaffected_roles', array_filter(explode("\n", $values['unaffected_roles'])))
      ->set('affected_node_forms', array_filter(explode("\n", $values['affected_node_forms'])))
      ->set('path_permissions', $path_permissions)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
