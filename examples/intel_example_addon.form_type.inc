<?php
/**
 * Intelligence Example Addon Form Type
 *
 * Extends Intelligence Example Addon to provide functionality to connect with
 * plugins that provide form types.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Class Intel_Example_Addon_Form_Type
 */
class Intel_Example_Addon_Form_Type extends Intel_Example_Addon {

  /**
   * Intel form type unique name
   * @var string
   */
  public $form_type_un = 'exampleform';

  /**
   * constructor.
   *
   */
  public function __construct() {

    parent::__construct();

    /*
     * Intelligence hooks
     */

    // Register hook_intel_form_type_info()
    add_filter('intel_form_type_info', array( $this, 'intel_form_type_info'));

    // Register hook_intel_form_type_FORM_TYPE_UN_form_info()
    add_filter('intel_form_type_' . $this->form_type_un . '_form_info', array( $this, 'intel_form_type_form_info' ));

    // Register hook_intel_url_urn_resolver()
    add_filter('intel_url_urn_resolver', array( $this, 'intel_url_urn_resolver') );

  }

  /**
   * Implements hook_form_type_info()
   *
   * Registers a form type provided by or connected to this plugin. Only needed
   * if the plugin provides a form such as Contact Form 7, Ninja Forms or Gravity Forms.
   *
   * Optional: Remove if plugin does not provide a form type to be tracked
   *
   * @param array $info
   * @return array
   */
  public function intel_form_type_info($info = array()) {
    $info[$this->form_type_un] = array(
      // A machine name to uniquely identify the form type provided by this plugin.
      'un' => $this->form_type_un,
      // Human readable name of the form type provided by this plugin.
      'title' => __( 'Example Form', $this->plugin_un ),
      // The plugin unique name for this plugin
      'plugin_un' => $this->plugin_un,
      // form tracking features addon supports
      'supports' => array(
        'track_submission' => 1,
        'track_submission_value' => 1,
        'track_view' => 1,
      ),
      // Callback to get data for form submissions
      'submission_data_callback' => array($this, 'intel_form_type_submission_data'),
    );
    return $info;
  }

  /**
   * Implements hook_intel_form_type_FORM_TYPE_UN_form_info()
   *
   * Provides configuration and Intelligence settings information about each
   * form that exists in the site of the form_type provided or connected to by
   * this plugin.
   *
   * Optional: Remove if plugin does not provide a form type to be tracked
   */
  public function intel_form_type_form_info($options = array()) {
    static $info = array();

    if (!empty($info) && empty($options['refresh'])) {
      return $info;
    }

    $args = array(
      'post_type'   => 'example_form',
    );

    $posts = get_posts( $args );

    foreach ($posts as $k => $post) {
      $row = array(
        'settings' => array(),
      );
      $row['id'] = $post->ID;
      $row['title'] = $post->post_title;
      $options = get_option('intel_example_form_settings_' . $post->ID, array());

      if ($this->is_intel_installed() && !empty($options)) {

        if (!empty($options['track_submission'])) {
          $labels = intel_get_form_submission_eventgoal_options();
          $row['settings']['track_submission'] = $options['track_submission'];
          $row['settings']['track_submission__title'] = !empty($labels[$options['track_submission']]) ? $labels[$options['track_submission']] : $options['track_submission'];
        }

        if (!empty($options['track_submission_value'])) {
          $row['settings']['track_submission_value'] = $options['track_submission_value'];
        }

        $row['settings']['field_map'] = array();
        if (!empty($options['field_map']) && is_array($options['field_map'])) {
          foreach ($options['field_map'] as $k => $v) {
            if (!empty($v)) {
              $row['settings']['field_map'][] = $v;
            }
          }
        }

      }

      $row['settings_url'] = '/wp-admin/admin.php?page=intel_example&action=edit&post=' . $row['id'] . '#intel';
      $info[$post->ID] = $row;
    }

    return $info;
  }

