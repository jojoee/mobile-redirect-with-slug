=== Mobile Redirect With Slug ===

Contributors: jojoee
Donate link: 
Tags: navigation, mobile, redirect, url, slug, page
Requires at least: 3.0.1
Tested up to: 4.5.3
Stable tag: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Redirect to mobile site (with or without slug url)

== Description ==

Simple plugin that allows you to redirect mobile's user to mobile site (with or without slug url)

For example, it redirects

* `desktop.com/` to `mobile.com/`
* `desktop.com/first/` to `mobile.com/first/`
* `desktop.com/second-post/` to `mobile.com/second-post/`

== Installation ==

1. Install the plugin via plugin's dashboard or download and upload this plugin into `wp-content/plugins` directory
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to `Settings > Mobile Redirect` or click `Settings` on plugin's dashboard
4. Settings it
5. Click `Save Changes`

== Frequently Asked Questions ==

= How to use it =
Activate the plugin on your plugin dashboard (`/wp-admin/plugins.php`)

= How it work =
Detect `mobile` by using `wp_is_mobile` function then redirect it

== Screenshots ==

1. Example 1 (screenshot-1.jpg)
2. Example 2 (screenshot-2.jpg)

== Changelog ==

= 1.0.1 (16 Aug 2016) =
* Update meta

= 1.0.0 (13 Aug 2016) =
* First release

== Others ==

= Notes =
* PHP Coding Standards: [WordPress](https://codex.wordpress.org/WordPress_Coding_Standards)
* Javascript Coding Standards: [Airbnb](https://github.com/airbnb/javascript)
* DocBlock Standard: [phpDocumentor](https://phpdoc.org/)
* 2 spaces for indent
* Repository: [mobile-redirect-with-slug](https://github.com/jojoee/mobile-redirect-with-slug)

= Features =
* [ ] Cookie support
* [ ] Redirect for some template (e.g. homepage)
* [ ] Option to not redirect tablet by using [Mobile Detect](https://github.com/serbanghita/Mobile-Detect/)
* [ ] i18n support

= Thanks =
* [gulp-connect-php](https://github.com/micahblu/gulp-connect-php)
