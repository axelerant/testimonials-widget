=== Testimonials Widget ===
Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: client, customer, quotations, quote, quotes, random, review, quote, recommendation, reference, testimonial, testimonials, testimony, widget, wpml
Requires at least: 3.4
Tested up to: 3.6.0
Stable tag: 2.10.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Testimonials Widget plugin allows you to display random or rotating portfolio, quotes, reviews, showcases, or text with images on your WordPress blog.


== Description ==
Testimonials Widget plugin allows you to display random or rotating portfolio, quotes, reviews, showcases, or text with images on your WordPress blog. You can insert Testimonials Widget content via shortcode, theme functions, or widgets with category and tag selections and having multiple display options such as random or specific ordering.

More than one Testimonials Widget section can be displayed at a time. Each Testimonials Widget separately pulls from the `testimonials-widget` custom post type. Additionally, with shortcodes and theme functions, you can display a short or long list or rotation of testimonials. Each Testimonal Widget has its own CSS identifier for custom styling.

Widgets display content sans `wpautop` formatting. This means no forced paragraph breaks unless the content specifically contains them. You can enable `wpautop` via the "Keep whitespace?" option.

Through categories and tagging, you can create organizational structures based upon products, projects and services via categories and then apply tagging for further classificaton. As an example, you might create a Portfolio category and then use tags to identify web, magazine, media, public, enterprise niches. You can then configure the Testimonial Widget to show only Portfolio testimonials with the public and enterprise tags. In another Testimonial Widget, you can also select only Portfolio testimonials, but then allow web and media tags.

Single testimonial view supports image, source, title, location, email, company and URL details.

= Primary Features =

* Admin interface to add, edit and manage testimonials
* Filters to manipulate testimonial layout and presentation
* Has fields for source, title, location, testimonial, email, company and URL details
* Multiple widgets on a single page capable
* Settings screen for site-wide option defaults
* Shortcodes and theme functions for listings and rotation
* Single testimonial view includes image, source, title, location,, email, company and URL details
* Testimonials archive view

= Testimonials Widget Premium Plugin Features =

