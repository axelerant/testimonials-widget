=== Testimonials Widget ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: client, customer, quotations, quote, quotes, random, review, quote, recommendation, reference, testimonial, testimonials, testimony, widget, wpml
Requires at least: 3.4
Tested up to: 3.6.0
Stable tag: 2.11.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Testimonials Widget plugin allows you to display random or selected portfolio, quotes, reviews, showcases, or text with images on your WordPress blog.


== Description ==

Testimonials Widget plugin allows you to display random or selected portfolio, quotes, reviews, showcases, or text with images on your WordPress blog. You can insert Testimonials Widget content via shortcode, theme functions, or widgets with category and tag selections and having multiple display options such as random or specific ordering.

More than one Testimonials Widget instance can be displayed at a time pulls from the `testimonials-widget` custom post type. Additionally, with shortcodes and theme functions, you can display a short or long list or rotation of testimonials. Each Testimonial Widget has its own CSS identifier for custom styling.

Widgets display content sans `wpautop` formatting. This means no forced paragraph breaks unless the content specifically contains them. You can enable `wpautop` via the "Keep whitespace?" option.

Through categories and tagging, you can create organizational structures based upon products, projects and services via categories and then apply tagging for further classification. As an example, you might create a Portfolio category and then use tags to identify web, magazine, media, public, enterprise niches. You can then configure the Testimonial Widget to show only Portfolio testimonials with the public and enterprise tags. In another Testimonial Widget, you can also select only Portfolio testimonials, but then allow web and media tags.

The single testimonial view supports image, source, title, location, email, company and URL details.

= Primary Features =

* Admin interface to add, edit and manage testimonials
* Capable of handling multiple widgets per page or post
* Fields for source, testimonial, image, title, location, email, company and URL details
* Filters to manipulate testimonials output
* Multiple paging options for testimonials listings
* Settings screen for site-wide option defaults
* Settings export/import
* Shortcodes and theme functions for listings and rotation
* Single testimonial view includes image, source, title, location, email, company and URL details
* Testimonials archive view

= Testimonials Widget Premium Plugin Features =

