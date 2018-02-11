<?php
/**
* Intelligence Example bootstrap file
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
* Plugin Name:       Intelligence Example
* Plugin URI:        https://wordpress.org/plugins/intelligence-example
* Description:       Example Intelligence add-on plugin.
* Version:           1.0.0.0-dev
* Author:            LevelTen
* Author URI:        https://intelligencewp.com
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       intel_example
* Domain Path:       /languages
* GitHub Plugin URI: https://github.com/levelten/wp-intelligence-example
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

define('INTEL_EXAMPLE_VER', '1.0.0.0-dev');

/**
 * Class Intel_Example
 */
final class Intel_Example {

  protected $version = INTEL_EXAMPLE_VER;

  public $plugin_info = array();

  public $plugin_un = 'intel_example';

  public $form_type_un = 'exampleform';

  /**
   * @var Intel_Example
   * @since 1.0.0
   */
  private static $instance;

  /**
   * Plugin Directory
   *
   * @since 1.0.0
   * @var string $dir
   */
  public $dir = '';

  /**
   * Plugin URL
   *
   * @since 1.0.0
   * @var string $url
   */
  public $url = '';

  /**
   * Main Plugin Instance
   *
   * Insures that only one instance of a plugin class exists in memory at any one
   * time. Also prevents needing to define globals all over the place.
   *
   * @since 1.0.2
   * @static
   * @static var array $instance
   * @return Intel_Example Instance
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

    $this->plugin_info = $this->intel_plugin_info();

    $this->dir = plugin_dir_path(__FILE__);

    $this->url = plugin_dir_url(__FILE__);

    // Register hook_admin_menu()
    add_filter('admin_menu', array( $this, 'admin_menu' ));

    /*
     * Intelligence hooks
     */

    // Register hook_intel_system_info()
    add_filter('intel_system_info', array( $this, 'intel_system_info' ));

    // Registers hook_wp_loaded()
    //add_action('wp_loaded', array( $this, 'wp_loaded' ));

    // Register hook_intel_menu()
    add_filter('intel_menu', array( $this, 'intel_menu' ));

    // Register hook_intel_demo_pages()
    add_filter('intel_demo_posts', array( $this, 'intel_demo_posts' ));

    // Register hook_intel_form_type_info()
    add_filter('intel_form_type_info', array( $this, 'intel_form_type_info'));

