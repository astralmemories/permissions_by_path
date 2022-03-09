<?php

namespace Drupal\permissions_by_path\Controller;
use Drupal\Core\Controller\ControllerBase;

class NoticeController extends ControllerBase {

  /**
   * returns a render-able array for a test page.
   */

  public function content() {

    // Check who is visiting this page
    $current_user = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($current_user->id());
    $username = $user->getAccountName();

    // Get the configuration file
    $config = \Drupal::config('permissions_by_path.settings');

    // Create an empty array to store the paths that this current user has access to
    $this_user_permissions = [];

    // Grab the Permissions Paths with their List of users with access
    $path1 = $config->get('path1');
    $usernames_array1 = (array) $config->get('usernames1');

    $path2 = $config->get('path2');
    $usernames_array2 = (array) $config->get('usernames2');

    $path3 = $config->get('path3');
    $usernames_array3 = (array) $config->get('usernames3');

    $path4 = $config->get('path4');
    $usernames_array4 = (array) $config->get('usernames4');

    $path5 = $config->get('path5');
    $usernames_array5 = (array) $config->get('usernames5');

    $path6 = $config->get('path6');
    $usernames_array6 = (array) $config->get('usernames6');

    $path7 = $config->get('path7');
    $usernames_array7 = (array) $config->get('usernames7');

    $path8 = $config->get('path8');
    $usernames_array8 = (array) $config->get('usernames8');

    $path9 = $config->get('path9');
    $usernames_array9 = (array) $config->get('usernames9');

    $path10 = $config->get('path10');
    $usernames_array10 = (array) $config->get('usernames10');



    // echo "<script>console.log('" . json_encode($usernames_array1) . "');</script>";

    // Check if the current user exists on the usernames list 1
    foreach ($usernames_array1 as &$value) {

      if ($value == $username) {

        array_push($this_user_permissions, $path1);
      }
    }

    foreach ($usernames_array2 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path2);
      }
    }

    foreach ($usernames_array3 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path3);
      }
    }

    foreach ($usernames_array4 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path4);
      }
    }

    foreach ($usernames_array5 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path5);
      }
    }

    foreach ($usernames_array6 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path6);
      }
    }

    foreach ($usernames_array7 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path7);
      }
    }

    foreach ($usernames_array8 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path8);
      }
    }

    foreach ($usernames_array9 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path9);
      }
    }

    foreach ($usernames_array10 as &$value) {

      if ($value == $username) {
        array_push($this_user_permissions, $path10);
      }
    }



    $list_of_paths = "";


    // List the paths that this user can access in a single string variable
    foreach ($this_user_permissions as &$permission_path) {

      if ($list_of_paths == "") {
        $list_of_paths = "&nbsp;&nbsp;&nbsp;&nbsp;&#8226; " . $permission_path;
      }
      else {
        $list_of_paths = $list_of_paths . "<br>&nbsp;&nbsp;&nbsp;&nbsp;&#8226; " . $permission_path;
      }

    }


//  class='container text-align-center'

    return ['#markup' => "

      <div class='container'>
        <h3>Hello " . $username . "!<br> You don't have access to edit that page.</h3>
        
        <p>You have access to edit pages that contains the following path(s):</p>
        
        <p>" . $list_of_paths ."</p> 
        
        <p>Please contact web@uccs.edu is you have issues with this module.</p>
      </div>

    "];


    return $build;
  }
}
