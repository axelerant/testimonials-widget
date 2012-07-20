=== Testimonials Widget ===
Contributors: comprock, j0hnsmith
Donate link: http://typo3vagabond.com/about-typo3-vagabond/donate/
Tags: testimonial, testimonials, quote, quotes, quotations, random quote, sidebar, widget
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 0.2.10

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
	* Specify a character limit to filter out longer quotes - 0 means no limit
	* Limit number of testimonials to pull at a time - 0 means no limit
* Allows for multiple widgets. Useful for using tags to keep widgets separated.
* Compatible with WordPress 3.0 multi-site functionality.

= Shortcode [testimonialswidget_list] =
* Options
	* hide_author - default show; hide_author=true
	* hide_source - default show; hide_source=true
	* ids - default none; ids=2 or ids="2,4,6"
	* limit - default no limit; limit=10
	* random - default newest first; random=true
	* tags - default none; tags=fire or tags="fire,water"
* [testimonialswidget_list] Examples
	* [testimonialswidget_list hide_author=true hide_source=true] 
	* [testimonialswidget_list tags="test,fun" limit=1]
	* [testimonialswidget_list ids=1]

= Need More Power? =
Check out the [Quotes Collection](http://wordpress.org/extend/plugins/quotes-collection/) plugin by [SriniG](http://profiles.wordpress.org/users/SriniG/profile/public/)

== Installation ==
1. Upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through the 'Plugins' menu in WordPress
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials' widget into the sidebar

== Frequently Asked Questions ==

= How do you include the actual testimonials for the widget? Where do I quote my customers? I mean, where do I enter the actual text? =

Checkout the first screenshot 1 at http://wordpress.org/extend/plugins/testimonials-widget/screenshots/ to see where to manage testimonials.

Basically, look down the left side of your WordPress admin area for the Testimonials sections. Click on that section link, then scroll down or click "Add new ttestimonial" to add quotes.

= What CSS applies to testimonials container? =

CSS class `testimonialswidget_testimonials` wraps all testimonials. Additionally, shortcode lists are wrapped by `testimonialswidget_testimonials testimonialswidget_testimonials_list`.

= What CSS applies to single testimonial container? =

CSS class `testimonialswidget_testimonial` wraps a single testimonial. Additionally, single shortcode list tems are wrapped by `testimonialswidget_testimonial testimonialswidget_testimonial_list`.

= How can I add the testimonials plugin to any where on the site? ie. somewhere other than the side bar like the contact page etc.? =

Use [testimonialswidget_list]. Usage examples are at the bottom of http://wordpress.org/extend/plugins/testimonials-widget/.

Look for `[testimonialswidget_list]`.

= How do I hide the comma after the author? =

Use CSS.
`span.testimonialswidget_join {
	display: none;
}`

= Testimonials widget is not showing or rotating =

The usual problem is that jQuery is included twice. Once by WordPress and again by a theme. Remove the jQuery version included by your theme and you should be fine.

= I'm not seeing any testimonials but the title =

If you're not seeing any testimonials, even when not using tags filter, you might try increasing the Character limit or setting it to '0' or 'none' in the widget box.

= How do I apply custom CSS to a testimonial widget? =

The easiest thing is to check the source code of your page with the widget and look for the testimonial widgets div container id tag. It'll be something like `id="testimonials_widget-3"`.

= How to stop testimonial text/author/source being cut off? =

Specify a larger minimum height in the testimonials widget, see screenshot 2.

= How to get rid of the quotation marks that surround the random quote? =

Open the testimonials-widget.css file that comes along with the plugin, scroll down and look towards the bottom for the comment "Uncomment the block below if you want to get rid of the quotation marks before and after the quote".

= How to change the random quote text color? =

Styling such as text color, font size, background color, etc., of the random quote can be customized by editing the testimonials-widget.css file or applying CSS like the following.

`.testimonialswidget_testimonial q {
	color: blue;
}`

= How can I style the shortcode testimonials? =

Using my own testimonials page, http://typo3vagabond.com/typo3-vagabond-testimonials/, as the example.

Each shortcode testimonial is wrapped by a `div` using classes `testimonialswidget_testimonial testimonialswidget_testimonial_list`. As such, to increase spacing between testimonials, try…

`
.testimonialswidget_testimonial_list {
padding-bottom: 1em;
}
`
Making the citation line a different color is a little trickier. The reason being is that applying a color to `.testimonialswidget_testimonial cite` will change the entire citation line in the widget display as well. To only change the shortcode testimonial citation color, try…

`
.testimonialswidget_testimonial_list cite {
color: blue;
}
`
If you're wanting to change only the source (URL/email address) color, then try.

`
.testimonialswidget_testimonial_list cite .testimonialswidget_source  {
color: purple;
}
`
Like wise, the author uses class `testimonialswidget_author` and join ", " uses class `testimonialswidget_join`.

= How to change the admin access level setting for the quotes collection admin page? =

Change the value of the variable `$testimonialswidget_admin_userlevel` on line 33 of the testimonials-widget.php file. Refer [WordPress documentation](http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table) for more information about user roles and capabilities.

== Screenshots ==

1. Admin interface (WordPress 3.2)
2. Add new testimonial
3. 'Testimonials' widget options (WordPress 3.2)
4. A testimonial in the sidebar
5. Edit testimonial
6. Testimonial shortcode results
7. Testimonial shortcode in post
	

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

= 0.2.10 =
* Character limit nows forces text truncation than preventing of testimonial to show
* Add option - Limit number of testimonials to pull at a time
* Sanitize widget variables
* Fix random_order issue on testimonials widget

= 0.2.9 =
* Require Editor role for managing Testimonials

= 0.2.8 =
* CSS testimonialswidget_testimonial_list fix #2

= 0.2.7 =
* CSS testimonialswidget_testimonial_list fix

= 0.2.6 =
* CSS updates for widgets and lists

= 0.2.5 =
* Add span.testimonialswidget_join for author , join text
* Add nl2br for testimonials display on a page

= 0.2.4 =
* Shortcode added - Thank you Hal Gatewood

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
* No nl2br when content is already using HTML
* TBD
