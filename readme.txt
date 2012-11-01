=== Testimonials Widget ===
Contributors: comprock
Donate link: http://typo3vagabond.com/about-typo3-vagabond/donate/
Tags: ajax, business, client, commendation, custom post type, customer, quotations, quotations widget, quote, quote shortcode, quotes, quotes collection, random, random content, random quote, recommendation, reference, shortcode, sidebar, sidebar quote, testimonial, testimonial widget, testimonials, testimonials widget, testimony, widget,wpml
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 2.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

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
	* Ignores already imported
* Compatible with WordPress multi-site
* Custom CSS in footer for HTML validation
* Customizeable testimonial data field `testimonial_extra`
* Display testimonials directly in template via theme function
* Editors and admins can edit testimonial publisher
* Fields for source, title, testimonial, email, company and URL
* Filters for `testimonials_widget_image_size`, `testimonials_widget_gravatar_size`, `testimonials_widget_data`
* Image, Gravatar, category and tag enabled
* Localizable - see `languages/testimonials-widget.pot`
* Multiple widget capable
* Multisite compatible
* Rotation JavaScript in footer than body
* Shortcodes
	* Listings with paging `[testimonialswidget_list]`
	* Rotating `[testimonialswidget_widget]`
* Testimonial supports HTML
* Testimonial, email, and URL fields are clickable
	* The URL requires a protocol like `http://` or `https://`
