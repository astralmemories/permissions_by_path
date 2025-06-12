<?php

namespace Drupal\permissions_by_path\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\UserStorageInterface;

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
   * Constructs a NoticeController object.
   */
  public function __construct(AccountProxyInterface $current_user, ConfigFactoryInterface $config_factory, UserStorageInterface $user_storage) {
    $this->currentUser = $current_user;
    $this->configFactory = $config_factory;
    $this->userStorage = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('config.factory'),
      $container->get('entity_type.manager')->getStorage('user')
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
    $module_enabled = $config->get('module_enable');
    $affected_roles = $config->get('affected_roles') ?: [];
    $affected_node_forms = $config->get('affected_node_forms') ?: [];
    $path_permissions = $config->get('path_permissions') ?: [];

    // Get the current user's roles.
    $user_roles = $this->currentUser->getRoles();

    // Check if the module is enabled and if the user has an affected role.
    $is_affected = (bool) array_intersect($user_roles, $affected_roles);

    // Find which paths this user has access to.
    $user_paths = [];
    foreach ($path_permissions as $mapping) {
      if (!empty($mapping['path']) && !empty($mapping['users'])) {
        if (in_array($username, $mapping['users'], TRUE)) {
          $user_paths[] = $mapping['path'];
        }
      }
    }

    // Build a render array for demonstration.
    return [
      '#theme' => 'item_list',
      '#title' => $this->t('Permissions by Path Debug Info'),
      '#items' => [
        $this->t('Module enabled: @enabled', ['@enabled' => $module_enabled ? 'Yes' : 'No']),
        $this->t('Your username: @username', ['@username' => $username]),
        $this->t('Your roles: @roles', ['@roles' => implode(', ', $user_roles)]),
        $this->t('Affected roles: @roles', ['@roles' => implode(', ', $affected_roles)]),
        $this->t('Affected content types: @types', ['@types' => implode(', ', $affected_node_forms)]),
        $this->t('You have access to these paths: @paths', ['@paths' => $user_paths ? implode(', ', $user_paths) : $this->t('None')]),
        $this->t('Are you affected by this module? @affected', ['@affected' => $is_affected ? 'Yes' : 'No']),
      ],
    ];
  }

}
