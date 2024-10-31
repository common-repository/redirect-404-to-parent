=== Redirect 404 to parent ===
Contributors: MooveAgency
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R3WRTDZMKZ6VY
Tags: redirect, 404, parent, URL, broken
Requires at least: 4.3
Tested up to: 6.3
Stable tag: 1.4.1
Requires PHP: 5.6
License: GPLv2

This plugin helps you redirect any 404 request to the parent URL.

== Description ==

**Redirect any 404 request to the parent URL with this incredibly powerful, easy-to-use, well supported and 100% free WordPress cookie plugin.**

Simply put, the plugin performs the following: when there is a 404 page found such as /about/sample-url then the plugin will automatically redirect the visitor to /about/

It's a very powerful plugin that saves you many hours specifying individual redirects for every 404 page. With our plugin, you can define one simple rule that will cover all child pages that produce 404 errors.

**Features**

* You can defined the BASE URL - this is the URL that will be served as a starting point.
* You can define the type of the redirection done by WordPress (302, 301, etc.).
* You can add as many rules you want and easily delete them if you don't need them anymore
* The plugin checks if you already added a rule based on the slug, so you won't add the same rule twice.
* The plugin checks if the URL you are setting up as a BASE exists in WordPress as a post or page, so you're not creating an erroneous URL base.
* You can see a log of the 404 redirected by the plugin (if the plugin registers any 404 errors), so you can easily identify what URLs are generating the 404 errors.
* If there are more than 10 rows in the 404 log/statistics, you can download the whole log for an URL in CSV format.

**Example Use Case**

* Base URL (set up in this plugin as a rule): http://yourdomain.com/sample-page/
* Target URL: http://yourdomain.com/sample-page/non-existing-page

In this case if a visitor tries to access the TARGET URL, WordPress returns a 404 error/page by default because the page/post doesn't exist.

Our plugin will automatically redirect the visitor to http://yourdomain.com/sample-page/ instead of letting the visitor end up on a 404 page.

### About us

[Moove Agency](https://www.mooveagency.com/) is a premium supplier of quality WordPress plugins, services and support. [Visit our site](https://www.mooveagency.com/services/wordpress-development/) to learn more.


== Installation ==
1. Upload the plugin files to the plugins directory, or install the plugin through the WordPress plugins screen directly by searching for the plugin by name.
2. Activate the plugin through the \'Plugins\' screen in WordPress.
3. Use the Settings -> **Moove redirect 404** page to configure the plugin and add rules.

== Screenshots ==

1. Adding redirect rules.
2. View / Remove redirect rules & statistics

== Changelog ==
= 1.4.1 =
* Statistic options disabled by default

= 1.4.0 =
* General Settings added to manage statistics status

= 1.3.4 =
* Add new redirect CURL function replaced to wp_remote_get

= 1.3.3 = 
* WP 6.2 Compatibility

= 1.3.2 =
* WP 6.0 Compatibility

= 1.3.1 =
* Admin navigation improved

= 1.3.0. =
* Add / Remove redirect form improved

= 1.2.2. =
* Minor CSS fix

= 1.2.1. =
* Hook added to disable stats

= 1.2.0. =
* Improved admin layout
* Compatibility with php 7.x
* Multisite compatibility added

= 1.0.2. =
* Added donation box

= 1.0.1. =
* Fixed PHP warnings

= 1.0.0. =
* Initial release of the plugin.
