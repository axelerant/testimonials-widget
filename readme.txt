=== Testimonial Widget ===
Contributors: j0hnmsith
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GGPVLS64ZMNV8
Tags: testimonials, quotes, quotations, random quote, sidebar, widget
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

Testimonial widget plugin allows you display testimonials in a sidebar on your WordPress blog.

== Description ==

Testimonial widget plugin allows you display testimonials in a sidebar on your WordPress blog


**Features and notes**

* All testimonials (subject to filters) are in the html source so will be seen by google etc. 
* **Admin interface**: A nice admin interface to add, edit and manage testimonials. Details such as author and source of the quote, and attributes like tags and visibility, can be specified. The 'Testimonials' menu in the WP admin navigation leads to the admin interface.
* **Sidebar widget**: The Testimonials sidebar widget loads a testimonial then rotates through all available testimonials. Following is the list of options in the widget control panel:
	* Widget title
	* Option to show/hide author
	* Option to show/hide source
	* Choose random or sequential order for refresh
	* Show only quotes with certain tags
	* Specify a character limit and filter out bigger quotes
* Compatible with WordPress 3.0 multi-site functionality.

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

1. Admin interface (in WordPress 3.2)
2. 'Testimonials' widget options (WordPress 3.2)
3. A testimonial in the sidebar
	

== Installation ==
1. Upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through the 'Plugins' menu in WordPress
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials' widget into the sidebar

==Changelog==
* **2011-08-12: Version 0.1**
	* initial release
