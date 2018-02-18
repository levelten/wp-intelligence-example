<?php

/**
 * @file
 * Included to assist in initial setup of plugin
 *
 * @since      1.0.0
 *
 * @package    Intel
 */

// check if intel_setup is available, if not include.
if (!is_callable('intel_setup')) {
	include_once intel_example_addon()->dir . 'intel_com/intel.setup.inc';
}

class Intel_Example_Addon_Setup extends Intel_Setup {

	public $plugin_un = 'intel_example_addon';

	/*
	 * Include any methods from Intel_Setup you want to override
	 */

}

function intel_example_addon_setup() {
	return Intel_Example_Addon_Setup::instance();
}
intel_example_addon_setup();
