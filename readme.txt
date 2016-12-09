=== f(x) Login Notification ===
Contributors: turtlepod
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TT23LVNKA3AU2
Tags: comments, spam
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send notification via email when a user successfully logged-in in your site.

== Description ==

f(x) Login Notification plugin is a security utility plugin to let you know who log to your site with other useful information such as user information and IP address via configurable email message.

After you install and activate this plugin, you can configure via Login Notification settings page in "Settings > Login Notification".

**Features:**

1. Be the first to know when someone login to your site (or hack your site).
1. Enable/disable admin notification.
1. Exclude user roles for admin notification.
1. Configurable email template.
1. Extendable and fully documented (read the Dev Notes).
1. The GPL v2.0 or later license. :) Use it to make something cool.
1. Support available at [Genbu Media](https://genbu.me/contact-us/).


== Installation ==

1. Navigate to "Plugins > Add New" Page from your Admin.
2. To install directly from WordPress.org repository, search the plugin name in the search box and click "Install Now" button to install the plugin.
3. To install from plugin .zip file, click "Upload Plugin" button in "Plugins > Add New" Screen. Browse the plugin .zip file, and click "Install Now" button.
4. Activate the plugin.
5. Navigate to "Settings > Login Notification" page in your admin panel to configure the plugins.

== Frequently Asked Questions ==

= Where is the settings ? =

The settings is in "Settings > Login Notification".

== Screenshots ==

1. Settings screenshot

== Changelog ==

= 1.0.0 - 7 Jan 2015 =
* Add readme. Prepare for WordPress.org
* Remove autohosted.com updater.
* Fix typo in settings.
* Update language files.
* Add `fx_login_nf_from_email` and `fx_login_nf_from_name` filter.

= 0.1.0 =
* First relase.

== Upgrade Notice ==

= 1.0.0 =
Maintenance relase.

== Dev Notes ==

Notes for developer: 

= Github =

Development of this plugin is hosted at [GitHub](https://github.com/turtlepod/fx-login-notification). Pull request and bug reports are welcome.

= Options =

This plugin save the options in single option name: `fx-login-nf-admin`.

= Hooks =

List of hooks available in this plugin:

**filter:** `fx_login_nf_user_roles` (array)

List of user roles in the checkbox option.

**filter:** `fx_login_nf_email_subject_template_default` (string)

The default email subject template. This string is translateable. To translate it in your language, translate the plugin and do not use this filter.

**filter:** `fx_login_nf_email_content_template_default` (string)

The default email content template. This string is translateable. To translate it in your language, translate the plugin and do not use this filter.

**filter:** `fx_login_nf_email_template_note` (string)

The template tags note in the settings page. Use this if you want to remove and add your own template tags in the email. This string is translateable. To translate it in your language, translate the plugin and do not use this filter.

**filter:** `fx_login_nf_parse_template` (string)

If you create your own tag, you can pass it in this filter.

**filter:** `fx_login_nf_from_email` (string)

"From Email Address" used to send the notification. As default it will use "noreply@{your site domain}".

**filter:** `fx_login_nf_from_name` (string)

"From Email Address Name" used to send the notification. As default it will use "{your site name} Notification". This string is translateable.

**action:** `fx_login_nf_before_send_mail`

Hook before email notification is sent.

**action:** `fx_login_nf_after_send_mail`

Hook after email notification is sent.


