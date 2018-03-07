<?php
/**
 * Intelligence Example Addon Intel Event
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
class Intel_Example_Addon_Intel_Event extends Intel_Example_Addon {

  /**
   * constructor.
   *
   */
  public function __construct() {

    parent::__construct();

    /*
     * Intelligence hooks
     */

    // Register hook_intel_intel_script_info()
    add_filter('intel_intel_script_info', array( $this, 'intel_intel_script_info'));

    // Register intel_intel_event_info()
    add_filter('intel_intel_event_info', array( $this, 'intel_intel_event_info'));
  }

  /**
   * Implements hook_intel_intel_script_info()
   *
   * Adds AddThis tracking script to the site.
   */
  function intel_intel_script_info($info = array()) {
    $info[$this->plugin_un] = array(
      // Human readable title of the script
      'title' => $this->plugin_info['plugin_title'],
      // Description of the script
      'description' => __('Description of this plugins scripts'),
      // path to javascript file
      'path' => $this->url . 'js/intel_example_addon.io_intel_event.js',
      // if enabled by default
      'enabled' => 1,
      // if user can enable/disable script in UI
      'selectable' => 0,
    );

    return $info;
  }

  /**
   * Implements hook_intel_intel_event_info()
   *
   * Adds intel events
   */
  function intel_intel_event_info($event = array()) {

    /*
     * Standard intel events can be triggered using jQuery style selectors and
     * event actions.
     *
     * Standard events are designed to be pushed using io('event', eventDef);
     */

    // Standard intel event
    // Event mode, goal and value and availablity are overridable.
    // This is the most common overridable mode.
    $event[$this->plugin_un . '_a'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('A click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('A click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example A', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // jQuery selector for the event
      'selector' => '.intel-example-addon.example-a',
      // jQuery
      'on_event' => 'click',
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => array(
      ),
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    // Standard intel event
    // Event is fully overridable in admin UI.
    $event[$this->plugin_un . '_b'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('B click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('B click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example B', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // jQuery selector for the event
      'selector' => '.intel-example-addon.example-b',
      // jQuery
      'on_event' => 'click',
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => 1,
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    // Standard intel event
    // Nothing is overridable in admin UI.
    $event[$this->plugin_un . '_c'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('C click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('C click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example C', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // jQuery selector for the event
      'selector' => '.intel-example-addon.example-c',
      // jQuery
      'on_event' => 'click',
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => 0,
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    // Standard intel event
    // Event mode, goal and value and availablity are overridable along with
    // the selector and category.
    $event[$this->plugin_un . '_d'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('D click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('D click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example D', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // jQuery selector for the event
      'selector' => '.intel-example-addon.example-d',
      // jQuery
      'on_event' => 'click',
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => array(
        'selector' => 1,
        'category' => 1,
      ),
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    /**
     * Advanced intel events don't use standard jQuery style selectors. Typically
     * these events are implemented in a custom JavaScript file and/or processed
     * server side in PHP.
     *
     * Advanced intel events are processed outside of standard io event processes.
     * The event may still be pushed using io('event') so settings are available
     * in _ioq.eventDefs.
    */


    // Advanced intel event.
    // Event mode, goal and value and availablity are overridable along with
    // the selector and category.
    // The eventDef is pushed to io using config to create a client side eventDef
    // without any triggering or binding side effects.
    $event[$this->plugin_un . '_e'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('E click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('E click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example E', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // This will push event so it is saved in eventDefs but not trigger it using
      // standard intel event processing.
      'on_event' => 'none',
      // Provides admin UI feedback as to how the GA Events are set
      'ga_event_auto' => array(
        'description' => Intel_Df::t('Automatically configured by Example Addon script.'),
      ),
      // Provides admin UI feedback  as to how the intel event is triggered
      'trigger_auto' => array(
        'description' => Intel_Df::t('Triggered when a link with the classes .intel-example-addon.example-e is clicked.'),
      ),
      // Provides admin UI feedback for availability options
      'availability_auto' => array(
        'description' => Intel_Df::t('Automatically enabled on all pages.'),
      ),
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => array(
      ),
      // Tells intelligence to pass eventDef in config rather than 'event' push
      //'config' => 1,
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    // Advanced intel event.
    // Event mode, goal and value and availablity are overridable along with
    // the selector and category.
    $event[$this->plugin_un . '_e'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('E click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('E click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example E', $this->plugin_un),
      // Mode of event: '': Standard event, 'value': Valued event, 'goal': Goal event
      'mode' => 'valued',
      // Default value of the event sent to Google Analtyics
      'value' => 10,
      // This will push event so it is saved in eventDefs but not trigger it using
      // standard intel event processing.
      'on_event' => 'none',
      // Provides admin UI feedback as to how the GA Events are set
      'ga_event_auto' => array(
        'description' => Intel_Df::t('Automatically configured by Example Addon script.'),
      ),
      // Provides admin UI feedback  as to how the intel event is triggered
      'trigger_auto' => array(
        'description' => Intel_Df::t('Triggered when a link with the classes .intel-example-addon.example-e is clicked.'),
      ),
      // Provides admin UI feedback for availability options
      'availability_auto' => array(
        'description' => Intel_Df::t('Automatically enabled on all pages.'),
      ),
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => array(
      ),
      // Tells intelligence to pass eventDef in config rather than 'event' push
      //'config' => 1,
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    // Advanced intel event.
    // Event mode, goal and value and availablity are overridable along with
    // the selector and category.
    $event[$this->plugin_un . '_f'] = array(
      // Administrative title of event
      'title' => $this->plugin_info['plugin_title'] . ' ' . __('F click', $this->plugin_un),
      // Event name to be sent to Google Analytics
      'category' => __('Example', $this->plugin_un) . ' ' . __('F click', $this->plugin_un),
      // Administrative description of even
      'description' => __('When visitor clicks on Example F', $this->plugin_un),
      // This will push event so it is saved in eventDefs but not trigger it using
      // standard intel event processing.
      'on_event' => 'none',
      // Provides admin UI feedback as to how the GA Events are set
      'ga_event_auto' => array(
        'description' => Intel_Df::t('Automatically configured by Example Addon script to count clicks on Example F.'),
      ),
      // Provides admin UI feedback  as to how the intel event is triggered
      'trigger_auto' => array(
        'description' => Intel_Df::t('Triggered when a link with the classes .intel-example-addon.example-f is clicked.'),
      ),
      // Provides admin UI feedback for availability options
      'availability_auto' => array(
        'description' => Intel_Df::t('Automatically enabled on all pages.'),
      ),
      // if event is enabled
      'enable' => 1,
      // lists the elements of the event that can be customized in admin UI
      'overridable' => 0,
      // Tells intelligence to pass eventDef in config rather than 'event' push
      //'config' => 1,
      // The plugin_un the event belongs to
      'plugin_un' => $this->plugin_un,
    );

    return $event;
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

    $examples = array('a', 'b', 'c', 'd', 'e', 'f');

    $content = '';
    $content = '<div class="intel-example-addon-demo">';

    foreach ($examples as $example_name) {
      $text = __('Example', $this->plugin_un) . ' ' . strtoupper($example_name);
      $img_vars = array(
        'path' => 'http://via.placeholder.com/1280x192&text=' . $text,
        'title' => $text,
        'width' => 1280,
        'height' => 192,
      );
      $img = Intel_Df::theme('image', $img_vars);

      $l_options = array(
        'fragment' => '',
        'html' => 1,
        'attributes' => array(
          'title' => $text,
          'class' => array(
            'intel-example-addon',
            'example-' . $example_name,
          ),
        ),
      );
      $content .= Intel_Df::l($img, 'javascript:void(0);', $l_options);
    }



    $posts["$id"] = array(
      'ID' => $id,
      'post_type' => 'page',
      'post_title' => 'Demo Example',
      'post_content' => $content,
      'intel_demo' => array(
        'url' => 'intelligence/demo/' . $this->plugin_un,
        // don't allow users to override demo page content
        'overridable' => 0,
      ),
    );

    $content = '</div>';

    return $posts;
  }

}