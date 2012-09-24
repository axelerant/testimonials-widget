=== Testimonials Widget ===
Contributors: comprock
Donate link: http://typo3vagabond.com/about-typo3-vagabond/donate/
Tags: ajax, business, client, commendation, custom post type, customer, quotations, quotations widget, quote, quote shortcode, quotes, quotes collection, random, random content, random quote, recommendation, reference, shortcode, sidebar, sidebar quote, testimonial, testimonial widget, testimonials, testimonials widget, testimony, widget
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 2.0.4
License: GPLv2 or later

Testimonials Widget plugin allows you to display rotating content, portfolio, quotes, showcase, or other text with images on your WordPress blog.


== Description ==

Testimonials Widget plugin allows you to display rotating content, portfolio, quotes, showcase, testimonials, or other text with images on your WordPress blog. You can insert Testimonials Widget content via shortcode, theme functions, or widgets with category and tag selections and having multiple display options to include random or specific ordering.

More than one Testimonials Widget section can be displayed at a time. Each Testimonials Widget separately pulls from the `testimonials-widget` custom post type. Additionally, with shortcodes and theme functions, you can display a short or long list or rotation of testimonials. Also, each Testimonal Widget has its own CSS identifier for custom styling.

Through categories and tagging, you can create organizational structures based upon products, projects and services via categories and then apply tagging for further classificaton. As an example, you might create a Portfolio category and then use tags to identify web, magazine, media, public, enterprise niches. You can then configure the Testimonial Widget to show only Portfolio testimonials with the public and enterprise tags. In another Testimonial Widget, you also select only Portfolio testimonials, but then allow web and media tags.

= Features =
* Admin interface to add, edit and manage testimonials
* Auto-migration from old custom table to new custom post type
	* Company, URL and email details are attempted to be identified and placed properly
	* Public testimonials are saved as Published. Non-public, are marked as Private.
* Compatible with WordPress multi-site
* Display testimonials directly in template via theme function
* Editors and admins can edit testimonial publisher
* Fields for source, testimonial, email, company and URL
* Image, Gravatar, category and tag enabled
* Localizable - see `languages/testimonials-widget.pot`
* Multiple widget capable
* Rotation JavaScript in footer than body
* Testimonial supports HTML
* Testimonial, email, and URL fields are clickable
* Testimonials Widget widget displays static and rotating testimonials 
* [testimonialswidget_list] shortcode
* [testimonialswidget_widget] shortcode
* Widget options
	* Title
	* Category filter
	* Tags filter
	* Require all tags
	* Advanced options
	* Hide image?
	* Hide source?
	* Hide email?
	* Hide company?
	* Hide URL?
	* Character limit
	* IDs filter
	* Limit
	* Minimum Height
	* ORDER BY
	* ORDER BY Order
	* Random order
	* Refresh Interval

= Shortcodes [testimonialswidget_list] [testimonialswidget_widget] =
* Shortcode Options
	* `category` - default none; category=product or category="product,services"
	* `char_limit` - default none; char_limit=200
	* `hide_company` - default show; hide_company=true
	* `hide_email` - default show; hide_email=true
	* `hide_image` - default show; hide_image=true
	* `hide_source` - default show; hide_source=true
	* `hide_url` - default show; hide_url=true
	* `ids` - default none; ids=2 or ids="2,4,6"
	* `limit` - default 25; limit=10
	* `order` - default DESC; orderby=ASC
	* `orderby` - default ID; order=post_date
	* `random` - default newest first; random=true (overrides `order` and `orderby`)
	* `tags_all` - default OR; tags_all=true
	* `tags` - default none; tags=fire or tags="fire,water"
* [testimonialswidget_list] Examples
	* [testimonialswidget_list hide_source=true hide_url=true] 
	* [testimonialswidget_list tags="test,fun" random=true]
	* [testimonialswidget_list category="product" tags="widget" limit=5]
	* [testimonialswidget_list ids="1,11,111"]
* [testimonialswidget_widget] Examples
	* [testimonialswidget_widget]
	* [testimonialswidget_widget tags="sometag" random=true]
	* [testimonialswidget_widget category="product" order="asc"]

= Theme Function `testimonialswidget_list()` =
* `<?php echo testimonialswidget_list( $args ); ?>`
* `$args` is an array of the above [testimonialswidget_list] shortcode options

= Theme Function `testimonialswidget_widget()` =
* For calling the widget with rotation code into your theme directly
* `<?php echo testimonialswidget_widget( $args, $number ); ?>`
* `$args` is an array of the above [testimonialswidget_list] shortcode options
* `$number` should be an arbitrarily number that doesn't conflict with existing actual Testimonial Widgets widget IDs

= Notes =
* Default image size is based upon Thumbnail size in Media Settings 
* Gravatar image is configured in the Avatar section of Discussion Settings

= Languages =
You can translate this plugin into your if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://typo3vagabond.com/contact-typo3vagabond/) to the plugin author.

