<?php

namespace Drupal\permissions_by_path\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\RoleStorageInterface;
use Drupal\node\NodeTypeStorageInterface;

/**
 * Defines the configuration form for the Permissions by Path module.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * The role storage.
   *
   * @var \Drupal\user\RoleStorageInterface
   */
  protected $roleStorage;

  /**
   * The node type storage.
   *
   * @var \Drupal\node\NodeTypeStorageInterface
   */
  protected $nodeTypeStorage;

  /**
   * Constructs the form.
   */
  public function __construct($config_factory, $typed_config, RoleStorageInterface $role_storage, NodeTypeStorageInterface $node_type_storage) {
    parent::__construct($config_factory, $typed_config);
    $this->roleStorage = $role_storage;
    $this->nodeTypeStorage = $node_type_storage;
  }

  /**
   * Dependency injection factory for this form.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('entity_type.manager')->getStorage('user_role'),
      $container->get('entity_type.manager')->getStorage('node_type')
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

    // Get all roles.
    $roles = $this->roleStorage->loadMultiple();
    $role_options = [];
    foreach ($roles as $role) {
      $role_options[$role->id()] = $role->label() . ' (' . $role->id() . ')';
    }

    // Get all content types.
    $content_types = $this->nodeTypeStorage->loadMultiple();
    $content_type_options = [];
    foreach ($content_types as $type) {
      $content_type_options[$type->id()] = $type->label() . ' (' . $type->id() . ')';
    }

    // Checkbox to enable or disable the module.
    $form['module_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable module'),
      '#default_value' => $config->get('module_enable'),
    ];

    // Checkboxes for listing affected roles.
    $form['affected_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles affected'),
      '#options' => $role_options,
      '#default_value' => $config->get('affected_roles') ?: [],
      '#description' => $this->t('Select roles that <strong>are</strong> affected by this module.'),
    ];

    // Checkboxes for listing affected content types.
    $form['affected_node_forms'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types affected'),
      '#options' => $content_type_options,
      '#default_value' => $config->get('affected_node_forms') ?: [],
      '#description' => $this->t('Select content types that <strong>are</strong> affected by this module.'),
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
   * Form submission handler. Saves all config values to the config system.
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

    // Only save the keys (machine names) of checked items.
    $affected_roles = array_keys(array_filter($values['affected_roles']));
    $affected_node_forms = array_keys(array_filter($values['affected_node_forms']));

    $this->config('permissions_by_path.settings')
      ->set('module_enable', (bool) $values['module_enable'])
      ->set('affected_roles', $affected_roles)
      ->set('affected_node_forms', $affected_node_forms)
      ->set('path_permissions', $path_permissions)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