Testimonials Widget Premium plugin extends the best [Testimonials Widget](http://wordpress.org/extend/plugins/testimonials-widget/) plugin for WordPress with [caching, excerpts, filters, read more links](http://aihr.us/testimonials-widget-premium/), more selection options, and advanced capabilities like selecting posts, pages and other custom post types as testimonials. Additionally, users can add testimonials via a front-end form.

In using Testimonials Widget Premium, you'll not be sorry.

* "Read more" link column on testimonial posts admin page
* Alternate destinations for "Read more" links
* Alternating `.even` and `.odd` CSS classes for styling testimonial list entries
* Built-in update notification
* Caching of testimonials queries and content to decrease server load time improve page loading speed by 1/10 to 1/2 a second
* Deactivates self if no active or incorrect version of Testimonials Widget plugin
* Default post author, category, and status options for user testimonial submissions
* Deletes old and related testimonial cache entries automatically
* Disable caching for widget, shortcode or theme functions
* Email notification for user submitted testimonials
* Excerpts for widget view, with read more link to complete testimonial
* Filters for caching and more link control, text replacement, and more
* Front-end entry form for user supplied testimonials. [Live demo](http://aihr.us/about-aihrus/testimonials/add-testimonial/)
* Plugin version tracking to ensure compatibility
* Premium tab on settings screen for site-wide option defaults
* Prevent duplicate testimonials when using multiple testimonial instances
* Read more links for testimonials exceeding the character limit
* Select only testimonials with excerpts, images or of arbitrary maximum and minimum length
* Select post, page and other custom post types for content rotations
* Settings and URL based cache clearing
* Shortcodes and theme functions for testimonials count, testimonial link list, and user testimonial submission form
* Show excerpts with list and single views
* Show unique testimonials on page with multiple testimonial instances
* Testimonial links listing with image, source, title, location, company, and URL fields
* WPML compatible

[Buy Testimonials Widget Premium](http://aihr.us/testimonials-widget-premium/) plugin for WordPress.

= Additional Features =

* Auto-migration from pre-2.0.0 custom table to new Testimonials Widget custom post type
* Automatic linking of email and URL fields via source or company fields
* Clickable widget titles
* Commenting on testimonial single-view pages
* Compatible with WordPress multisite
* Configuration based validation for easy extending or overriding
* Content truncation respects HTML tags
* Custom CSS in footer for HTML validation
* Custom text or HTML for bottom of widgets
* Custom widget bottom text
* Customizable archive and testimonial URLs
* Customizable testimonial data field `testimonial_extra`
* Deletes testimonials-widget custom post type entries and settings on uninstall 
* Disable self-generated quotation marks
* Easy to configure Next and Previous page indicators
* Editors and admins can edit testimonial publisher
* Flush URLs on deactivation 
* IE7 CSS hacks for quotes and join parts
* Image, email based Gravatar, category and tag enabled
* Internal version tracking for compatibility checks and automatic settings update
* Localizable via `languages/testimonials-widget.pot`
* Optional commenting on testimonial single-view pages
* Respects meta capabilities
* Rotation JavaScript in footer than body
* Supports [WP-PageNavi](http://wordpress.org/extend/plugins/wp-pagenavi/)
* Testimonial content supports HTML
* Testimonial, email, and URL fields are clickable – URL requires `http://` or `https://` prefix
* Testimonials Widget widget displays static and rotating testimonials 
* Testimonials output is completely customizable via filters
* Testimonials support styling based upon CSS classes for category, tags and post id
* URLs can be opened in new windows
* Unique CSS class per widget
* WordPress Multilingual enabled [WPML](http://wpml.org/)

= Shortcodes =

* `[testimonialswidget_list]` - Listings with paging 
* `[testimonialswidget_widget]` - Rotating

= Shortcode and Widget Options =

Please keep in mind that inheritance affects options. This means that changes made to Testimonials > Settings will not affect current widget instance options. You must change the widget instance manually. However, if you create a new widget instance, then the global settings will apply.

Further, global settings are the baseline for shortcodes. If you want to alter the shortcode appearance, then alter the shortcode options directly.

**General**

* Character Limit - Number of characters to limit testimonial views to
	* `char_limit` - default none; char_limit=200
	* Widget - default 500
* Hide built-in quotes? - Remove open and close quote span tags surrounding testimonial content
	* `disable_quotes` - default false; disable_quotes=true
* Hide "Testimonials Not Found"?
	* `hide_not_found` - default show; hide_not_found=true
* Hide Gravatar Image?
	* `hide_gravatar` - default show; hide_gravatar=true
* Hide Image?
	* `hide_image` - default show; hide_image=true
* Hide Image in Single View?
	* `hide_image_single` - default show; hide_image_single=true
* Hide Content?
	* `hide_content` - default show; hide_content=true
* Hide Author/Source? - Don't display "Post Title" in cite
	* `hide_source` - default show; hide_source=true
* Hide Email?
	* `hide_email` - default show; hide_email=true
* Hide Title?
	* `hide_title` - default show; hide_title=true
* Hide Location?
	* `hide_location` - default show; hide_location=true
* Hide Company?
	* `hide_company` - default show; hide_company=true
* Hide URL?
	* `hide_url` - default show; hide_url=true
* URL Target - Add target to all URLs; leave blank if none
	* `target` - default none; target=_new
* Testimonial Bottom Text - Custom text or HTML for bottom of testimonials
	* `bottom_text` - default none; bottom_text="`&lt;h3&gt;&lt;a href="http://example.com"&gt;All testimonials&lt;/a&gt;&lt;/h3&gt;`"
* Enable Paging - for [testimonialswidget_list]
	* `paging` - default true [true|before|after|false]; paging=false
		* `true` – display paging before and after testimonial entries
		* `before` – display paging only before testimonial entries
		* `after` – display paging only after testimonial entries
	* Widget - Not functional

**Selection**

* Category Filter - Comma separated category slug-names
	* `category` - default none; category=product or category="category-a, another-category"
* Require All Tags - Select only testimonials with all of the given tags
	* `tags_all` - default OR; tags_all=true
* Tags Filter - Comma separated tag slug-names
	* `tags` - default none; tags=fire or tags="tag-a, another-tag"
* Include IDs Filter - Comma separated IDs
	* `ids` - default none; ids=2 or ids="2,4,6"
* Exclude IDs Filter - Comma separated IDs
	* `exclude` - default none; exclude=2 or exclude="2,4,6"
* Limit - Number of testimonials to rotate through via widget or show at a time when listing
	* `limit` - default 10; limit=25

**Ordering**

* Random Order? - Unchecking this will rotate testimonials per ORDER BY and ORDER BY Order. Widgets are random by default automatically
	* `random` - default none; random=true (overrides `order` and `orderby`)
	* Widget = default true
* ORDER BY - Used when Random order is disabled
	* `orderby` - [default ID](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); orderby=title
* ORDER BY meta_key - Used when "Random Order" is disabled and sorting by a testimonials meta key is needed
	* `meta_key` - default none [testimonials-widget-company|testimonials-widget-email|testimonials-widget-title|testimonials-widget-location|testimonials-widget-url]; meta_key=testimonials-widget-company
* ORDER BY Order - DESC or ASC
	* `order` - [default DESC](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); order=ASC

**Widget**

* Widget Title
	* `title` - default "Testimonials"
* Title Link - URL or Post ID to link widget title to
	* `title_link` - default none; title_link=123, title_link=http://example.com
* Keep Whitespace? - Keeps testimonials looking as entered than sans auto-formatting
	* `keep_whitespace` - default none; keep_whitespace=true
	* The citation has no whitespace adaptations. It's straight text, except for email or URL links. The presentation is handled strictly by CSS.
* Height - Testimonials height, in pixels. Overrides minimum and maximum height
	* `height` - default none; height=300
* Minimum Height - Set for minimum display height, in pixels
	* `min_height` - default none; min_height=100
* Maximum Height - Set for maximum display height, in pixels
	* `max_height` - default none; max_height=250
* Rotation speed - Seconds between testimonial rotations or 0 for no rotation at all
	* `refresh_interval` - default 5; refresh_interval=0

= Other Options =

**Post Type**

* Allow Comments? – Only affects the Testimonials Widget post edit page. Your theme controls the front-end view.
* Archive Page URL – URL slug-name for testimonials archive page. After changing, you must click "Save Changes" on Permalink Settings to update them.
* Testimonial Page URL – URL slug-name for testimonial view pages. After changing, you must click "Save Changes" on Permalink Settings to update them.

**Compatibility & Reset**

* Remove `.hentry` CSS? – Some themes use class `.hentry` in a manner that breaks Testimonials Widgets CSS
	* `remove_hentry` - default none; remove_hentry=true
* Use `<q>` tag? – Pre 2.11.0. Not HTML5 compliant
	* `use_quote_tag` - default none; use_quote_tag=true
* Remove Plugin Data on Deletion? - Delete all Testimonials Widget data and options from database on plugin deletion
* Reset to Defaults? – Check this box to reset options to their defaults

= Shortcode Examples =

[testimonialswidget_list]

* [testimonialswidget_list category=product hide_not_found=true]
* [testimonialswidget_list category=product tags=widget limit=5]
* [testimonialswidget_list char_limit=0 target=_new limit=3 disable_quotes=true]
* [testimonialswidget_list hide_source=true hide_url=true] 
* [testimonialswidget_list ids="1,11,111" paging=false]
* [testimonialswidget_list meta_key=testimonials-widget-company order=asc limit=15]
* [testimonialswidget_list order=ASC orderby=title]
* [testimonialswidget_list tags="test,fun" random=true exclude="2,22,333"]

[testimonialswidget_widget]

* [testimonialswidget_widget category=product order=asc height=300]
* [testimonialswidget_widget min_height=250 max_height=500]
* [testimonialswidget_widget tags=sometag random=true]

= Theme Functions =

* `testimonialswidget_list()` - Testimonials listing with paging 
* `testimonialswidget_widget()` - Rotating testimonials

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
* `testimonials_widget_next_posts_link_text` - Configure Next page indicator
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
* `testimonials_widget_widget_options` – Alters displayed widget options
* `testimonials_widget_wp_pagenavi` - Configure WP-PageNavi specifically for Testimonial Widgets

= Notes =

* Auto-migration from pre-2.0.0 custom table to new custom post type
	* Company, URL and email details are attempted to be identified and placed properly
	* Public testimonials are saved as Published. Non-public, are marked as Private.
	* Ignores already imported
* Default image size is based upon Thumbnail size in Media Settings 
* Gravatar image is configured in the Avatar section of Discussion Settings
* When plugin is uninstalled, all data and settings are deleted

= Languages =

You can translate this plugin into your own language if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) to the plugin author.

See the FAQ for further localization tips.

* Dutch by Bjorn Robijns
* [Hebrew by Ahrale](http://atar4u.com/)

= Recommendation =

* Use Jonathan Lundström's [Drag & Drop Featured Image](http://wordpress.org/extend/plugins/drag-drop-featured-image/) to speed up loading of the featured image

= Background & Thanks =

A big, special thank you to [Joe Weber](https://plus.google.com/100063271269277312276/posts) of [12 Star Creative](http://www.12starcreative.com/) for creating the Testimonials Widget banner. It's fantastic.

Version 2.0.0 of Testimonials Widget is a complete rewrite based upon a composite of ideas from user feedback and grokking the plugins [Imperfect Quotes](http://www.swarmstrategies.com/imperfect-quotes/), [IvyCat Ajax Testimonials](http://wordpress.org/extend/plugins/ivycat-ajax-testimonials/), [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/), and [TB Testimonials](http://travisballard.com/wordpress/tb-testimonials/). Thank you to these plugin developers for their efforts that have helped inspire this rewrite.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring meta boxes for your posts, pages or custom post types a snap.

Prior to version 2.0.0, this plugin was a fork of [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/) by [Srini G](http://wordpress.org/support/profile/SriniG) with additional contributions from [j0hnsmith](http://wordpress.org/support/profile/j0hnsmith), [ChrisCree](http://wordpress.org/support/profile/ChrisCree) and [comprock](http://wordpress.org/support/profile/comprock).

= Support =

So that others can share in the answer, please submit your support requests through the [WordPress forums for Testimonials Widget](http://wordpress.org/support/plugin/testimonials-widget).

If you want private or priority support, [please donate](http://aihr.us/about-aihrus/donate/) at least $ 65 USD to cover my time. Then send your [support request](http://aihr.us/contact-aihrus/).

Thank you for your understanding.


== Installation ==

1. Via WordPress Admin > Plugins > Add New, Upload the `testimonials-widget.zip` file
1. Alternately, via FTP, upload `testimonials-widget` directory to the `/wp-content/plugins/` directory
1. Activate the 'Testimonials Widget' plugin after uploading or through WordPress Admin > Plugins

= Usage =

1. Add and manage the quotes through the 'Testimonials' menu in the WordPress admin area
1. To display testimonials in the sidebar, go to 'Widgets' menu and drag the 'Testimonials Widget' into the desired widget area
1. Configure the 'Testimonials Widget' to select quotes and display as needed
1. Use the `[testimonialswidget_list]` or `[testimonialswidget_widget]` shortcodes to display testimonials on a page or in a post
1. Read FAQ 1 for `testimonialswidget_list()` and `testimonialswidget_widget()` theme functions usage


== Frequently Asked Questions ==

= Latest Major Changes =

* CSS class names are simplified and now WordPress coding standards compliant
	* For the most part, other than `testimonialswidget_testimonial` remove `testimonialswidget_` from the CSS class name in your CSS customizations.
	* Ex: `.testimonialswidget_join` becomes `.join`
	* Ex: `.testimonialswidget_author` becomes `.author`
* CSS and JavaScript renaming
	* `bottom_text` renamed to `bottom-text`
	* `close_quote` renamed to `close-quote`
	* `display_none` renamed to `display-none`
	* `join_location` renamed to `join-location`
	* `join_title` renamed to `join-title`
	* `open_quote` renamed to `open-quote`
	* `testimonialswidget_testimonial` renamed to `testimonials-widget-testimonial`
	* `testimonialswidget_testimonials` renamed to `testimonials-widget-testimonials`
* Rename filter `testimonials_widget_next_posts_link` to `testimonials_widget_next_posts_link_text`
* Testimonials are now formatted using `blockquote` than `q` for HTML5 compliance. If you need `q` tag formatting, enable it at WP Admin > Testimonials > Settings, Compatibility & Reset tab
	* `cite` is now `div.credit`

Read the [FAQ](http://aihr.us/testimonials-widget/faq/) for further help.

= I'm still stuck, how can I get help? =

Visit the [support forum](http://wordpress.org/support/plugin/testimonials-widget) and ask your question. Don't forget to include the URL of where to see the problem.


== Screenshots ==

1. Testimonials admin interface
2. Collapsed Testimonials Widget options
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
16. Testimonials Widget Settings > Widget tab
17. Testimonials Widget Settings > Compatibility & Reset tab


== Changelog ==

See [Changelog](http://aihr.us/testimonials-widget/changelog/)


== Upgrade Notice ==

= TBD =

* CSS and JavaScript renaming
	* `bottom_text` renamed to `bottom-text`
	* `close_quote` renamed to `close-quote`
	* `display_none` renamed to `display-none`
	* `join_location` renamed to `join-location`
	* `join_title` renamed to `join-title`
	* `open_quote` renamed to `open-quote`
	* `testimonialswidget_testimonial` renamed to `testimonials-widget-testimonial`
	* `testimonialswidget_testimonials` renamed to `testimonials-widget-testimonials`

= 2.11.3 =

* Correct filter name `testimonials_widget_next_posts_link` to `testimonials_widget_next_posts_link_text`

= 2.11.0 =

* CSS class names are simplified. For the most part, other than `testimonialswidget_testimonial` remove `testimonialswidget_` from the CSS class name in your CSS customizations.
	* Ex: `.testimonialswidget_join` becomes `.join`
	* Ex: `.testimonialswidget_author` becomes `.author`
* Testimonials are now formatted using `blockquote` than `q` for HTML5 compliance. If you need `q` tag formatting, enable it at WP Admin > Testimonials > Settings, Compatibility & Reset tab
	* `cite` is now `div.credit`

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

== Beta Testers Needed ==

I really want Testimonials Widget and Testimonials Widget Premium to be the best WordPress plugins of their type. However, it's getting beyond me to do it alone.

I need beta testers to help with ensuring pending releases of Testimonials Widget and Testimonials Widget Premium are solid. This would benefit us all by helping reduce the number of releases and raise code quality.

[Please contact me directly](http://aihr.us/contact-aihrus/).

Beta testers benefit directly with latest versions, free access to Testimonials Widget Premium, and personal support assistance by me.

== TODO ==

Is there something you want done? Write it up on the [support forums](http://wordpress.org/support/plugin/testimonials-widget) and then [donate](http://aihr.us/about-aihrus/donate/) or [write an awesome testimonial](http://aihr.us/about-aihrus/testimonials/add-testimonial/).