Testimonials Widget Premium plugin extends the best [Testimonials Widget](http://wordpress.org/extend/plugins/testimonials-widget/) plugin for WordPress with [caching, excerpts, filters, read more links](http://aihr.us/wordpress/testimonials-widget-premium/), more selection options, and advanced capabilities like selecting posts, pages and other custom post types as testimonials. Additionally, user may optionally add testimonials via a front-end form.

In using Testimonials Widget Premium, you'll not be sorry.

* Alternate destinations for "Read more" links
* Built-in update notification
* Caching of testimonials queries and content to decrease server load time improve page loading speed by 1/10 to 1/2 a second
* Deletes old and related testimonial cache entries automatically
* Disable caching for widget, shortcode or theme functions
* Ensure unique testimonial display per page
* Excerpts for widget view, with read more link to complete testimonial
* Filters for caching and more link control, text replacement, and more
* Front-end entry form for user supplied testimonials. [Live demo](http://aihr.us/about-aihrus/testimonials/add-testimonial/)
* Prevent duplicate testimonials when using multiple testimonial instances
* Read more links for testimonials exceeding the character limit
* Select post, page and other custom post types for rotations
* Select only testimonials with excerpts, images or of arbitrary maximum and minimum length
* Settings screen for site-wide option defaults
* Shortcodes and theme functions for testimonials count and testimonial link list
* Show excerpts with list and single views
* Testimonial list entries have alternating `.even` and `.odd` CSS classes for backgrounds and other styling
* WPML compatible

[Buy Testimonials Widget Premium](http://aihr.us/wordpress/testimonials-widget-premium/) plugin for WordPress.

= Additional Features =

* Auto-migration from pre-2.0.0 custom table to new custom post type
	* Company, URL and email details are attempted to be identified and placed properly
	* Public testimonials are saved as Published. Non-public, are marked as Private.
	* Ignores already imported
* Commenting on testimonial single-view pages
* Compatible with WordPress multi-site
* Content truncation to respect HTML tags
* Custom CSS in footer for HTML validation
* Custom text or HTML for bottom of widgets
* Customizable archive and testimonial URLs
* Customizeable testimonial data field `testimonial_extra`
* Editors and admins can edit testimonial publisher
* Image, Gravatar, category and tag enabled
* Localizable - see `languages/testimonials-widget.pot`
* Respects meta capabilities
* Rotation JavaScript in footer than body
* Scrolling testimonials for maximum height restricted widgets
* Supports [WP-PageNavi](http://wordpress.org/extend/plugins/wp-pagenavi/)
* Testimonial content and layout completely customizable via filters
* Testimonial content supports HTML
* Testimonial, email, and URL fields are clickable
	* The URL requires a protocol like `http://` or `https://`
* Testimonials Widget widget displays static and rotating testimonials 
* Testimonials support styling based upon CSS classes for category, tags and post id
* URLs can be opened in new windows
* Unique CSS class per widget
* WordPress Multilingual enabled [WPML](http://wpml.org/)

= Shortcodes =

* `[testimonialswidget_list]` - Listings with paging 
* `[testimonialswidget_widget]` - Rotating

= Shortcode and Widget Options =

* Widget Title
	* `title1 - default "Testimonials"
* Title Link - URL or Post ID to link widget title to
	* `title_link` - default none; title_link=123, title_link=http://example.com
* Category Filter - Comma separated category slug-names
	* `category` - default none; category=product or category="category-a, another-category"
* Character Limit - Number of characters to limit testimonial views to
	* `char_limit` - default none; char_limit=200
	* Widget - default 500
* Exclude IDs Filter - Comma separated IDs
	* `exclude` - default none; exclude=2 or exclude="2,4,6"
* Hide Content?
	* `hide_content` - default show; hide_content=true
* Hide Company?
	* `hide_company` - default show; hide_company=true
* Hide Email?
	* `hide_email` - default show; hide_email=true
* Hide Gravatar Image?
	* `hide_gravatar` - default show; hide_gravatar=true
* Hide Image?
	* `hide_image` - default show; hide_image=true
* Hide "Testimonials Not Found"?
	* `hide_not_found` - default show; hide_not_found=true
* Hide Author/Source? - Don't display "Post Title" in cite
	* `hide_source` - default show; hide_source=true
* Hide Title?
	* `hide_title` - default show; hide_title=true
* Hide Location?
	* `hide_location` - default show; hide_location=true
* Hide URL?
	* `hide_url` - default show; hide_url=true
* Include IDs Filter - Comma separated IDs
	* `ids` - default none; ids=2 or ids="2,4,6"
* Remove `.hentry` CSS? – Some themes use class `.hentry` in a manner that breaks Testimonials Widgets CSS
	* `remove_hentry` - default none; remove_hentry=true
* Keep Whitespace? - Keeps testimonials looking as entered than sans auto-formatting
	* `keep_whitespace` - default none; keep_whitespace=true
	* The citation has no whitespace adaptions. It's straight text, except for email or URL links. The presentation is handled strictly by CSS.
* Limit - Number of testimonials to rotate through via widget or show at a time when listing
	* `limit` - default 10; limit=25
* ORDER BY meta_key - Used when "Random Order" is disabled and sorting by a testimonials meta key is needed
	* `meta_key` - default none [testimonials-widget-company|testimonials-widget-email|testimonials-widget-title|testimonials-widget-location|testimonials-widget-url]; meta_key=testimonials-widget-company
* Minimum Height - Set for minimum display height, in pixels
	* `min_height` - default none; min_height=100
* Maximum Height - Set for maximum display height, in pixels
	* `max_height` - default none; max_height=250
* ORDER BY Order - DESC or ASC
	* `order` - [default DESC](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); order=ASC
* ORDER BY - Used when Random order is disabled
	* `orderby` - [default ID](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); orderby=title
* Enable Paging - for [testimonialswidget_list]
	* `paging` - default true [true|before|after|false]; paging=false
		* `true` – display paging before and after testimonial entries
		* `before` – display paging only before testimonial entries
		* `after` – display paging only after testimonial entries
	* Widget - Not functional
* Random Order? - Unchecking this will rotate testimonials per ORDER BY and ORDER BY Order. Widgets are random by default automatically
	* `random` - default none; random=true (overrides `order` and `orderby`)
	* Widget = default true
* Rotation speed - Seconds between testimonial rotations or 0 for no rotation at all
	* `refresh_interval` - default 5; refresh_interval=0
* Require All Tags - Select only testimonials with all of the given tags
	* `tags_all` - default OR; tags_all=true
* Tags Filter - Comma separated tag slug-names
	* `tags` - default none; tags=fire or tags="tag-a, another-tag"
* URL Target - Adds target to all URLs; leave blank if none
	* `target` - default none; target=_new
* Testimonial Bottom Text - Custom text or HTML for bottom of testimonials
	* `bottom_text` - default none; bottom_text="`&lt;h3&gt;&lt;a href="http://example.com"&gt;All testimonials&lt;/a&gt;&lt;/h3&gt;`"

= Shortcode Examples =

* [testimonialswidget_list]
	* [testimonialswidget_list]
	* [testimonialswidget_list category=product hide_not_found=true]
	* [testimonialswidget_list category=product tags=widget limit=5]
	* [testimonialswidget_list char_limit=0 target=_new]
	* [testimonialswidget_list hide_source=true hide_url=true] 
	* [testimonialswidget_list ids="1,11,111"]
	* [testimonialswidget_list meta_key=testimonials-widget-company order=asc limit=15]
	* [testimonialswidget_list order=ASC orderby=title]
	* [testimonialswidget_list paging=true limit=25]
	* [testimonialswidget_list tags="test,fun" random=true exclude="2,22,333"]
* [testimonialswidget_widget]
	* [testimonialswidget_widget]
	* [testimonialswidget_widget category=product order=asc]
	* [testimonialswidget_widget min_height=250 max_height=500]
	* [testimonialswidget_widget tags=sometag random=true]

= Theme Functions =

* `testimonialswidget_list()` - Listings with paging 
* `testimonialswidget_widget()` - Rotating

= Filters =

* `testimonials_widget_cache_get` - Caching grabber
* `testimonials_widget_cache_set` - Caching setter
* `testimonials_widget_columns` - Customize testimonial posts column headers
* `testimonials_widget_content_more` - More content ellipsis
* `testimonials_widget_content` - Testimonial content parser helper
* `testimonials_widget_data` - Process testimonials data before display processing
* `testimonials_widget_defaults_single` - Create a global or central Testimonials Widget configuration for single view
* `testimonials_widget_defaults` - Create a global or central Testimonials Widget configuration
* `testimonials_widget_get_testimonial_html` - Customize testimonial contents and layout within `get_testimonial_html`. Useful for moving processed parts around than regenerating everything from scratch.
* `testimonials_widget_gravatar_size` - Change the Gravatar size
* `testimonials_widget_image_size` - Change the image size
* `testimonials_widget_meta_box` - Modify Testimonial Data fields
* `testimonials_widget_next_posts_link` - Configure Next page indicator
* `testimonials_widget_posts_custom_column` - Customize testimonial posts column contents
* `testimonials_widget_previous_posts_link_text` - Configure Previous page indicator
* `testimonials_widget_query_args` - Alter WP_Query arguments for testimonial selection
* `testimonials_widget_sections` – Alter section options
* `testimonials_widget_settings` – Alter setting options
* `testimonials_widget_testimonial_html_single_content` - Customize single view content before appending filter `testimonials_widget_testimonial_html_single` results
* `testimonials_widget_testimonial_html_single` - Customize testimonials single view output post `get_testimonial_html`
* `testimonials_widget_testimonial_html` - Customize testimonials list and widget output post `get_testimonial_html`
* `testimonials_widget_testimonials_css` - Alter dynamically generated CSS
* `testimonials_widget_testimonials_js` - Alter dynamically generated JavaScript
* `testimonials_widget_validate_settings` - Validate settings helper
* `testimonials_widget_version` - Version tracking for settings
* `testimonials_widget_wp_pagenavi` - Configure WP-PageNavi specifically for Testimonial Widgets

= Notes =

* Default image size is based upon Thumbnail size in Media Settings 
* Gravatar image is configured in the Avatar section of Discussion Settings

= Languages =

You can translate this plugin into your own language if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) to the plugin author.

* Dutch by Bjorn Robijns
* [Hebrew by Ahrale](http://atar4u.com/)

= Recommendation =

* Use Jonathan Lundström's [Drag & Drop Featured Image](http://wordpress.org/extend/plugins/drag-drop-featured-image/) to speed up loading of the featured image

= Background & Thanks =

A big, special thank you to [Joe Weber](https://plus.google.com/100063271269277312276/posts) of [12 Star Creative](http://www.12starcreative.com/) for creating the Testimonials Widget banner. It's fantastic.

Version 2.0.0 of Testimonials Widget is a complete rewrite based upon a composite of ideas from user feedback and grokking the plugins [Imperfect Quotes](http://www.swarmstrategies.com/imperfect-quotes/), [IvyCat Ajax Testimonials](http://wordpress.org/extend/plugins/ivycat-ajax-testimonials/), [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/), and [TB Testimonials](http://travisballard.com/wordpress/tb-testimonials/). Thank you to these plugin developers for their efforts that have helped inspire this rewrite.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring metaboxes for your posts, pages or custom post types a snap.

Prior to version 2.0.0, this plugin was a fork of [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/) by [Srini G](http://wordpress.org/support/profile/SriniG) with additional contributions from [j0hnsmith](http://wordpress.org/support/profile/j0hnsmith), [ChrisCree](http://wordpress.org/support/profile/ChrisCree) and [comprock](http://wordpress.org/support/profile/comprock).

= Support =

So that others can share in the answer, please submit your support requests through the [WordPress forums for Testimonials Widget](http://wordpress.org/support/plugin/testimonials-widget).

If you want private or priority support, [please donate](http://aihr.us/about-aihrus/donate/) $ 125 USD to cover my time. Then send your [support request](http://aihr.us/contact-aihrus/).

Thank you for your understanding.


== Installation ==

1. Via WordPress Admin > Plugins > Add New, Upload the `testimonials-widget.zip` file
1. Alternately, via FTP, upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin through WordPress Admin > Plugins

= Usage =
1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials Widget' into the desired widget area
1. Configure the 'Testimonials Widget' to select quotes and display as needed
1. Alternately, use the `[testimonialswidget_list]` or `[testimonialswidget_widget]` shortcodes to display testimonials on a page or in a post
1. Alternately, read FAQ 1 for `testimonialswidget_list()` and `testimonialswidget_widget()` theme functions usage


== Frequently Asked Questions ==

See [FAQ](http://aihr.us/testimonials-widget/faq/)


= I'm still stuck, how can I get help? =
Visit the [support forum](http://wordpress.org/support/plugin/testimonials-widget) and ask your question. Don't forget to include the URL of where to see the problem.


== Screenshots ==

1. Testimonials admin interface
2. Collasped Testimonials Widget options
3. Expanded 'General Options' in Testimonials Widget options
4. Testimonial widget in the sidebar 
5. [testimonialswidget_list] in post
6. [testimonialswidget_list] results with paging
7. Widget whitespace kept
8. Widget with clickable title and custom text/HTML on bottom
9. [WP-PageNavi compatible](http://wordpress.org/extend/plugins/wp-pagenavi/) for page numbers than default arrows
10. Poedit Catalog properties
11. Testimonials Widget Settings > General tab
12. Expanded 'Selection Options' in Testimonials Widget options
13. Expanded 'Ordering Options' in Testimonials Widget options
14. Testimonials Widget Settings > Selection tab
15. Testimonials Widget Settings > Post Type tab


== Changelog ==
See [Changelog](http://aihr.us/testimonials-widget/changelog/)


== Upgrade Notice ==

= 2.8.0 =
* Deprecated
	* `hide_author` now `hide_source`
* Removed filters `testimonials_widget_options_update`, `testimonials_widget_options_form`
	* Use `testimonials_widget_validate_settings` and `testimonials_widget_settings` instead
* Renamed variable and related class `widget_text` to `bottom_text`

= 2.7.3 =
* Quotes are no longer handled via `q`, `p:before`, or `p:after` CSS. It's handled via `.testimonialswidget_testimonial .testimonialswidget_open_quote:before` and `.testimonialswidget_testimonial .testimonialswidget_close_quote:after`
* This change was made to keep consistency in how quotes were managed and to reduce the number of exception cases. In the end, this is simpler.

= 2.7.0 =
* Quotes with `keep_whitespace=true` aren't applied via CSS `.testimonialswidget_testimonial q` tag anymore, but `.testimonialswidget_testimonial q p:first-child:before` and `.testimonialswidget_testimonial q p:last-child:after`
* Widget testimonial `p` tags are no longer CSS `display: inline`, `display: block` as expected

= 2.4.1 =
* Paging is on by default, except for widgets

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

Is there something you want done? Write it up on the [support forums](http://wordpress.org/support/plugin/testimonials-widget) and then [donate](http://aihr.us/about-aihrus/donate/) or [send along](http://aihr.us/contact-aihrus/) an [awesome testimonial](http://aihr.us/about-aihrus/testimonials/).

* BUG [Post Types Order](http://wordpress.org/support/topic/random-order-doesnt-work) - sorting conflict
* Delete data on deactivation