    // Register hook_intel_form_type_FORM_TYPE_UN_form_info()
    add_filter('intel_form_type_exampleform_form_info', array( $this, 'intel_form_type_form_info' ));
    // Register hook_wp_loaded()

  }

  /**
   * Implements hook_wp_loaded()
   *
   * Used to check if Intel is not loaded and include setup process if needed.
   * Alternatively this check can be done in hook_admin_menu() if the plugin
   * implements hook_admin_menu()
   */
  public function wp_loaded() {
    // check if Intel is installed, add setup processing if not
    if (!$this->is_intel_installed()) {
      require_once( $this->dir . $this->plugin_un . '.setup.inc' );
    }
  }

  /**
   * Returns if Intelligence plugin is installed
   * @param string $level
   * @return mixed
   */
  public function is_intel_installed($level = 'min') {
    static $flags = array();
    if (!isset($flags[$level])) {
      $flags[$level] = (is_callable('intel_is_installed')) ? intel_is_installed($level) : FALSE;
    }
    return $flags[$level];
  }

  /**
   * Provides plugin data for hook_intel_system_info
   * @param array $info
   * @return array
   */
  function intel_plugin_info($info = array()) {
    $info = array(
      // The unique name for this plugin
      'plugin_un' => $this->plugin_un,
      // Title of the plugin
      'plugin_title' => __('Intelligence Example Add-On', $this->plugin_un),
      // Shorter version of title used when reduced characters are desired
      'plugin_title_short' => __('Intelligence Example', $this->plugin_un),
      // Main plugin file
      'plugin_file' => 'wpcf7-intel.php', // Main plugin file
      // The server path to the plugin files directory
      'plugin_dir' => $this->dir,
      // The browser path to the plugin files directory
      'plugin_url' => $this->url,
      // The install file for the plugin if different than [plugin_un].install
      // Used to auto discover database updates
      'update_file' => 'intel_example.install', // default [plugin_un].install
      // If this plugin extends a plugin other than Intelligience, include that
      // plugin's info in 'extends_' properties
      // The extends plugin unique name
      'extends_plugin_un' => 'example-plugin',
      // the extends plugin title
      'extends_plugin_title' => __('Example Plugin', 'example-plugin'),
    );
    return $info;
  }

  /**
   * Implements hook_intel_system_info()
   *
   * Registers plugin with intel_system
   *
   * @param array $info
   * @return array
   */
  function intel_system_info($info = array()) {
    // array of plugin info indexed by plugin_un
    $info[$this->plugin_un] = $this->intel_plugin_info();
    return $info;
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
      /*
      'plugin' => array(
        // The plugin unique name for this plugin
        'un' => 'intel_example',
        'name' => __( 'Contact Form 7', 'contact-form-7' ),
        'slug' => 'contact-form-7',
        'text_domain' => 'contact-form-7',
      ),
      */
      //'submission_data_callback' => 'wpcf7_intel_form_type_submission_data',
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

  /**
   * Implements hook_intel_menu()
   *
   * @param array $items
   * @return array
   */
  public function intel_menu($items = array()) {
    // route for Admin > Intelligence > Settings > Setup > Example
    $items['admin/config/intel/settings/setup/' . $this->plugin_un] = array(
      'title' => 'Setup',
      'description' => Intel_Df::t('Intelligence Example initial plugin setup'),
      'page callback' => $this->plugin_un . '_admin_setup_page',
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'file' => 'admin/' . $this->plugin_un . '.admin_setup.inc',
      'file path' => $this->dir,
    );
    // rout for Admin > Intelligence > Help > Demo > Example
    $items['admin/help/demo/' . $this->plugin_un] = array(
      'title' => $this->plugin_info['extends_plugin_title'],
      'page callback' => array($this, 'intel_admin_help_demo_page'),
      'access callback' => 'user_access',
      'access arguments' => array('admin intel'),
      'type' => Intel_Df::MENU_LOCAL_TASK,
      'weight' => 10,
    );
    return $items;
  }

  /*
   * Provides an Intelligence > Help > Demo > Example page
   */
  function intel_admin_help_demo_page() {
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

    $l_options = Intel_Df::l_options_add_target('example_demo');
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
  function intel_demo_posts($posts = array()) {
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
      'post_title' => 'Demo Example',
      'post_content' => $content,
      'intel_demo' => array(
        'url' => 'intelligence/demo/' . $this->plugin_un,
      ),
    );

    return $posts;
  }

  /**
   * Implements hook_admin_menu()
   */
  public function admin_menu() {
    // Admin > Example page route to spoof fictitious Example plugin main admin menu item
    add_menu_page(esc_html__("Example", $this->plugin_un), esc_html__("Example", $this->plugin_un), 'manage_options', 'example', array($this, 'example_settings_page'), version_compare($this->plugin_un, '3.8.0', '>=') ? 'dashicons-analytics' : '');

    // Custom sub-page for Intel settings
    add_submenu_page('example', esc_html__("Settings", $this->plugin_un), esc_html__("Intelligence", $this->plugin_un), 'manage_options', $this->plugin_un, array($this, 'example_settings_page'));

    // Intel setup checks. Alternative to using hook_wp_loaded()
    if (!$this->is_intel_installed()) {
      require_once( $this->dir . $this->plugin_un . '.setup.inc' );
      intel_example_setup()->plugin_setup_admin_menu();
    }
  }

  /*
   * Settings page for Admin > Example > Intelligence
   */
  public function example_settings_page() {
    $items = array();

    $items[] = '<div class="wrap">';
    $items[] = '<h1>' . esc_html__( 'Intelligence Settings', $this->plugin_un ) . '</h1>';
    $items[] = '</div>';


    if($this->is_intel_installed()) {
      $connect_desc = __('Connected');
    }
    else {
      $connect_desc = __('Not connected.', $this->plugin_un);
      $connect_desc .= ' ' . sprintf(
          __( ' %sSetup Intelligence%s', $this->plugin_un ),
          '<a href="' . wpcf7_intel_setup()->plugin_setup_url() . '" class="button">', '</a>'
        );
    }

    $items[] = '<table class="form-table">';
    $items[] = '<tbody>';
    $items[] = '<tr>';
    $items[] = '<th>' . esc_html__( 'Intelligence API', $this->plugin_un ) . '</th>';
    $items[] = '<td>' . $connect_desc . '</td>';
    $items[] = '</tr>';

    if ($this->is_intel_installed()) {
      $eventgoal_options = intel_get_form_submission_eventgoal_options();
      $default_name = get_option('intel_form_track_submission_default', 'form_submission');
      $value = !empty($eventgoal_options[$default_name]) ? $eventgoal_options[$default_name] : Intel_Df::t('(not set)');
      $l_options = Intel_Df::l_options_add_destination('wp-admin/admin.php?page=' . $this->plugin_un);
      $l_options['attributes'] = array(
        'class' => array('button'),
      );
      $value .= ' ' . Intel_Df::l(esc_html__('Change', $this->plugin_un), 'admin/config/intel/settings/form/default_tracking', $l_options);
      $items[] = '<tr>';
      $items[] = '<th>' . esc_html__( 'Default submission event/goal', $this->plugin_un ) . '</th>';
      $items[] = '<td>' . $value . '</td>';
      $items[] = '</tr>';

      $default_value = get_option('intel_form_track_submission_value_default', '');
      $items[] = '<tr>';
      $items[] = '<th>' . esc_html__( 'Default submission value', $this->plugin_un ) . '</th>';
      $items[] = '<td>' . (!empty($default_value) ? $default_value : Intel_Df::t('(default)')) . '</td>';
      $items[] = '</tr>';
    }
    $items[] = '</tbody>';
    $items[] = '</table>';

    $output = implode("\n", $items);
    echo $output;
  }
}

function intel_example() {
  return Intel_Example::instance();
}
global $intel_example;
$intel_example = intel_example();

/*
 * Implements hook_register_activation_hook()
 *
 * The code that runs during plugin activation.
 *
 * Initializes Intel's database schema update system
 */
function _intel_example_activation() {
  // plugin specific installation code.
  // initializes data for plugin when first installed
  require_once plugin_dir_path( __FILE__ ) . 'intel_example.install';
  intel_example_install();

  // check if Intel is active
  if (is_callable('intel_activate_plugin')) {
    // initializes Intel's database update management system
    intel_activate_plugin('intel_example');
  }
}
register_activation_hook( __FILE__, '_intel_example_activation' );

/**
 * Implements hook_register_deactivation_hook()
 *
 * The code that runs during plugin deactivation.
 */
function _intel_example_deactivate() {

}
register_deactivation_hook( __FILE__, '_intel_example_deactivate' );

/*
 * Implements hook_register_uninstall_hook()
 *
 * Runs when plugin is Deleted (uninstalled)
 */
function _intel_example_uninstall() {
  // plugin specific installation code.
  // remove plugin data from database before plugin is uninstalled
  require_once plugin_dir_path( __FILE__ ) . 'intel_example.install';
  intel_example_uninstall();
}
register_uninstall_hook( __FILE__, '_intel_example_uninstall' );