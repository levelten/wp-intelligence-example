=== Intelligence Example Addon ===
Contributors: tomdude
Donate link: getlevelten.com/blog/tom
Tags: analytics, contact, contact form, form, google analytics, marketing, metrics, stats, tracking, web form
Requires at least: 4.5
Tested up to: 4.9.2
Stable tag: 1.0.0.0-dev
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Example Intelligence Addon. Good starting structure for Intelligence add-ons.

== Description ==

Download the plugin.

The examples are for creating an addon to extend the fictitious Foo Bar plugin

Foo Bar plugin info:
Title: Foo Bar
Slug: foo-bar
Main plugin file: foo-bar.php

Create a plugin title and plugin unique name (plugin_un) for the addon combining naming for Intelligence and the extends
plugin.

Example
Title: Intelligence Foo Bar
Plugin_un: intel_foo_bar

Change any file names including 'intel_example' to the new plugin_un.

Examples:
intel_example.php -> intel_foo_bar.php
intel_example.install -> intel_foo_bar.install
intel_example.setup -> intel_foo_bar.setup
admin/intel_example.setup.inc -> admin/intel_foo_bar.setup.inc

Search and replace in the new plugin directory

Human readable title
Intelligence Example Addon -> Intelligence Foo Bar

Plugin unique name
intel_example_addon -> intel_foo_bar

Class names
Intel_Example_Addon -> Intel_Foo_Bar

Contants
INTEL_EXAMPLE_ADDON -> INTEL_FOO_BAR

Review:
Intel_Example_Addon::intel_plugin_info()

Example

== Installation ==

=== Install Files Within WordPress ===

1. Visit 'Plugins > Add New'
1. Search for 'Intelligence Example'
1. Activate Intelligence Example from your Plugins page.
1. Go to "plugin setup" below.

=== Install Files Manually ===

1. Download the [Intelligence Example plugin](https://wordpress.org/plugins/intelligence-example) and the [Intelligence plugin](https://wordpress.org/plugins/intelligence)
1. Add `intelligence-example` and `intelligence` folders to the `/wp-content/plugins/` directory
1. Activate the Intelligence Example plugin through the 'Plugins' menu in WordPress
1. Go to "plugin setup" below.

=== Plugin Setup ===

1. You should see a notice at the top of the Plugins page. Click "Setup plugin" to launch the setup wizard. You can also launch the wizard from the Contact Form 7 settings, 'Contact > Intelligence'.
1. Go through the setup wizard and set up the plugin for your site.
1. You're done!

=== Popular options ===
Track and manage Intelligence goals and events in existing Google Analytics tracking ID:

1. Go to "Intelligence > Settings"
1. Under "Tracking settings" fieldset, open the "Base Google Analytics profile" fieldset
1. If base profile is not set, click "Set profile" link to set your existing tracking ID
1. Check "Track Intelligence events & goals in base profile"
1. Check "Sync Intelligence goals configuration to base profile"
1. Click "Save settings" button at bottom of page

Embed Google Analytics tracking code if site does not already embed tracking code through some other method.

1. Go to "Intelligence > Settings"
1. Under "Tracking settings" fieldset, open the "Advanced" fieldset.
1. For "Include Google Analytics tracking code" select the "Analytics" option
1. Click "Save settings" button at bottom of page

== Screen Shots ==



== Changelog ==

= 1.0.0 =
* Initial version

== Upgrade Notice ==

= 1.0.0 =
No notices