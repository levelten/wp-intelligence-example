<?php

if (!is_callable('intel_wp_theme')) {
  /**
   * Provides basic theme processes as standin if intel not installed.
   * @param $hook
   * @param array $variables
   * @return mixed|string
   */
  function intel_wp_theme($hook, $variables = array()) {
    if (class_exists('Intel_Df')) {
      return Intel_Df::theme($hook, $variables);
    }

    $output = '';

    $theme_info = array();
    $theme_info = apply_filters('intel_theme', $theme_info);

    // call preprocess functions
    if (is_callable('template_preprocess_' . $hook)) {
      call_user_func_array('template_preprocess_' . $hook, array(&$variables));
    }

    // allow plugins to preprocess variables
    $variables = apply_filters('intel_preprocess_' . $hook, $variables);

    // call process functions
    if (is_callable('template_process_' . $hook)) {
      call_user_func_array('template_process_' . $hook, array(&$variables));
    }

    // allow plugins to process variables
    $variables = apply_filters('intel_process_' . $hook, $variables);

    if (!empty($theme_info[$hook]['callback'])) {
      $func = $theme_info[$hook]['callback'];
      if (is_string($func) && is_callable($func)) {
        $output = call_user_func_array($func, array(&$variables));
      }
    }

    return $output;
  }

  /**
   * Implements hook_theme().
   */
  function intel_wp_theme_info($themes = array()) {
    //$themes = array();
    $themes['wp_screen'] = array(
      'variables' => array(
        'title' => NULL,
        'content' => NULL,
        'help_tab' => array(),
      ),
      'callback' => 'theme_wp_screen',
    );
    $themes['wp_welcome_panel'] = array(
      'variables' => array(
        'title' => NULL,
        'description' => NULL,
        'panel_header' => NULL,
        'panel_footer' => NULL,
        'content' => NULL,
      ),
      'callback' => 'theme_wp_welcome_panel',
    );
    return $themes;
  }

// Register hook_theme()
  add_filter('intel_theme', 'intel_wp_theme_info');

  function template_preprocess_wp_screen(&$vars) {

  }

  function theme_wp_screen(&$vars) {
    global $intel_wp_screen;

    $screen = get_current_screen();

    $intel_wp_screen = (object) array(
      'id' => !empty($vars['screen_id']) ? $vars['screen_id'] : $screen->id,
      'variables' => $vars,
    );

    $output = '';
    $class = 'wrap intel-wp-screen';
    if (!empty($vars['class'])) {
      $class .= ' ' . implode(' ', $vars['class']);
    }

    $output .= '<div class="' . $class . '">';

    if (isset($vars['title'])) {
      $output .= '<h1>' . $vars['title'] . '</h1>';
    }

    if (isset($vars['content'])) {
      $output .= $vars['content'];
    }

    $output .= '</div>';

    return $output;
  }

  function template_preprocess_wp_welcome_panel(&$vars) {

  }

  function theme_wp_welcome_panel(&$vars) {
    $output = '';
    $class = 'welcome-panel intel-wp-welcome-panel';
    if (!empty($vars['class'])) {
      $class .= ' ' . implode(' ', $vars['class']);
    }
    $output .= '<div id="welcome-panel" class="' . $class . '">';

    if (isset($vars['panel_header'])) {
      $output .= $vars['panel_header'];
    }

    $output .= '<div class="welcome-panel-content">';

    if (isset($vars['title'])) {
      $output .= '<h2 class="title">' . $vars['title'] . '</h2>';
    }

    if (isset($vars['description'])) {
      $output .= '<div class="about-description">' . $vars['description'] . '</div>';
    }

    if (isset($vars['content'])) {
      $output .= '<div class="welcome-panel-column-container">';
      if (is_array($vars['content'])) {
        foreach ($vars['content'] as $cnt => $content) {
          $class = 'welcome-panel-column-container';
          if ($cnt == (count($content) - 1)) {
            $class .= ' welcome-panel-last';
          }
          $output .= '<div class="' . $class . '">';
          $output .= $content;
          $output .= '</div>'; // end div.welcome-panel-column-container
        }
      }
      else {
        $output .= $vars['content'];
      }

      $output .= '</div>'; // end div.welcome-panel-column-container

    }

    $output .= '</div>'; // end div.welcome-panel-content

    if (isset($vars['panel_footer'])) {
      $output .= $vars['panel_footer'];
    }

    $output .= '</div>'; // end div#welcome-panel

    return $output;
  }
}