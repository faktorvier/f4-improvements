=== F4 Improvements ===
Contributors: faktorvier
Donate link: https://www.faktorvier.ch/donate/
Tags: improvements, enhancements, performance, security, frontend
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 1.9.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a lot of improvements and enhancements for WordPress and WP Rocket to your installation.

== Description ==

There are a few features that really should be in the WordPress core. [F4 Improvements](https://www.f4dev.ch) adds a few more features and a lot of improvements and enhancements to your WordPress installation. They improve the performance for your WordPress, make it more secure, add a few useful new features, removes unimportant stuff and ensure better usability. It also adds some improvements for the awesome caching plugin WP Rocket and WooCommerce.

= Features =

== WP Rocket ==
* Add action to toggle caching on/off to the WP Rocket admin menu
* Enable same cache for all users
* Disable mobile-specific cache (WP Rocket >= 3.16)
* Remove WP Rocket footprint comment
* Leave empty lines in .htaccess
* Ignore additional query strings
* WooCommerce: Clear product cache on stock change
* WooCommerce: Disable cart fragments cache

== WooCommerce ==
* Save Save ship_to_different_address
* Remove Adjacent Links
* Hide flatrates if free shipping
* Hide jumping embed loader in backend
* Set reply to email

== Yoast SEO ==
* Choose image size for social og images

== Front end ==

* Remove RSD Link
* Remove REST Output
* Remove oEmbed Discovery
* Remove wlwmanifest Link
* Remove Shortlinks
* Remove Adjacent Links
* Remove Feed Links
* Remove Generator Tag
* Remove Emojis
* Remove oEmbed Assets
* Remove Gutenberg Assets
* Remove Global Styles Assets
* Hide Author Pages
* Hide Admin Toolbar

== Back end ==

* Set default color theme for new users
* Disable WYSIWYG editor for specific post types
* Disable WYSIWYG media button for specific post types
* Disable Gutenberg editor for specific post types
* Keep taxonomy hierarchy in checklist
* Remove "Welcome to WP!" box from dashbord
* Remove "Quick Draft" box from dashboard
* Remove "News and Events" box from dashboard
* Remove "At a Glance" box from dashboard
* Remove "Activity" box from dashboard

== Media ==

* Change JPEG Quality
* Enable SVG Support
* Allow image upscaling (to ensure better image sizes handling)
* Normalize Upload Filename (lowercase, whitespaces, umlauts etc.)
* Use title as alt attribute on upload
* Disable scaling for big image sizes
* Remove specific default image sizes

== Core ==

* Disable Core Update Email
* Skip default plugins and themes on update
* Disable XML-RPC
* Disable Theme Editor
* Set phpmailer return path
* Disable admin email check

= Features overview =

* Improvements and enhancements for frontend, backend, media and wordpress core
* Easy to use
* Lightweight and optimized
* 100% free!

= Planned features =

* Add more useful WordPress improvements
* Polylang improvements
* WooCommerce improvements

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/f4-improvements` directory, or install the plugin through the WordPress plugins screen directly
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings -> Improvements screen to configure the plugin

== Frequently Asked Questions ==

= Is it really free? =

Yes, absolutely!

== Screenshots ==

1. Front end improvements
2. Back end improvements
3. Media improvements
4. System improvements
5. WP Rocket improvements
6. WooCommerce improvements

== Changelog ==

= 1.9.0 =
* Add option to disable mobile-specific cache for WP Rocket
* Support WooCommerce 8.9

= 1.8.2 =
* Support WooCommerce 8.7
* Support WordPress 6.5

= 1.8.1 =
* Fix possible XSS vulnerability
* Support WooCommerce 8.6
* Support WordPress 6.4

= 1.8.0 =
* Add option to disable admin email check
* Declare custom_order_tables compatibility
* Support WooCommerce 8.1
* Support WordPress 6.3

= 1.7.3 =
* Optimize for PHP 8.1
* Support WooCommerce 7.4

= 1.7.2 =
* Support WooCommerce 7.1
* Support WordPress 6.1

= 1.7.1 =
* Update www.f4dev.ch links
* Support WooCommerce 6.8

= 1.7.0 =
* Add option to remove global styles from the front end
* Support WooCommerce 6.6

= 1.6.3 =
* Support WordPress 6.0

= 1.6.2 =
* Fix bug with Yoast SEO
* Remove trailing php tags

= 1.6.1 =
* Support WordPress 5.9

= 1.6.0 =
* Add Yoast SEO module and og image size option
* Support WooCommerce 6.1

= 1.5.0 =
* Add option to disable scaling for big image sizes
* Support WordPress 5.8

= 1.4.0 =
* Add option to skip default plugins and themes on update
* Add option to hide jumping embed loader in backend
* Support WooCommerce 5.1

= 1.3.3 =
* Support WordPress 5.7

= 1.3.2 =
* Support WordPress 5.6

= 1.3.1 =
* WP Rocket: Don't generate and flush critical css if cache is disabled

= 1.3.0 =
* Add WooCommerce improvements
* Add new "Modern" color theme to theme selection
* Remove all default values for new installations
* System: Add option to set phpmailer return path

= 1.2.1 =
* Update hook priority for rocket_remove_empty_lines
* Support WordPress 5.5

= 1.2.0 =
* Add WP Rocket improvements
* Rework load and init hooks

= 1.1.0 =
* Add option to set alt attribute to title on upload
* Add option to keep taxonomy hierarchy in checklist
* Remove option to normalize attachment titles
* Update default values
* Update german translations

= 1.0.0 =
* Initial stable release