= Background & Thanks =
Version 2.0.0 of Testimonials Widget is a complete rewrite based upon a composite of ideas from user feedback and grokking the plugins [Imperfect Quotes](http://www.swarmstrategies.com/imperfect-quotes/), [IvyCat Ajax Testimonials](http://wordpress.org/extend/plugins/ivycat-ajax-testimonials/), [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/), and [TB Testimonials](http://travisballard.com/wordpress/tb-testimonials/). Thank you to these plugin developers for their efforts that have helped inspire this rewrite.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring metaboxes for your posts, pages or custom post types a snap.

Prior to version 2.0.0, this plugin was a fork of [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/) by [Srini G](http://wordpress.org/support/profile/SriniG) with additional contributions from [j0hnsmith](http://wordpress.org/support/profile/j0hnsmith), [ChrisCree](http://wordpress.org/support/profile/ChrisCree) and [comprock](http://wordpress.org/support/profile/comprock).


== Installation ==

1. Upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through the 'Plugins' menu in WordPress

= Usage =
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials' widget into the desired widget area
1. Configure the Testimonial Widget to select quotes and display as needed
1. Alternately, use the `[testimonialswidget_list]` or `[testimonialswidget_widget]` to display testimonials on a page or in a post
1. Or read the description for `testimonialswidget_list()` and `testimonialswidget_widget()` theme functions usage


== Upgrade Notice ==

= Version 2.0.0 =
* CSS
	* Class `testimonialswidget_company` replaces `testimonialswidget_source`
	* Class `testimonialswidget_source` replaces `testimonialswidget_author`
	* The tighten widget display up, p tags within q are displayed inline.
* Shortcode options
	* `hide_source` replaced by `hide_url`
	* `hide_author` replaced by `hide_source`
* Testimonials
	* Migration from the old custom table to new custom post type is automatically done. Import might take a few moments to complete.
	* Company, URL and email details are attempted to be identified and placed properly based upon the original author and source fields. The company is "guessed" from the `author` field when there's a ", " or " of " context. If the `source` is an email, it's saved as such. Otherwise, it's assumed to be a URL.
	* Public testimonials are saved as Published. Non-public testimonials are marked as Private.
* Widget options
	* "Show author" and "Show source" options are replaced by "Hide source" and "Hide URL" respectively. There's no backwards compatibility for these changes. 
	* Default `min-height` is now 250px than 150px.


== Frequently Asked Questions ==

= How do you include the actual testimonials for the widget? Where do I quote my customers? I mean, where do I enter the actual text? =
Checkout the first screenshot 1 at http://wordpress.org/extend/plugins/testimonials-widget/screenshots/ to see where to manage testimonials.

Basically, look down the left side of your WordPress admin area for the Testimonials sections. Click on that section link, then scroll down or click "Add new ttestimonial" to add quotes.

= How do I change the image size? =
The default image size is based upon Thumbnail size in Media Settings. If changing that doesn't work for you, then use `add_filter` in your theme to adjust the image size.

`add_filter( 'testimonials_widget_image_size', array( 120, 90 ) );`

You can use either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).

= How do I change the Gravatar size? =
Use an `add_filter` in your theme to adjust the Gravatar size.

`add_filter( 'testimonials_widget_gravatar_size', 120 );`

Default Gravatar size is 96, maximum 512.

= What CSS applies to testimonials container? =
CSS class `testimonialswidget_testimonials` wraps all testimonials. Additionally, shortcode lists are wrapped by `testimonialswidget_testimonials testimonialswidget_testimonials_list`.

= What CSS applies to single testimonial container? =
CSS class `testimonialswidget_testimonial` wraps a single testimonial. Additionally, single shortcode list tems are wrapped by `testimonialswidget_testimonial testimonialswidget_testimonial_list`.

= How can I add the testimonials plugin to any where on the site? ie. somewhere other than the side bar like the contact page etc.? =
Use [testimonialswidget_list]. Usage examples are at the bottom of http://wordpress.org/extend/plugins/testimonials-widget/.

Look for `[testimonialswidget_list]`.

= How do I hide the comma after the source? =
Use CSS.
`
.testimonialswidget_testimonial .testimonialswidget_join {
	display: none;
}
`

= Testimonials widget is not showing or rotating =
The usual problem is that jQuery is included twice. Once by WordPress and again by a theme. Remove the jQuery version included by your theme and you should be fine.

= I'm not seeing any testimonials but the title =
If you're not seeing any testimonials, even when not using tags filter, you might try increasing the Character limit or setting it to '0' or 'none' in the widget box.

= How do I apply custom CSS to a testimonial widget? =
The easiest thing is to check the source code of your page with the widget and look for the testimonial widgets div container id tag. It'll be something like `id="testimonials_widget-3"`.

= How to stop testimonial text being cut off in the widget? =
Specify a larger minimum height in the testimonials widget, see screenshot 3.

= How to get rid of the quotation marks that surround the random quote? =
`
.testimonialswidget_testimonial q {
	quotes: none;
}
`

= How to change the random quote text color? =
Styling such as text color, font size, background color, etc., of the random quote can be customized by editing the testimonials-widget.css file or applying CSS like the following.

`
.testimonialswidget_testimonial q {
	color: blue;
}
`

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

If you're wanting to change only the company or URL color, then try.

`
.testimonialswidget_testimonial_list cite .testimonialswidget_company {
	color: purple;
}
`

Like wise, the source uses class `testimonialswidget_source`.

= How do I change the join ", " text? =
In CSS, revise the join content like the following.

`
.testimonialswidget_testimonial .testimonialswidget_join:before {
	content: " | "
}
`

= How to change the admin access level setting for the quotes collection admin page? =
Change the value of the variable `$testimonialswidget_admin_userlevel` on line 33 of the testimonials-widget.php file. Refer [WordPress documentation](http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table) for more information about user roles and capabilities.

= How do I put company details on a separate line? =
In CSS put the following.

`
.testimonialswidget_testimonial .testimonialswidget_join {
	display: none;
}

.testimonialswidget_testimonial .testimonialswidget_company,
.testimonialswidget_testimonial .testimonialswidget_url {
	display: block;
}
`

= I'm stuck, how can I get help? =
Visit the [support forum](http://wordpress.org/support/plugin/testimonials-widget) and ask your question.


== Screenshots ==

1. Testimonials admin interface
2. Edit testimonial
3. Testimonials Widget options - top
4. Testimonials Widget options - bottom
5. [testimonialswidget_widget] in post and testimonial widget in the sidebar 
6. [testimonialswidget_list] in post
7. [testimonialswidget_list] results
	

== TODO ==

* Ideas
	* Caching
	* Centralized defaults - share widgets and shortcode options
	* CSV import
	* Custom CSS
	* Custom templating
	* Disable post password
	* During migration
		* Pull out images and attach correctly
		* Set category
	* Fields to show
		* Category
		* Date
		* Tags
	* Global options page
		* Number of refresh interations
		* Widget options inherit from global
	* Minimum height removal for widgets
	* Move CSS to footer? - http://wordpress.org/support/topic/plugin-testimonials-widget-html-validation
	* Next/Back buttons or click to advance - http://wordpress.org/support/topic/plugin-testimonials-widget-how-to-add-nextback-buttons-or-clickthrough
	* Pagination when using shortcode
	* Scrolling text - http://wordpress.org/support/topic/plugin-testimonials-widget-scroll-for-a-single-but-long-testimonial
	* Testimonial manual ordering
	* Translate with WPML


== Changelog ==
= trunk =
-

= 2.0.4 =
* Allow for 0 refresh_interval in get_testimonials_html

= 2.0.3 =
* Allow for 0 refresh_interval in widget

= 2.0.2 =
* BUGFIX [Warning: call_user_func_array() ??](http://wordpress.org/support/topic/plugin-testimonials-widget-warning-call_user_func_array)
* Added Testimonials_Widget_Widget::get_testimonials_scripts for use with add_filter for wp_footer

= 2.0.1 =
* Verbiage updates
* Readme.txt validation
* widget q p tag display inline
* GPL2 licensing
* Move upgrade notice text towards installation
* Reorder screenshots
* Apply 'the_content' filters directly to prevent plugin baggage
* Update screenshot-7.png

= 2.0.0 =
* Major rewrite
* Add filters for image & gravatar sizes
* Admin bar New > Testimonial
* Authors and lower can manage their own testimonials
* Auto-migration from old to new format upon install
	* Public > Published
	* Not public > Private
* Categories - product, project, service
* Clean up verbiage
* Cleaner widget class
* Custom columns list view
	* Image
	* Source
	* Shortcode
	* Email
	* Company
	* URL
	* Published by
	* Category
	* Tags
	* Date
* Custom fields metabox
	* Email
	* Company
	* URL
* Custom post-type
* Default fields - source, email, company, URL
* Editors and higher can manage all testimonials and edit testimonial publisher
* Enable categories and tags
* Enable full shortcode options in widget
* Gravatar
* HTML content allowed
* Images
* JavaScript in footer
* Localization
* Reference shortcode column
* Reorganize widget options panel
* Rotation JavaScript in footer than body
* Shortcode options validation
* WP_Query for get_testimonials()
* Widget image on own line
* Widget options
	* Title
	* Category filter
	* Tags filter
	* Require all tags
	* Advanced options
	* Hide image?
	* Hide source?
	* Hide email?
	* Hide company?
	* Hide URL?
	* Character limit
	* IDs filter
	* Limit
	* Minimum Height
	* ORDER BY
	* ORDER BY Order
	* Random order
	* Refresh Interval
* Move caching to ideas
* Add theme function `testimonialswidget_widget()` doc
* Update POT
* [testimonialswidget_widget] shortcode
* Match [testimonialswidget_widget] shortcode option defaults to widget
* Update screenshots
* Readd Minimum Height - need help getting around this

= 0.2.13 =
* Clean up CSS
* Remove q & cite p wrapper

= 0.2.12 =
* the_title filter fix

= 0.2.11 =
* Enable character limit for shortcode

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