* Testimonials Widget widget displays static and rotating testimonials 
* URLs can be opened in new windows
* WordPress Multilingual enabled [WPML](http://wpml.org/)

= Premium Features =
Please [donate](http://typo3vagabond.com/about-typo3-vagabond/donate/) for access to the premium level code.

* Caching of testimonials queries and content to decrease server load time improve page loading speed by 1/10 to 1/2 a second

= Shortcode and Widget Options =
* Category filter - Comma separated category slug-names
	* `category` - default none; category=product or category="product,services"
* Character limit - Number of characters to limit testimonial views to
	* `char_limit` - default none; char_limit=200
	* Widget - default 500
* Hide company?
	* `hide_company` - default show; hide_company=true
* Hide email?
	* `hide_email` - default show; hide_email=true
* Hide gravatar?
	* `hide_gravatar` - default show; hide_gravatar=true
* Hide image?
	* `hide_image` - default show; hide_image=true
* Hide not found?
	* `hide_not_found` - default show; hide_not_found=true
* Hide source?
	* `hide_source` - default show; hide_source=true
* Hide title?
	* `hide_title` - default show; hide_title=true
* Hide URL?
	* `hide_url` - default show; hide_url=true
* IDs filter - Comma separated IDs
	* `ids` - default none; ids=2 or ids="2,4,6"
* Limit - Number of testimonials to rotate through via widget or show at a time when listing
	* `limit` - default 25; limit=10
* Sort by meta key - Used when Random order is disabled and sorting by a testimonials meta key is needed
	* `meta_key` - default none [testimonials-widget-company|testimonials-widget-email|testimonials-widget-title|testimonials-widget-url]; meta_key=testimonials-widget-company
* Maximum Height - Set for maximum display height
	* `max_height` - default none; max_height=250
* Minimum Height - Set for minimum display height
	* `min_height` - default none; min_height=100
* ORDER BY Order - DESC or ASC
	* `order` - [default DESC](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); order=ASC
* ORDER BY - Used when Random order is disabled
	* `orderby` - [default ID](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); orderby=title
* Enable paging for [testimonialswidget_list]
	* `paging` - default none; paging=true
	* Widget - Not functional
* Random order - Unchecking this will rotate testimonials per ORDER BY and ORDER BY Order
	* `random` - default none; random=true (overrides `order` and `orderby`)
	* Widget = default true
* Rotation Speed - Seconds between testimonial rotations or 0 for no refresh
	* `refresh_interval` - default 5; refresh_interval=0
* Require all tags - Select only testimonials with all of the given tags
	* `tags_all` - default OR; tags_all=true
* Tags filter - Comma separated tag slug-names
	* `tags` - default none; tags=fire or tags="fire,water"
* URL Target
	* `target` - default none; target=_new

= Shortcode Examples =
* [testimonialswidget_list]
	* [testimonialswidget_list]
	* [testimonialswidget_list category=product hide_not_found=true]
	* [testimonialswidget_list category=product tags=widget limit=5]
	* [testimonialswidget_list char_limit=0 target=_new]
	* [testimonialswidget_list hide_source=true hide_url=true] 
	* [testimonialswidget_list ids="1,11,111"]
	* [testimonialswidget_list meta_key=testimonials-widget-company order=asc limit=10]
	* [testimonialswidget_list paging=true limit=10]
	* [testimonialswidget_list tags="test,fun" random=true]
* [testimonialswidget_widget]
	* [testimonialswidget_widget]
	* [testimonialswidget_widget category=product order=asc]
	* [testimonialswidget_widget min_height=250 max_height=500]
	* [testimonialswidget_widget tags=sometag random=true]

= Theme Function `testimonialswidget_list()` =
* `<?php echo testimonialswidget_list( $args ); ?>`
* `$args` is an array of the above [testimonialswidget_list] shortcode options - optional

= Theme Function `testimonialswidget_widget()` =
* For calling the widget with rotation code into your theme directly
* `<?php echo testimonialswidget_widget( $args, $number ); ?>`
* `<?php echo testimonialswidget_widget( $args ); ?>`
* `$args` is an array of the above [testimonialswidget_list] shortcode options - optional, see FAQ for usage
* `$number` should be an arbitrarily number that doesn't conflict with existing actual Testimonial Widgets widget IDs - optional

= Notes =
* Default image size is based upon Thumbnail size in Media Settings 
* Gravatar image is configured in the Avatar section of Discussion Settings

= Languages =
You can translate this plugin into your if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://typo3vagabond.com/contact-typo3vagabond/) to the plugin author.

= Background & Thanks =
Version 2.0.0 of Testimonials Widget is a complete rewrite based upon a composite of ideas from user feedback and grokking the plugins [Imperfect Quotes](http://www.swarmstrategies.com/imperfect-quotes/), [IvyCat Ajax Testimonials](http://wordpress.org/extend/plugins/ivycat-ajax-testimonials/), [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/), and [TB Testimonials](http://travisballard.com/wordpress/tb-testimonials/). Thank you to these plugin developers for their efforts that have helped inspire this rewrite.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring metaboxes for your posts, pages or custom post types a snap.

Prior to version 2.0.0, this plugin was a fork of [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/) by [Srini G](http://wordpress.org/support/profile/SriniG) with additional contributions from [j0hnsmith](http://wordpress.org/support/profile/j0hnsmith), [ChrisCree](http://wordpress.org/support/profile/ChrisCree) and [comprock](http://wordpress.org/support/profile/comprock).

= Support =

Please request support through the [WordPress forums for Testimonials Widget](http://wordpress.org/support/plugin/testimonials-widget). Additionally, if you need to send sensitive information, please email <support@typo3vagabond.com>.


== Installation ==

1. Via WordPress Admin > Plugins > Add New, Upload the `testimonials-widget.zip` file
1. Alternately, via FTP, upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through WordPress Admin > Plugins

= Usage =
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials Widget' into the desired widget area
1. Configure the 'Testimonials Widget' to select quotes and display as needed
1. Alternately, use the `[testimonialswidget_list]` or `[testimonialswidget_widget]` shortcodes to display testimonials on a page or in a post
1. Alternately, read the FAQ for `testimonialswidget_list()` and `testimonialswidget_widget()` theme functions usage


== Frequently Asked Questions ==

= 1. How do I use the theme functions `testimonialswidget_list()` and `testimonialswidget_widget()`? =

In your theme functions file, place code similar to the following for the configuration you need.

`
<?php

$args							= array(
	'category'					=> 'product',
	'tags'						=> 'widget',
	'limit'						=> 5
);

echo testimonialswidget_list( $args );

$args['refresh_interval']		= 10;

echo testimonialswidget_widget( $args );

?>
`

= 2. How do you include the actual testimonials for the widget? Where do I quote my customers? I mean, where do I enter the actual text? =
Checkout the first screenshot 1 at http://wordpress.org/extend/plugins/testimonials-widget/screenshots/ to see where to manage testimonials.

Basically, look down the left side of your WordPress admin area for the Testimonials sections. Click on that section link, then scroll down or click "Add new ttestimonial" to add quotes.

= 3. How do I filter the testimonials data before display processing? =
`
function my_testimonials_widget_data( $array ) {
	foreach( $array as $key => $testimonial ) {
		// do something with the $testimonial entry
		// the keys below are those that are currently available
		// 'testimonial_extra' is the key in which you can put in your own custom content for display
		$testimonial	= array(
			'post_id'				=> …,
			'testimonial_source'	=> …,
			'testimonial_company'	=> …,
			'testimonial_content'	=> …,
			'testimonial_email'		=> …,
			'testimonial_image'		=> …,
			'testimonial_url'		=> …,
			'testimonial_extra'		=> …,
		);

		$array[ $key ]			= $testimonial;
	}

	return $array;
}

add_filter( 'testimonials_widget_data', 'my_testimonials_widget_data' );
`

Do note that content truncation might still remove your appended content if you're using `char_limit`.

Content of `testimonial_extra` is appended after the closing `cite` tag within the testimonial with CSS class `testimonialswidget_extra`.

= 4. How do I change the image size? =
The default image size is based upon Thumbnail size in Media Settings. If changing that doesn't work for you, then use `add_filter` in your theme to adjust the image size.

`
add_filter( 'testimonials_widget_image_size', 'my_testimonials_widget_image_size' );

function my_testimonials_widget_image_size( $size ) {
	$size						= array( 120, 90 );

	return $size;
}
`

You can use either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).

= 5. How do I change the Gravatar size? =
Use an `add_filter` in your theme to adjust the Gravatar size.

`
add_filter( 'testimonials_widget_gravatar_size', 'my_testimonials_widget_gravatar_size' );

function my_testimonials_widget_gravatar_size( $size ) {
	$size						= 120;

	return $size;
}
`

Default Gravatar size is 96, maximum 512.

= 6. What CSS applies to testimonials container? =
CSS class `testimonialswidget_testimonials` wraps all testimonials. Additionally, shortcode lists are wrapped by `testimonialswidget_testimonials testimonialswidget_testimonials_list`.

= 7. What CSS applies to single testimonial container? =
CSS class `testimonialswidget_testimonial` wraps a single testimonial. Additionally, single shortcode list tems are wrapped by `testimonialswidget_testimonial testimonialswidget_testimonial_list`.

= 8. How can I add the testimonials plugin to any where on the site? ie. somewhere other than the side bar like the contact page etc.? =
Use [testimonialswidget_list]. Usage examples are at the bottom of http://wordpress.org/extend/plugins/testimonials-widget/.

Look for `[testimonialswidget_list]`.

= 9. How do I hide the comma after the source? =
Use CSS.
`
.testimonialswidget_testimonial .testimonialswidget_join {
	display: none;
}
`

= 10. Testimonials widget is not showing or rotating =
The usual problem is that jQuery is included twice. Once by WordPress and again by a theme. Remove the jQuery version included by your theme and you should be fine.

= 11. I'm not seeing any testimonials but the title =
If you're not seeing any testimonials, even when not using tags filter, you might try increasing the Character limit or setting it to '0' or 'none' in the widget box.

= 12. How do I apply custom CSS to a testimonial widget? =
The easiest thing is to check the source code of your page with the widget and look for the testimonial widgets div container id tag. It'll be something like `id="testimonials_widget-3"`.

= 13. How to get rid of the quotation marks that surround the random quote? =
`
.testimonialswidget_testimonial q {
	quotes: none;
}
`

= 14. How to change the random quote text color? =
Styling such as text color, font size, background color, etc., of the random quote can be customized by editing the testimonials-widget.css file or applying CSS like the following.

`
.testimonialswidget_testimonial q {
	color: blue;
}
`

= 15. How can I style the shortcode testimonials? =
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

= 16. How do I change the join ", " text? =
In CSS, revise the join content like the following.

`
.testimonialswidget_testimonial .testimonialswidget_join:before {
	content: " | "
}
`

= 17. How to change the admin access level setting for the quotes collection admin page? =
Change the value of the variable `$testimonialswidget_admin_userlevel` on line 33 of the testimonials-widget.php file. Refer [WordPress documentation](http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table) for more information about user roles and capabilities.

= 18. How do I put the title on a separate line? =
In CSS put the following.

`
.testimonialswidget_testimonial .testimonialswidget_join_title {
	display: none;
}

.testimonialswidget_testimonial .testimonialswidget_title {
	display: block;
}
`

= 19. How do I put company details on a separate line? =
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

= 20. After upgrading, testimonial rotations have stopped =
The JavaScript for rotating testimonials is moved to the footer. As such, your theme requires `wp_footer()` in the footer. Check to make sure your theme has the `wp_footer()` call in footer.php or the equivalent file.

= 21. How can I justify testimonials text? =

To justify all testimonials try…
`
.testimonialswidget_testimonial {
	text-align: justify;
}
`
To justify only the testimonials list try…
`
.testimonialswidget_testimonial_list {
	text-align: justify;
}
`

= 22. How about testimonials own URL? =
Testimonial Widgets records are a custom post type and therefore can be viewed via a URL like http://www.example.com/testimonial/michael-cannon-senior-developer/.

When you look at the Testimonials Widget admin list, you can click on the View link to see the testimonial.

Going further though, you'll need to enable feature image, gravatar and custom post meta like company, email, etc. on your own for your theme.

= 23. My testimonial URL says "Page not found" =
Go to WordPress > Plugins to Deactivate and then Activate Testimonials Widget. The `flush_rewrite_rules` function needs to run.

= 24. Does this plug in use admin-ajax.php to refresh? =
No, it doesn't call admin-ajax.php at all.

= 25. Is there a way to reorder testimonials? =
Look for ORDER BY under Advanced Options of the Testimonials Widget. In ORDER BY, put post_date. Then you use dates to put your testimonials into the order you want.

= 26. How do I create a next link? =
See http://wordpress.org/support/topic/plugin-testimonials-widget-next-testimonial-not-pagination.

= 27. How do I hide the "No testimonials found" text? =
In Widget options, check "Hide testimonials not found?" or in shortcode options use `hide_not_found=true`.

`[testimonialswidget_list hide_not_found=true]`


= I'm still stuck, how can I get help? =
Visit the [support forum](http://wordpress.org/support/plugin/testimonials-widget) and ask your question.


== Screenshots ==

1. Testimonials admin interface
2. Edit testimonial
3. Testimonials Widget options - top
4. Testimonials Widget options - bottom
5. [testimonialswidget_widget] in post and testimonial widget in the sidebar 
6. [testimonialswidget_list] in post
7. [testimonialswidget_list] results
8. [testimonialswidget_list] with paging


== Changelog ==
= trunk =
-

= 2.2.5 =
* Adapt for [Testimonials Widget Premium plugin](http://typo3vagabond.com/wordpress/testimonials-widget-premium/)
* Add support text
* Correct verbiage spacing
* Explain `limit`
* TODO revisions

= 2.2.4 =
* BUGFIX [Tags - no more than 2?](http://wordpress.org/support/topic/tags-no-more-than-2)
* Clean up PHP notices
* Fix Changelog link
* PREMIUM Implement testimonials query and content caching
* TODO update

= 2.2.3 =
* Begin premium plugin adaptions
* BUGFIX [Tags - no more than 2?](http://wordpress.org/support/topic/tags-no-more-than-2)
* BUGFIX [Updated - Now getting fatal error when using testimonialswidget_list()](http://wordpress.org/support/topic/updated-now-getting-fatal-error-when-using-testimonialswidget_list)
* Clean up links in readme.txt
* Correct company and URL link usage
* [Correct readme.txt to standard](http://wordpress.org/extend/plugins/about/readme.txt)
* Don't rotate testimonial if only 1 
* TODO updates

= 2.2.2 =
* BUGFIX [Now getting fatal error when using testimonialswidget_list()](http://wordpress.org/support/topic/updated-now-getting-fatal-error-when-using-testimonialswidget_list)
* Theme function defaults
* TODO updates
* URL pointing update

= 2.2.1 =
* Number FAQ Entries
* Revise Installation Usage text
* Revise Shortcode and Widget Options text

= 2.2.0 =
* FAQ `testimonialswidget_widget()` example
* Multisite compatible
* Reversion as 2.1.10 was a minor release than only bug fixes

= 2.1.10 =
* [Add title field ](http://wordpress.org/support/topic/plugin-testimonials-widget-just-tried-216-thoughts-suggestions)
* Consolidate defaults to simplify code maintenance
* Correct CSS testimonial list spacing
* Debug true - clear out PHP notices and such
* Default minimum height removed for widgets, now optional
* Maximum height setting
* [Remove CSS `position` attributes `.testimonialswidget_testimonial { position: absolute; }`](http://wordpress.org/support/topic/testimonials-widget-not-showing-correctly-on-sub-pages)
* TODO cleanup
* Update language POT
* Update screenshots
* Update WPML
* Widget options dropdown for ORDER BY entries

= 2.1.9 =
* Allow min_height 0
* FAQ - How do I use the theme function `testimonialswidget_list()`?
* Move CSS include to header

= 2.1.8 =
* Remove testimonialswidget_widget char_limit default
* TODO - debug true

= 2.1.7 =
* [0 disables char_limit](http://wordpress.org/support/topic/plugin-testimonials-widget-more-than-one-testimonial-appears-overlaps-content-below-the-widget)
* [Set link target](http://wordpress.org/support/topic/plugin-testimonials-widget-just-tried-216-thoughts-suggestions)
* Update widget option top screenshot

= 2.1.6 =
* FAQ: `ORDER BY` explanation
* FAQ: `testimonial_extra` explanation
* [Moved CSS to footer](http://wordpress.org/support/topic/plugin-testimonials-widget-html-validation)
* Next testimonial link idea
* Option: Add `hide_not_found` to prevent showing "No testimonials found"
* Revise theme methods as functions
* Screenhsot: Update upper widget options
* Staged widget testimonials are initially `display: none` via CSS `.testimonialswidget_display_none`
* TODO updates
* Verbiage: Refresh Interval to Rotation Speed
* Widget option explanations

= 2.1.5 =
* Always apply min-height

= 2.1.4 =
* Enable WPML
* Idea - Maximum height setting
* Revise description
* Revise TODO

= 2.1.3 =
* Allow commas in meta_key
* FAQ on page not found
* Fix widget Random order always true condition
* Increase bottom margin spacing for listed testimonials
* TODO vote casting note
* Update localization pot file

= 2.1.2 =
* Add `hide_gravatar` option
* Add apply_filters( 'testimonials_widget_data', $testimonial_data ) to process data before display processing
* Add right margin to gravatar image
* Added empty testimonial data field `testimonial_extra` for customization in testimonials
* Allow widget and shortcode sorting by post meta values via `meta_key`
* Correct PHP static accessors
* Update FAQ
* Update widget options screenshots
* Working full testimonial URLs

= 2.1.1 =
* Add [testimonialswidget_list] paging screenshot

= 2.1.0 =
* Enable paging for [testimonialswidget_list] shortcode
* Flush rewrite rules on activation
* Disallow paging in widget and [testimonialswidget_widget] shortcode

= 2.0.6 =
* Update shortcode option directions

= 2.0.5 =
* Ignore already imported
* Mark `testimonialswidget_widget() $number` argument as optional

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
	* Maximum Height
	* Minimum Height
	* ORDER BY
	* ORDER BY Order
	* Random order
	* Rotation Speed
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


== Upgrade Notice ==

= 2.0.0 =
* CSS
	* Class `testimonialswidget_company` replaces `testimonialswidget_source`
	* Class `testimonialswidget_source` replaces `testimonialswidget_author`
	* The tighten widget display up, p tags within q are displayed inline.
* JavaScript
	* The JavaScript for rotating testimonials is moved to the footer. As such, your theme requires `wp_footer()` in the footer.
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


== TODO ==

Cast your vote on what to do next with [donations](http://typo3vagabond.com/about-typo3-vagabond/donate/) and [testimonials](http://typo3vagabond.com/contact-typo3vagabond/).

* CSV import
* [CSV Export](http://wordpress.org/support/topic/csv-export-publish-new)
* Fields to show
	* Category
	* Date
	* Tags
* Global options page
	* Centralized defaults - share widgets and shortcode options
	* Number of refresh interations
	* Widget options inherit from global
* Improved single page view - image and all fields
* [Make the widget title clickable](http://wordpress.org/support/topic/possible-to-make-the-widget-title-clickable)
* [Next testimonial](http://wordpress.org/support/topic/plugin-testimonials-widget-next-testimonial-not-pagination)
* [Next, Previous page indicators](http://wordpress.org/support/topic/next-previous-page-indicators)
* [Page numbers](http://wordpress.org/support/topic/next-previous-page-indicators)
* [Publish & New](http://wordpress.org/support/topic/csv-export-publish-new)
* [Read More links to full testimonial page](http://wordpress.org/support/topic/plugin-testimonials-widget-short-rotating-testimonial-link-to-the-full-testimonial)
* [Scrolling text](http://wordpress.org/support/topic/plugin-testimonials-widget-scroll-for-a-single-but-long-testimonial)
* Widget category select helper
