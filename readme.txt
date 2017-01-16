=== Ultimate Member Profile Tabs ===
Contributors: plusplugins
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TY7RLMFUCPVE4
Tags: ultimate member, ultimatemember, user profiles, tabs, profile tabs, custom tabs, user tabs, member tabs
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 1.2.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add custom profile tabs to your Ultimate Member user profiles.

== Description ==

**Features:**

For each custom tab you can:

- Set the name and icon of the tab
- Set the position of tab
- Control which user roles have the tab
- Control which user roles can see the tab
- Make the tab private (only visible to profile owner)
- Make the profile nav bar button redirect to a custom URL
- Create subnavs

You can also output any field that a member has added to their profile using the following shortcode:

`[pp-tabs field=meta_key]`

or, to display the field label as well:

`[pp-tabs field=meta_key label=1]`

**Pro Version**

Need to integrate a UM form in your custom tab? Take a look at [Ultimate Member Tabs Pro](https://plusplugins.com/downloads/ultimate-member-tabs-pro/). Split your monolithic UM profile form into neat, easy-to-navigate tabs!

**More UM extensions**

Want to extend your Ultimate Member site even more? Visit [PlusPlugins](https://plusplugins.com) for more Ultimate Member extensions.

== Installation ==

This plugin requires the Ultimate Member plugin.

1. Upload the plugin to your wordpress site
2. Activate
3. Done!

== Frequently Asked Questions ==

= Where do I add a custom tab? =

In the Wordpress Admin menu, go to Ultimate Member -> Profile Tabs

= How do I create a subnav? =

When creating a new tab, select a post parent. The new tab will be a subnav of the parent tab.

= How do I build dynamic shortcodes? =

You need a nested shortcode, using ours to get the profile id: `[pp-tabs field=id]`

== Screenshots ==

1. The profile tab content edit screen
2. Privacy and other options

== Changelog ==

= 1.2.7 =
* Fix: Plugin dependency notice issue
* Update: Confirm WordPress 3.6.1 compatibilty

= 1.2.6 =
* New: Use our shortcode to get profile ID and build dynamic shortcodes

= 1.2.5 =
* Fix: Tabs incorrectly hidden due to previous update

= 1.2.4 =
* New: Enable private tabs to be viewed by specific user roles

= 1.2.3 =
* New: Add option to force position to front

= 1.2.2 =
* Update: Add link to the new Tabs Pro plugin

= 1.2.1 =
* Update: CMB2 library
* Tweak: Save a few DB queries

= 1.2.0 =
* New: Support for subnavs

= 1.1.2 =
* Fix: Add option to add redirect URL for tab

= 1.1.1 =
* Fix: Potential bug

= 1.1 =
* New: Rewrite with CMB2
* Fix: Remove limit of 5 custom tabs
* Added: Shortcode to output any user field

= 1.0.0 =
* Initial release
