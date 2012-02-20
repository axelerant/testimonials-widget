=== Testimonials Widget ===
Contributors: comprock, j0hnsmith
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GGPVLS64ZMNV8
Tags: testimonials, quotes, quotations, random quote, sidebar, widget
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 0.2.3

Testimonials widget plugin allows you display testimonials in a widget on your WordPress blog.

== Description ==

Testimonial widget plugin allows you display testimonials in a widget on your WordPress blog. 

More than one widget can be displayed, each pulling from testimonial sets by using tags. Each displayed widget has its own identifier allowing for custom CSS styling.

You can make a simple client or portfolio rotator by using the testimonial field as the client or organization name, and the source field with the URL and tag as "portfolio". Then in the widget, show source and require tag "portfolio". 


**Features and notes**

* All testimonials (subject to filters) are in the html source so will be seen by google etc. 
* Testimony, author and source fields are clickable
* **Admin interface**: A nice admin interface to add, edit and manage testimonials. Details such as author and source of the quote, and attributes like tags and visibility, can be specified. The 'Testimonials' menu in the WP admin navigation leads to the admin interface.
* **Sidebar widget**: The Testimonials sidebar widget loads a testimonial then rotates through all available testimonials. Following is the list of options in the widget control panel:
	* Widget title
	* Option to show/hide author
	* Option to show/hide source
	* Choose refresh interval in seconds or set to 0 for static display
	* Choose random or sequential order for refresh
	* Show only quotes with certain tags
	* Show only quotes with all tags
	* Specify a character limit to filter out longer quotes
* Allows for multiple widgets. Useful for using tags to keep widgets separated.
* Compatible with WordPress 3.0 multi-site functionality.

= Need More Power? =
Check out the [Quotes Collection](http://wordpress.org/extend/plugins/quotes-collection/) plugin by [SriniG](http://profiles.wordpress.org/users/SriniG/profile/public/)

== Installation ==
1. Upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through the 'Plugins' menu in WordPress
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials' widget into the sidebar

== Frequently Asked Questions ==

= How to stop testimonial text/author/source being cut off? =

Specify a larger minimum height in the testimonials widget, see screenshot 2.

= How to get rid of the quotation marks that surround the random quote? =

Open the testimonials-widget.css file that comes along with the plugin, scroll down and look towards the bottom.

= How to change the random quote text color? =

Styling such as text color, font size, background color, etc., of the random quote can be customized by editing the testimonials-widget.css file.

= How to change the admin access level setting for the quotes collection admin page? =

Change the value of the variable `$testimonialswidget_admin_userlevel` on line 33 of the testimonials-widget.php file. Refer [WordPress documentation](http://codex.wordpress.org/Roles_and_Capabilities) for more information about user roles and capabilities.

== Screenshots ==

1. Admin interface (WordPress 3.2)
2. Add new testimonial
3. 'Testimonials' widget options (WordPress 3.2)
4. A testimonial in the sidebar
5. Edit testimonial
	

== Installation ==
1. Upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through the 'Plugins' menu in WordPress
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials' widget into the sidebar

== Upgrade Notice ==
* If you have no tags fields, deactivate and activate the plugin as normal to perform database upgrades

==Changelog==
= trunk =
-

= 0.2.3 =
* Allow testimonials to have multiple tags
* Show only quotes with all tags

= 0.2.2 =
* Show newest testimonials first in admin list by default
* Quick locallization
* Quotes Collection recommendation

= 2011-10-03: Version 0.2 =
* Multi-widget enabled
* Testimonial, author & source text are clickable automatically
* Allow 0 refresh to make widget static
* Allow pressing return when editing testimonial to save record

= 2011-08-12: Version 0.1 =
* initial release

== TODO ==
* Add shortcodes: list, single, by tag
