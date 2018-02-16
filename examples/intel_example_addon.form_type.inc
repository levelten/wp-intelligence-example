<?php
/**
* Intelligence Example Addon bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              getlevelten.com/blog/tom
* @since             1.0.0
* @package           Intelligence
*
* @wordpress-plugin
* Plugin Name:       Intelligence Example Addon
* Plugin URI:        https://wordpress.org/plugins/intelligence-example-addon
* Description:       Example Intelligence add-on plugin.
* Version:           1.0.0.0-dev
* Author:            LevelTen
* Author URI:        https://intelligencewp.com
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       intel_example
* Domain Path:       /languages
* GitHub Plugin URI: https://github.com/levelten/wp-intelligence-example-addon
*/

/**
 * This is an starter / example Intelligence add-on plugin. It is designed to
 * extend the fictional "Example plugin".
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Class Intel_Example
 */
final class Intel_Example_Addon_Form_Type {

  public $main;

  /**
   * @var Intel_Example_Addon
   * @since 1.0.0
   */
  private static $instance;

  /**
   * Main Plugin Instance
   *
   * Insures that only one instance of a plugin class exists in memory at any one
   * time. Also prevents needing to define globals all over the place.
   *
   * @since 1.0.2
   * @static
   * @static var array $instance
   * @return Intel_Example_Addon Instance
   */
  public static function instance($options = array()) {
    if (null === static::$instance) {
      static::$instance = new static($options);
    }

    return static::$instance;
  }

  /**
   * constructor.
   *
   */
  public function __construct() {
    global $wp;

    // set main plugin class
    $this->main = intel_example_addon();

    // Register hook_admin_menu()
    add_filter('admin_menu', array( $this, 'admin_menu' ));

    /*
     * Intelligence hooks
     */

    // Register hook_intel_form_type_info()
    add_filter('intel_form_type_info', array( $this, 'intel_form_type_info'));

    // Register hook_intel_form_type_FORM_TYPE_UN_form_info()
    add_filter('intel_form_type_exampleform_form_info', array( $this, 'intel_form_type_form_info' ));

  }

  /**
   * Implements hook_admin_menu()
   */
  public function admin_menu() {
    // Custom sub-page for Intel settings
    //add_submenu_page('example', esc_html__("Settings Form", $this->main->plugin_un), esc_html__("Intelligence settings form", $this->main->plugin_un), 'manage_options', $this->main->plugin_un . '_form', array($this, 'example_settings_page'));

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
  function intel_form_type_info($info = array()) {
    $info[$this->form_type_un] = array(
      // A machine name to uniquly identify the form type provided by this plugin.
      'un' => $this->form_type_un,
      // Human readable name of the form type provided by this plugin.
      'title' => __( 'Example Form', $this->plugin_un ),
      // The plugin unique name for this plugin
      'plugin_un' => $this->plugin_un,
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
  function intel_form_type_form_info($data = NULL, $options = array()) {
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
}

function intel_example_addon_form_type() {
  return Intel_Example_Addon_Form_Type::instance();
}
global $intel_example_addon_form_type;
$intel_example_addon_form_type = intel_example_addon_form_type();