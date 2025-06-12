<?php

namespace Drupal\permissions_by_path\Controller;

use Drupal\node\NodeTypeStorageInterface;
use Drupal\user\RoleStorageInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\UserStorageInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;

/**
 * Returns responses for Permissions by Path routes.
 */
class NoticeController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * The role storage.
   *
   * @var \Drupal\user\RoleStorageInterface
   */
  protected $roleStorage;

  /**
   * The node type storage.
   *
   * @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface
   */
  protected $nodeTypeStorage;

  /**
   * Constructs a NoticeController object.
   */
  public function __construct(
    AccountProxyInterface $current_user,
    ConfigFactoryInterface $config_factory,
    UserStorageInterface $user_storage,
    RoleStorageInterface $role_storage,
    ConfigEntityStorageInterface $node_type_storage,
  ) {
    $this->currentUser = $current_user;
    $this->configFactory = $config_factory;
    $this->userStorage = $user_storage;
    $this->roleStorage = $role_storage;
    $this->nodeTypeStorage = $node_type_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $container->get('current_user'),
      $container->get('config.factory'),
      $entity_type_manager->getStorage('user'),
      $entity_type_manager->getStorage('user_role'),
      $entity_type_manager->getStorage('node_type')
    );
  }

  /**
   * Returns a render array for the test page.
   */
  public function content() {
    $user = $this->userStorage->load($this->currentUser->id());
    $username = $user ? $user->getAccountName() : '';

    // Get the configuration.
    $config = $this->configFactory->get('permissions_by_path.settings');
    $affected_roles = $config->get('affected_roles') ?: [];
    $affected_node_forms = $config->get('affected_node_forms') ?: [];
    $path_permissions = $config->get('path_permissions') ?: [];

    // Get the current user's roles, excluding 'authenticated'.
    $user_roles = array_diff($this->currentUser->getRoles(), ['authenticated']);

    // Show roles as "Label (machine_name)".
    $user_roles_display = [];
    foreach ($user_roles as $rid) {
      if ($role = $this->roleStorage->load($rid)) {
        $user_roles_display[] = $role->label() . ' (' . $rid . ')';
      }
    }

    // Show affected roles as "Label (machine_name)".
    $affected_roles_display = [];
    foreach ($affected_roles as $rid) {
      if ($role = $this->roleStorage->load($rid)) {
        $affected_roles_display[] = $role->label() . ' (' . $rid . ')';
      }
    }

    // Show affected content types as "Label (machine_name)".
    $affected_node_forms_display = [];
    foreach ($affected_node_forms as $type) {
      if ($bundle = $this->nodeTypeStorage->load($type)) {
        $affected_node_forms_display[] = $bundle->label() . ' (' . $type . ')';
      }
    }

    // Find which paths this user has access to.
    $user_paths = [];
    foreach ($path_permissions as $mapping) {
      if (!empty($mapping['path']) && !empty($mapping['users'])) {
        if (in_array($username, $mapping['users'], TRUE)) {
          $user_paths[] = $mapping['path'];
        }
      }
    }

    // Build the paths as an unordered list.
    $user_paths_markup = $user_paths
      ? '<ul>' . implode('', array_map(fn($p) => '<li><strong>' . $p . '</strong></li>', $user_paths)) . '</ul>'
      : $this->t('None');

    // Build the message.
    $items = [
      $this->t('Username: @username', ['@username' => $username]),
      $this->t('Your roles: @roles', ['@roles' => $user_roles_display ? implode(', ', $user_roles_display) : $this->t('None')]),
      $this->t('Roles affected by Permissions by Path: @roles', ['@roles' => $affected_roles_display ? implode(', ', $affected_roles_display) : $this->t('None')]),
      $this->t('Content types affected: @types', ['@types' => $affected_node_forms_display ? implode(', ', $affected_node_forms_display) : $this->t('None')]),
      [
        '#markup' => $this->t('Paths you have access to:') . ' ' . $user_paths_markup,
        '#allowed_tags' => ['ul', 'li', 'strong', 'em'],
      ],
    ];

    return [
      '#title' => $this->t('Access Denied'),
      '#markup' => '<div class="messages messages--error">' .
      $this->t('You do not have permission to edit this page.<br><br>Only specific users are allowed to edit content within certain sections of the site. If you believe you should have access, please contact your site administrator.') .
      '</div>',
      'info' => [
        '#theme' => 'item_list',
        '#title' => $this->t('Your Permissions by Path Information'),
        '#items' => $items,
        '#attributes' => ['style' => 'margin-top:2em;'],
      ],
    ];
  }

}
