<?php

namespace Drupal\permissions_by_path\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'permissions_by_path_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'permissions_by_path.settings',
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

    $config = $this->config('permissions_by_path.settings');

    // Enable/Disable this module
    $form['module_enable_checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Module'),
      '#default_value' => $config->get('module_enable'),
      '#description' => $this->t('Enable/Disable this module functionality. All other settings are ignored if this is not checked.'),
    ];

    // unaffected_roles
    $form['unaffected_roles_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of user roles that are not affected by this module:'),
      '#default_value' => implode(PHP_EOL, $config->get('unaffected_roles')),
      '#description' => $this->t('Write one user role ID per line.'),
    ];

    // affected_node_forms
    $form['affected_node_forms_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of Content Types (Page types) that will be affected by this module:'),
      '#default_value' => implode(PHP_EOL, $config->get('affected_node_forms')),
      '#description' => $this->t('One node Content Type ID per line. Example: landing_page'),
    ];

    // path1
    $form['path1_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path1:'),
      '#default_value' => $config->get('path1'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /degrees'),
    ];

    // usernames1
    $form['usernames1_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path1:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames1')),
      '#description' => $this->t('One username per line.'),
    ];

    // path2
    $form['path2_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path2:'),
      '#default_value' => $config->get('path2'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /admissions'),
    ];

    // usernames2
    $form['usernames2_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path2:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames2')),
      '#description' => $this->t('One username per line.'),
    ];

    // path3
    $form['path3_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path3:'),
      '#default_value' => $config->get('path3'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /experience'),
    ];

    // usernames3
    $form['usernames3_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path3:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames3')),
      '#description' => $this->t('One username per line.'),
    ];

    // path4
    $form['path4_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path4:'),
      '#default_value' => $config->get('path4'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /parents'),
    ];

    // usernames4
    $form['usernames4_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path4:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames4')),
      '#description' => $this->t('One username per line.'),
    ];

    // path5
    $form['path5_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path5:'),
      '#default_value' => $config->get('path5'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /math'),
    ];

    // usernames5
    $form['usernames5_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path5:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames5')),
      '#description' => $this->t('One username per line.'),
    ];

    // path6
    $form['path6_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path6:'),
      '#default_value' => $config->get('path6'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /history'),
    ];

    // usernames6
    $form['usernames6_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path6:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames6')),
      '#description' => $this->t('One username per line.'),
    ];

    // path7
    $form['path7_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path7:'),
      '#default_value' => $config->get('path7'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /psychology'),
    ];

    // usernames7
    $form['usernames7_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path7:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames7')),
      '#description' => $this->t('One username per line.'),
    ];

    // path8
    $form['path8_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path8:'),
      '#default_value' => $config->get('path8'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /geography'),
    ];

    // usernames8
    $form['usernames8_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path8:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames8')),
      '#description' => $this->t('One username per line.'),
    ];

    // path9
    $form['path9_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path9:'),
      '#default_value' => $config->get('path9'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /sociology'),
    ];

    // usernames9
    $form['usernames9_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path9:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames9')),
      '#description' => $this->t('One username per line.'),
    ];

    // path10
    $form['path10_textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permissions Path10:'),
      '#default_value' => $config->get('path10'),
      '#description' => $this->t('Add an existing path from this site that you wish to grant access to the following users. Example: /science'),
    ];

    // usernames10
    $form['usernames10_textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of users with access to Path10:'),
      '#default_value' => implode(PHP_EOL, $config->get('usernames10')),
      '#description' => $this->t('One username per line.'),
    ];

    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    # Set configuration file permissions_by_path.settings

    $values = $form_state->getValues();

    $permissions_by_path_settings = $this->config('permissions_by_path.settings');

    // Prepare the configuration array fields
    $unaffected_roles_settings_array = preg_split("/[\r\n]+/", $values['unaffected_roles_textarea']);
    $unaffected_roles_settings_array = array_filter($unaffected_roles_settings_array);
    $unaffected_roles_settings_array = array_values($unaffected_roles_settings_array);

    $affected_node_forms_settings_array = preg_split("/[\r\n]+/", $values['affected_node_forms_textarea']);
    $affected_node_forms_settings_array = array_filter($affected_node_forms_settings_array);
    $affected_node_forms_settings_array = array_values($affected_node_forms_settings_array);

    $usernames1_settings_array = preg_split("/[\r\n]+/", $values['usernames1_textarea']);
    $usernames1_settings_array = array_filter($usernames1_settings_array);
    $usernames1_settings_array = array_values($usernames1_settings_array);

    $usernames2_settings_array = preg_split("/[\r\n]+/", $values['usernames2_textarea']);
    $usernames2_settings_array = array_filter($usernames2_settings_array);
    $usernames2_settings_array = array_values($usernames2_settings_array);

    $usernames3_settings_array = preg_split("/[\r\n]+/", $values['usernames3_textarea']);
    $usernames3_settings_array = array_filter($usernames3_settings_array);
    $usernames3_settings_array = array_values($usernames3_settings_array);

    $usernames4_settings_array = preg_split("/[\r\n]+/", $values['usernames4_textarea']);
    $usernames4_settings_array = array_filter($usernames4_settings_array);
    $usernames4_settings_array = array_values($usernames4_settings_array);

    $usernames5_settings_array = preg_split("/[\r\n]+/", $values['usernames5_textarea']);
    $usernames5_settings_array = array_filter($usernames5_settings_array);
    $usernames5_settings_array = array_values($usernames5_settings_array);

    $usernames6_settings_array = preg_split("/[\r\n]+/", $values['usernames6_textarea']);
    $usernames6_settings_array = array_filter($usernames6_settings_array);
    $usernames6_settings_array = array_values($usernames6_settings_array);

    $usernames7_settings_array = preg_split("/[\r\n]+/", $values['usernames7_textarea']);
    $usernames7_settings_array = array_filter($usernames7_settings_array);
    $usernames7_settings_array = array_values($usernames7_settings_array);

    $usernames8_settings_array = preg_split("/[\r\n]+/", $values['usernames8_textarea']);
    $usernames8_settings_array = array_filter($usernames8_settings_array);
    $usernames8_settings_array = array_values($usernames8_settings_array);

    $usernames9_settings_array = preg_split("/[\r\n]+/", $values['usernames9_textarea']);
    $usernames9_settings_array = array_filter($usernames9_settings_array);
    $usernames9_settings_array = array_values($usernames9_settings_array);

    $usernames10_settings_array = preg_split("/[\r\n]+/", $values['usernames10_textarea']);
    $usernames10_settings_array = array_filter($usernames10_settings_array);
    $usernames10_settings_array = array_values($usernames10_settings_array);


    // Set the new values to their fields
    $permissions_by_path_settings->set('module_enable', $form_state->getValue('module_enable_checkbox'));

    $permissions_by_path_settings->set('unaffected_roles', $unaffected_roles_settings_array);

    $permissions_by_path_settings->set('affected_node_forms', $affected_node_forms_settings_array);

    $permissions_by_path_settings->set('path1', $form_state->getValue('path1_textfield'));
    $permissions_by_path_settings->set('usernames1', $usernames1_settings_array);

    $permissions_by_path_settings->set('path2', $form_state->getValue('path2_textfield'));
    $permissions_by_path_settings->set('usernames2', $usernames2_settings_array);

    $permissions_by_path_settings->set('path3', $form_state->getValue('path3_textfield'));
    $permissions_by_path_settings->set('usernames3', $usernames3_settings_array);

    $permissions_by_path_settings->set('path4', $form_state->getValue('path4_textfield'));
    $permissions_by_path_settings->set('usernames4', $usernames4_settings_array);

    $permissions_by_path_settings->set('path5', $form_state->getValue('path5_textfield'));
    $permissions_by_path_settings->set('usernames5', $usernames5_settings_array);

    $permissions_by_path_settings->set('path6', $form_state->getValue('path6_textfield'));
    $permissions_by_path_settings->set('usernames6', $usernames6_settings_array);

    $permissions_by_path_settings->set('path7', $form_state->getValue('path7_textfield'));
    $permissions_by_path_settings->set('usernames7', $usernames7_settings_array);

    $permissions_by_path_settings->set('path8', $form_state->getValue('path8_textfield'));
    $permissions_by_path_settings->set('usernames8', $usernames8_settings_array);

    $permissions_by_path_settings->set('path9', $form_state->getValue('path9_textfield'));
    $permissions_by_path_settings->set('usernames9', $usernames9_settings_array);

    $permissions_by_path_settings->set('path10', $form_state->getValue('path10_textfield'));
    $permissions_by_path_settings->set('usernames10', $usernames10_settings_array);

    // Save the new configuration
    $permissions_by_path_settings->save();


    parent::submitForm($form, $form_state);

    // Clear the config_filter plugin cache.
    \Drupal::service('plugin.manager.config_filter')->clearCachedDefinitions();
  }

}