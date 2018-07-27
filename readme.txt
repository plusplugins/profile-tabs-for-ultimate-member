=== Ultimate Member Profile Tabs ===
Contributors: plusplugins
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TY7RLMFUCPVE4
Tags: ultimate member, ultimatemember, user profiles, tabs, profile tabs, custom tabs, user tabs, member tabs, reorder tabs, sort tabs, membership tabs, extra tabs
Requires at least: 4.1
Tested up to: 4.9.7
Requires PHP: 5.6
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add custom profile tabs to your Ultimate Member user profiles.

== Description ==

Easily add custom profile tabs to your Ultimate Member user profiles.

This plugin requires the [Ultimate Member](https://wordpress.org/plugins/ultimate-member/) plugin.

= Features =

For each custom tab you can:

* Set the name and icon of the tab
* Set the position of tab
* Control which user roles have the tab
* Control which user roles can see the tab
* Make the tab private (only visible to profile owner)
* Make the profile nav bar button redirect to a custom URL
* Create subnavs

You can also output any field that a member has added to their profile using the following shortcode:

`[pp-tabs field=meta_key]`

or, to display the field label as well:

`[pp-tabs field=meta_key label=1]`

= Pro Version =

Need to integrate a UM form in your custom tab? Take a look at [Ultimate Member Tabs Pro](https://plusplugins.com/downloads/ultimate-member-tabs-pro/). Split your monolithic UM profile form into neat, easy-to-navigate tabs!

= More UM extensions =

Want to extend your Ultimate Member site even more? Visit [PlusPlugins](https://plusplugins.com) for more Ultimate Member extensions.

= Important: Upgrade Notice for 2.0.0 =

This is an overhaul of changes *without* backwards compliance to make this plugin compatible with the "Ultimate Member – User Profile & Membership Plugin version 2.0.4". Please take **backup** measures before upgrading.

= Feedback & Support =

If you like this plugin, please [rate and/or review](https://wordpress.org/support/plugin/profile-tabs-for-ultimate-member/reviews/) it. If you have ideas on how to make the plugin even better or if you have found any bugs, please report these in the [Support Forum](https://wordpress.org/support/plugin/profile-tabs-for-ultimate-member/) or in the [GitHub repository](https://github.com/plusplugins/profile-tabs-for-ultimate-member/issues).

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

= 2.1.1 =
* Fix: Warning: array_intersect() Argument #1 is not an array while logged out

= 2.1.0 =
* Fix: User Role issue with tabs
* Update: Confirm UM 2.0 User Roles Logic compatibility
* Update: Confirm WordPress 4.9.7 compatibility

= 2.0.2 =
* Fix: Timeout issue when UM Profile Form shortcode is used

= 2.0.0 =
* Update: Confirm UM 2.0 compatibility
* Update: Confirm WordPress 4.9.5 compatibility

= 1.2.8 =
* New: Support for localization
* New: Confirm WPML compatibility
* Update: Confirm WordPress 4.7.2 compatibility

= 1.2.7 =
* Fix: Plugin dependency notice issue
* Update: Confirm WordPress 3.6.1 compatibility

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

== Upgrade Notice ==

= 2.0.0 =

This is an overhaul of changes *without* backwards compliance to make the plugin compatible with the plugin "Ultimate Member – User Profile & Membership Plugin". Please take backup measures before upgrading.
