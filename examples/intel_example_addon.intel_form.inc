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
 * Class Intel_Example_Addon_Intel_Form
 */
class Intel_Example_Addon_Intel_Form extends Intel_Example_Addon {

  /**
   * constructor.
   *
   */
  public function __construct() {
    parent::__construct();

    // Register hook_admin_menu()
    add_filter('admin_menu', array( $this, 'admin_menu' ));

    /*
     * Intelligence hooks
     */

    // Register hook_intel_form_alter()
    add_action('intel_form_alter', array( $this, 'intel_form_alter'), 10, 3 );

    // Register hook_intel_form_FORM_ID_alter()
    add_action('intel_form_Intel_Example_Addon_Intel_Form::intel_example_addon_settings_form_alter', array( $this, 'intel_form_intel_example_addon_settings_form_alter'), 10, 2 );
  }

  /**
   * Implements hook_admin_menu()
   */
  public function admin_menu() {
    parent::admin_menu();

    // Custom sub-page for Intel settings
    add_submenu_page('example', esc_html__("Settings Form", $this->plugin_un), esc_html__("Intelligence settings form", $this->plugin_un), 'manage_options', $this->plugin_un . '_form', array($this, 'example_settings_form_page'));
  }

  /*
   * Settings page for Admin > Example > Intelligence
   */
  public function example_settings_form_page() {

    if (!$this->is_intel_installed()) {
      require_once( $this->dir . $this->plugin_un . '.setup.inc' );
      print intel_example_addon_setup()->plugin_setup_notice(array('alert' => 1));
      return;
    }

    // include Intel_Form class
    require_once INTEL_DIR . 'includes/class-intel-form.php';

    $output = '';
    $form = Intel_Form::drupal_get_form('Intel_Example_Addon_Intel_Form::intel_example_addon_settings_form');
    $content = Intel_Df::render($form);
    $vars = array(
      'title' => __('Intelligence settings form'),
      'content' => $content,
    );

    $output = intel_wp_theme('wp_screen', $vars);

    echo $output;
  }

  /**
   *
   */
  public static function intel_example_addon_settings_form($form, &$form_state) {
    // Get setings from option
    $settings = get_option('intel_example_addon_settings', array());

    // Add settings to form_state so alter, validate and submit hooks have access.
    $form_state['settings'] = $settings;

    // Add a field
    $form['example_textfield'] = array(
      '#type' => 'textfield',
      '#title' => __('Example textfield', self::$instance->plugin_un),
      '#default_value' => !empty($settings['example_textfield']) ? $settings['example_textfield'] : '',
    );

    // Add submit button
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['save'] = array(
      '#type' => 'submit',
      '#value' => Intel_Df::t('Save'),
    );

    return $form;
  }

  /**
   * Implements FORM_ID_validate();
   *
   * Processes form submissions.
   *
   * @param $form
   * @param $form_state
   */
  public static function intel_example_addon_settings_form_validate($form, &$form_state) {
    $values = $form_state['values'];
    $settings = $form_state['settings'];

    update_option('intel_example_addon_settings', $values);
  }

  /**
   * Implements FORM_ID_submit();
   *
   * Processes form submissions.
   *
   * @param $form
   * @param $form_state
   */
  public static function intel_example_addon_settings_form_submit($form, &$form_state) {
    $values = $form_state['values'];
    $settings = $form_state['settings'];

    update_option('intel_example_addon_settings', $values);
  }

  /**
   * Implements hook_intel_form_alter()
   *
   * Enables the altering of any intel_form
   *
   * @param $form
   * @param $form_state
   * @param $form_id
   */
  public function intel_form_alter(&$form, &$form_state, $form_id) {
    if ($form_id == 'Intel_Example_Addon_Intel_Form::intel_example_addon_settings_form') {
      // get settings from form_state
      $settings = $form_state['settings'];

      // Add field
      $form['example_textfield_alter'] = array(
        '#type' => 'textfield',
        '#title' => __('Example textfield alter', $this->plugin_un),
        '#default_value' => !empty($settings['example_textfield_alter']) ? $settings['example_textfield_alter'] : '',
        '#description' => __('This field was added using hook_form_alter()', $this->plugin_un),
      );
    }
  }

  /**
   * Implements hook_form_FORM_ID_alter()
   *
   * @param $form
   * @param $form_state
   */
  public function intel_form_intel_example_addon_settings_form_alter(&$form, &$form_state) {
    // get settings from form_state
    $settings = $form_state['settings'];

    // Add field
    $form['example_textfield_name_alter'] = array(
      '#type' => 'textfield',
      '#title' => __('Example textfield name alter', $this->plugin_un),
      '#default_value' => !empty($settings['example_textfield_name_alter']) ? $settings['example_textfield_name_alter'] : '',
      '#description' => __('This field was added using hook_form_FORM_ID_alter()', $this->plugin_un),
    );

    $counter = get_option('intel_example_addon_settings_counter', 0);
    $form['example_counter'] = array(
      '#type' => 'textfield',
      '#title' => __('Example submit counter', $this->plugin_un),
      '#default_value' => $counter,
      '#description' => __('Counts form submissions', $this->plugin_un),
      '#size' => 5,
    );

    $form['example_counter_reset'] = array(
      '#type' => 'checkbox',
      '#title' => __('Reset counter', $this->plugin_un),
      '#default_value' => '',
    );

    // add custom validate callback
    $form['#validate'][] = 'Intel_Example_Addon_Intel_Form::intel_example_addon_settings_form_counter_validate';

    // add custom submit callback
    $form['#submit'][] = 'Intel_Example_Addon_Intel_Form::intel_example_addon_settings_form_counter_submit';
  }

  /**
   * Implements callback_FORM_ID_validate()
   *
   * @param $form
   * @param $form_state
   */
  public static function intel_example_addon_settings_form_counter_validate($form, &$form_state) {

  }

  /**
   * Implements callback_FORM_ID_submit()
   *
   * Processes form submissions.
   *
   * @param $form
   * @param $form_state
   */
  public static function intel_example_addon_settings_form_counter_submit($form, &$form_state) {
    $counter = 0;
    if (empty($form_state['values']['example_counter_reset'])) {
      $counter = intval($form_state['values']['example_counter']) + 1;
    }

    update_option('intel_example_addon_settings_counter', $counter);
  }

  /**
   * Implements hook_intel_demo_pages()
   *
   * Adds a demo page to test tracking for this plugin.
   *
   * @param array $posts
   * @return array
   */
  function intel_demo_posts($posts = array()) {
    $id = -1 * (count($posts) + 1);

    $content = '';
    $content .= '<h3>Demo Intelligence Form</h3>' . "\n";
    $content .= '[intel_form name="intel_demo_contact_form" title="' . Intel_Df::t('Demo Contact Form') . '"]' . "\n";

    $posts["$id"] = array(
      'ID' => $id,
      'post_type' => 'page',
      'post_title' => 'Demo Example',
      'post_content' => $content,
      'intel_demo' => array(
        'url' => 'intelligence/demo/' . $this->plugin_un,
      ),
    );

    return $posts;
  }
}