  /*
 * Provides an Intelligence > Help > Demo > Example page
 */
  public function intel_admin_help_demo_page() {
    $output = '';

    $demo_mode = get_option('intel_demo_mode', 0);

    /*
    if (empty($demo_mode)) {
      $msg = Intel_Df::t('Demo is currently disabled for non logged in users. Go to demo settings to enable.');
      Intel_Df::drupal_set_message($msg, 'warning');
    }
    */

    $output .= '<div class="card">';
    $output .= '<div class="card-block clearfix">';

    $output .= '<p class="lead">';
    $output .= Intel_Df::t('Try out Example tracking!');
    //$output .= ' ' . Intel_Df::t('This tutorial will walk you through the essentials of extending Google Analytics using Intelligence to create results oriented analytics.');
    $output .= '</p>';

    /*
    $l_options = Intel_Df::l_options_add_class('btn btn-info');
    $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path(), $l_options);
    $output .= Intel_Df::l( Intel_Df::t('Demo settings'), 'admin/config/intel/settings/general/demo', $l_options) . '<br><br>';
    */

    $output .= '<div class="row">';
    $output .= '<div class="col-md-6">';
    $output .= '<p>';
    $output .= '<h3>' . Intel_Df::t('First') . '</h3>';
    $output .= __('Launch Google Analytics to see conversions in real-time:', $this->plugin_un);
    $output .= '</p>';

    $output .= '<div>';
    $l_options = Intel_Df::l_options_add_target('ga');
    $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
    $url = 	$url = intel_get_ga_report_url('rt_goal');
    $output .= Intel_Df::l( Intel_Df::t('View real-time conversion goals'), $url, $l_options);

    $output .= '<br>';

    $l_options = Intel_Df::l_options_add_target('ga');
    $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
    $url = 	$url = intel_get_ga_report_url('rt_event');
    $output .= Intel_Df::l( Intel_Df::t('View real-time events'), $url, $l_options);
    $output .= '</div>';
    $output .= '</div>'; // end col-x-6

    $output .= '<div class="col-md-6">';

    $output .= '<p>';
    $output .= '<h3>' . Intel_Df::t('Next') . '</h3>';
    $output .= __('Pick one of your forms to test:', $this->plugin_un);
    $output .= '</p>';

    $forms = $this->intel_form_type_form_info();

    $l_options = Intel_Df::l_options_add_target($this->plugin_un . '_demo');
    $l_options = Intel_Df::l_options_add_class('btn btn-info m-b-_5', $l_options);
    $l_options['query'] = array();
    $output .= '<div>';
    foreach ($forms as $form) {
      $l_options['query']['fid'] = $form['id'];
      $output .= Intel_Df::l( __('Try', $this->plugin_un) . ': ' . $form['title'], 'intelligence/demo/' . $this->plugin_un, $l_options);
      $output .= '<br>';
    }
    $output .= '</div>';

    $output .= '</div>'; // end col-x-6
    $output .= '</div>'; // end row

    $output .= '</div>'; // end card-block
    $output .= '</div>'; // end card

    return $output;
  }

  /**
   * Implements hook_intel_demo_pages()
   *
   * Adds a demo page to test tracking for this plugin.
   *
   * @param array $posts
   * @return array
   */
  public function intel_demo_posts($posts = array()) {
    $id = -1 * (count($posts) + 1);

    $forms = $this->intel_form_type_form_info();

    $content = '';
    if (!empty($_GET['fid']) && !empty($forms[$_GET['fid']])) {
      $form = $forms[$_GET['fid']];
      $content .= '<br><h3>' . $form['title'] . ':</h3>';
      $content .= '[example-form id="' . $form['id'] . '" title="' . $form['title'] . '"]';
    }
    elseif (!empty($forms)) {
      $form = array_shift($forms);
      $content .= '<br><h3>' . $form['title'] . ':</h3>';
      $content .= '[example-form id="' . $form['id'] . '" title="' . $form['title'] . '"]';
    }
    else {
      $content = __('No Example forms were found', $this->plugin_un);
    }
    $posts["$id"] = array(
      'ID' => $id,
      'post_type' => 'page',
      'post_title' => __('Demo') . ' ' . $this->plugin_info['extends_plugin_title'],
      'post_content' => $content,
      'intel_demo' => array(
        'url' => 'intelligence/demo/' . $this->plugin_un,
      ),
    );

    return $posts;
  }

  /**
   * Implements hook_intel_url_urn_resolver()
   */
  function intel_url_urn_resolver($vars) {
    $urn_elms = explode(':', $vars['path']);
    if ($urn_elms[0] == 'urn') {
      array_shift($urn_elms);
    }
    if ($urn_elms[0] == '') {
      if ($urn_elms[1] == $this->form_type_un && !empty($urn_elms[2])) {
        $vars['path'] = 'wp-admin/post.php';
        $vars['options']['query']['action'] = 'edit';
        $vars['options']['query']['post'] = $urn_elms[2];
      }
    }

    return $vars;
  }

  /**
   * Implements hook_intel_test_url_parsing_alter()
   */
  function intel_test_url_parsing_alter($urls) {
    $urls[] = ":{$this->form_type_un}:1";
    $urls[] = "urn::{$this->form_type_un}:1";
    $urls[] = ":{$this->form_type_un}:1:1";
    $urls[] = "urn::{$this->form_type_un}:1:1";
    $urls[] = ":{$this->form_type_un}:1:submission:1";
    $urls[] = "urn::{$this->form_type_un}:1:submission:1";
    return $urls;
  }

}