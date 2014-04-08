=== Testimonials by Aihrus ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: client, customer, portfolio, quotations, quote, quotes, random, recommendation, reference, review, reviews, testimonial, testimonials, testimony, wpml
Requires at least: 3.6
Tested up to: 3.9.0
Stable tag: 2.19.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Testimonials by Aihrus lets you randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site.


== Description ==

Testimonials [by Aihrus](http://aihr.us/about-aihrus/) lets you randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site. You can insert Testimonials content via shortcode, theme functions, or widgets with category and tag selections and having multiple display options such as random or specific ordering. Further, [Review schema](http://schema.org/Review) for improved search engine results is built-in.

[youtube https://www.youtube.com/watch?v=bhUhuQ-2m8s]

**[Video introduction](https://www.youtube.com/watch?v=bhUhuQ-2m8s)**

**View a [Live Testimonials Demo](http://aihr.us/good-work-deserves-good-words-testimonials-examples/)**

= Testimonials Is Way Beyond Being Just a Testimonials Slider! =

* Slide Images - responsive image slideshow
* Slide Videos - video slideshow
* Slide Text - useful for rotating testimonials
* Slide Mixed Content: slideshow having video slides, images slides, and text slides. Each individual slide can also have mixed content.
* Shortcode compatible - slide content that is rendered using shortcodes. i.e. stock Worpdress Galleries and Gravity Forms
* Widget ready
* Shortcode ready 
* Random display
* Fade and slide transitions
* Filter slideshow content by WordPress' built in categories, tags, or post-ID

More than one Testimonials shortcode or widget instance can be displayed at a time pulls from the `testimonials-widget` custom post type. Additionally, with shortcodes and theme functions, you can display a short or long list or rotation of testimonials. Each Testimonial widget has its own CSS identifier for custom styling.

Widgets display content sans `wpautop` formatting. This means no forced paragraph breaks unless the content specifically contains them. You can enable `wpautop` via the "Keep whitespace?" option.

Through categories and tagging, you can create organizational structures based upon products, projects and services via categories and then apply tagging for further classification. As an example, you might create a Portfolio category and then use tags to identify web, magazine, media, public, enterprise niches. You can then configure the Testimonial instance to show only Portfolio testimonials with the public and enterprise tags. In another Testimonial instance, you can also select only Portfolio testimonials, but then allow web and media tags.

The single testimonial view supports image, source, title, location, email, company and URL details with optional Review schema.

= Primary Features =

* API to manipulate testimonials output and selection
* Admin interface to add, edit and manage testimonials
* Capable of handling multiple widgets per page or post
* Fields for source, testimonial, image, title, location, email, company and URL details
* Minimalist CSS styling for easier theming
* Multiple paging options for testimonials listings
* Schema.org microdata format for improved search engine results
* Settings export/import
* Settings screen for site-wide option defaults
* Shortcodes and theme functions for listings and rotation
* Single testimonial view includes image, source, title, location, email, company and URL details
* Testimonials archive view
* Use custom taxonomy or WordPress's own for categories and tags
* Uses [bxSlider](http://bxslider.com) for transitions

= Shortcodes =

* `[[testimonials]]` - Listings with paging 
* `[[testimonials_slider]]` - Rotating

= Theme Functions =

* `testimonials()` - Testimonials listing with paging 
* `testimonials_slider()` - Rotating testimonials

= Testimonials Migration Helpers =
* Testimonials Widget pre 2.0.0 - Upgrading is automatic
* [GC Testimonials](http://wordpress.org/plugins/gc-testimonials-to-testimonials/)
* [Testimonials by WooThemes](http://wordpress.org/plugins/wootheme-testimonials-to-testimonials/)

= Testimonials Premium =

Testimonials Premium adds onto the best WordPress testimonials plugin there is, [Testimonials](http://wordpress.org/extend/plugins/testimonials-widget/). Testimonials Premium offers [caching, excerpts, filters, read more links](http://aihr.us/products/testimonials-premium-wordpress-plugin/), more selection options, and advanced capabilities like using custom post types as testimonials. Additionally, testimonials support ratings and users can submit their own testimonials via a front-end form shortcode or widget.

[Buy Testimonials Premium](http://aihr.us/products/testimonials-premium-wordpress-plugin/) plugin for WordPress.

= Primary Premium Features =

* Akismet, math-based CAPTCHA, and more anti-spam traps
* Alternate destinations for "Read more" links
* Auto rotate testimonials by page
* CSS or HTML table based testimonials submissions form layout
* Caching of testimonials queries and content to decrease server load time improve page loading speed by 1/10 to 1/2 a second
* Carousel mode for rotating multiple testimonials at the same time
* Displays ratings to grant testimonials more power!
* Excerpts for widget view, with read more link to complete testimonial
* Front-end entry form for user supplied testimonials. **[Live form demo](http://aihr.us/about-aihrus/testimonials/add-testimonial/)**
* Select only testimonials with excerpts, images or of arbitrary maximum and minimum length
* Show unique testimonials when displaying multiple testimonial instances

= Premium Shortcodes =

* Count of testimonials `[[testimonials_count]]`
* List of testimonial source and title linking to full entry `[[testimonials_links]]` 
* Testimonials entry form `[[testimonials_form]]`

= Premium Theme Functions =

* `<?php echo testimonials_count( $args ); ?>`
* `<?php echo testimonials_form( $args ); ?>`
* `<?php echo testimonials_links( $args ); ?>`

[Buy Testimonials Premium](http://aihr.us/products/testimonials-premium-wordpress-plugin/) plugin for WordPress.

= Additional Features =

* Adjustable animation speed
* Archive Page URL and Testimonial Page URL are prevented from being the same or matching existing pages.
* Auto-suggest for category and tag options
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
* Easily label and link to reviewed item for Review schema
* Easy to configure Next and Previous page indicators
* Editors and admins can edit testimonial publisher
* Flush URLs on deactivation 
* Gravatars saved as featured images
* IDs column shown in testimonials edit page
* IE7 CSS hacks for quotes and join parts
* Image, email based Gravatar, category and tag enabled
* Internal version tracking for compatibility checks and automatic settings update
* Localizable via `languages/testimonials-widget.pot`
* Optional commenting on testimonial single-view pages
* Performance optimizations
* Respects meta capabilities
* Rotation JavaScript in footer than body
* Settings page contextual help
* Shortcode helpers on category and tag admin screens
* Shortcodes allowed inside of testimonials content
* Supports [WP-PageNavi](http://wordpress.org/extend/plugins/wp-pagenavi/)
* Testimonial content supports HTML
* Testimonial, email, and URL fields are clickable – URL requires `http://` or `https://` prefix
* Testimonials output is completely customizable via filters
* Testimonials support styling based upon CSS classes for category, tags and post id
* Testimonials widget displays static and rotating testimonials 
* Unique CSS class per widget
* URLs can be opened in new windows
* WordPress Multilingual enabled [WPML](http://wpml.org/)

= Shortcode Examples =

**[[testimonials]]**

* `[[testimonials category="category-name"]]` - Testimonial list by category
* `[[testimonials category=product hide_not_found=true]]` - Testimonial list by category and hide "No testimonials found" message
* `[[testimonials category=product tags=widget limit=5]]` - Testimonial list by tag, showing 5 at most
* `[[testimonials char_limit=0 limit=-1]]` - Show all testimonials on one page
* `[[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]]` - Show 3 full-length testimonials, with opening and closing quote marks removed
* `[[testimonials hide_source=true hide_url=true]]` - Show testimonial list with source and urls hidden
* `[[testimonials ids="1,11,111" paging=false]]` - Show only these 3 testimonials
* `[[testimonials meta_key=testimonials-widget-company order=asc limit=15]]` - Show 15 testimonials, in company order
* `[[testimonials order=ASC orderby=title]]` - List testimonials by post title
* `[[testimonials tags="test,fun" random=true exclude="2,22,333"]]` - Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids

**[[testimonials_slider]]**

* `[[testimonials_slider category=product order=asc]]` - Show rotating testimonials, of the product category, lowest post ids first
* `[[testimonials_slider tags=sometag random=true]]` - Show rotating, random testimonials having tag "sometag"

= Shortcode and Widget Options =

Please keep in mind that inheritance affects options. This means that changes made to Testimonials > Settings will not affect current widget instance options. You must change the widget instance manually. However, if you create a new widget instance, then the global settings will apply.

Further, global settings are the baseline for shortcodes. If you want to alter the shortcode appearance, then alter the shortcode options directly.

**General**

* Enable Review Schema? – Adds HTML tag markup per the [Review schema](http://schema.org/Review) to testimonials. Search engines including Bing, Google, Yahoo! and Yandex rely on this markup to improve the display of search results.
	* `enable_schema` - default true; enable_schema=false
* Default Reviewed Item? - Name of thing being referenced in testimonials
	* `item_reviewed` - default "Site Title"
* Default Reviewed Item URL? - URL of thing being referenced in testimonials
	* `item_reviewed_url` - default `network_site_url();`
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
* Hide Testimonial Content?
	* `hide_content` - default show; hide_content=true
* Hide Author/Source? - Don't display "Post Title" in cite
	* `hide_source` - default show; hide_source=true
* Hide Email?
	* `hide_email` - default show; hide_email=true
* Hide Job Title?
	* `hide_title` - default show; hide_title=true
* Hide Location?
	* `hide_location` - default show; hide_location=true
* Hide Company?
	* `hide_company` - default show; hide_company=true
* Hide URL?
	* `hide_url` - default show; hide_url=true
* Exclude bxSlider CSS? - For a bare-bones, unthemed slider.
	* `exclude_bxslider_css` - default show; exclude_bxslider_css=true
* URL Target - Add target to all URLs; leave blank if none
	* `target` - default none; target=_new
* Enable Paging? - for [[testimonials]]
	* `paging` - default true [true|before|after|false]; paging=false
		* `true` – display paging before and after testimonial entries
		* `before` – display paging only before testimonial entries
		* `after` – display paging only after testimonial entries
	* Widget - Not functional
* Enable [shortcodes]? - If unchecked, shortcodes are stripped.
	* `do_shortcode` - default false; do_shortcode=true
* Include IE7 CSS?
* Use `<q>` tag? – Not HTML5 compliant
	* `use_quote_tag` - default none; use_quote_tag=true
* Remove `.hentry` CSS? – Some themes use class `.hentry` in a manner that breaks Testimonials' CSS and corrupts microdata parsing
	* `remove_hentry` - default true; remove_hentry=false

**Selection**

* Category Filter - Comma separated category names
	* `category` - default none; category=product or category="category-a, another-category"
* Tags Filter - Comma separated tag names
	* `tags` - default none; tags=fire or tags="tag-a, another-tag"
* Require All Tags - Select only testimonials with all of the given tags
	* `tags_all` - default OR; tags_all=true
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
* Character Limit - Number of characters to limit testimonial views to
	* `char_limit` - default none; char_limit=200
	* Widget - default 500
* Rotation speed - Seconds between testimonial rotations or 0 for no rotation at all
	* `refresh_interval` - default 5; refresh_interval=0
* Transition Mode? - Type of transition between slides
	* `transition_mode` - default fade; transition_mode=horizontal|vertical|fade
* Show Play/Pause? - Display start and stop buttons underneath the testimonial slider.
	* `show_start_stop` - default true; show_start_stop=false
* Enable Video? - Only enable when displaying video content.
	* `enable_video` - default false; enable_video=true
* Keep Whitespace? - Keeps testimonials looking as entered than sans auto-formatting
	* `keep_whitespace` - default none; keep_whitespace=true
	* The citation has no whitespace adaptations. It's straight text, except for email or URL links. The presentation is handled strictly by CSS.
* Testimonial Bottom Text - Custom text or HTML for bottom of testimonials
	* `bottom_text` - default none; bottom_text="`&lt;h3&gt;&lt;a href="http://example.com"&gt;All testimonials&lt;/a&gt;&lt;/h3&gt;`"

**Post Type**

* Allow Comments? – Only affects the Testimonials post edit page. Your theme controls the front-end view.
* Archive Page URL – URL slug-name for testimonials archive page. After changing, you must click "Save Changes" on Permalink Settings to update them.
* Testimonial Page URL – URL slug-name for testimonial view pages. After changing, you must click "Save Changes" on Permalink Settings to update them.

**Reset**

* Don't Use Default Taxonomies? – If checked, use Testimonials' own category and tag taxonomies instead
* Export Settings – These are your current settings in a serialized format. Copy the contents to make a backup of your settings.
* Import Settings – Paste new serialized settings here to overwrite your current configuration.
* Remove Plugin Data on Deletion? - Delete all Testimonials data and options from database on plugin deletion
* Reset to Defaults? – Check this box to reset options to their defaults

**Version Based Options**

* Use bxSlider? - Pre 2.15.0, Testimonials' used custom JavaScript for transitions.
* Disable Animation? - Disable animation between testimonial transitions. Useful when stacking.
	* `disable_animation` - default false; disable_animation=true
* Fade Out Speed - Transition duration in milliseconds; higher values indicate slower animations, not faster ones.
	* `fade_out_speed` - default 1250; fade_out_speed=400
* Fade In Speed - Transition duration in milliseconds; higher values indicate slower animations, not faster ones.
	* `fade_in_speed` - default 500; fade_in_speed=800
* Height - Testimonials height, in pixels. Overrides minimum and maximum height
	* `height` - default none; height=300
* Minimum Height - Set for minimum display height, in pixels
	* `min_height` - default none; min_height=100
* Maximum Height - Set for maximum display height, in pixels
	* `max_height` - default none; max_height=250


== Installation ==

= Requirements =

* [jQuery 1.10+](https://aihrus.zendesk.com/entries/23693363)
* PHP 5.3+ [Read notice](https://aihrus.zendesk.com/entries/30678006) – Since 2.16.0

= Install Methods =

* Through WordPress Admin > Plugins > Add New, Search for "Testimonials Widget"
	* Find "Testimonials by Aihrus"
	* Click "Install Now" of "Testimonials by Aihrus"
* Download [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `testimonials-widget.zip`
	* Click "Install Now"
* Download and unzip [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Using FTP, upload directory `testimonials-widget` to your website's `/wp-content/plugins/` directory

= Activation Options =

* Activate the "Testimonials" plugin after uploading
* Activate the "Testimonials" plugin through WordPress Admin > Plugins

= Usage =

1. Read [How do I create a testimonial record?](https://aihrus.zendesk.com/entries/30602506)
1. Add and manage testimonials through the "Testimonials" menu in the WordPress admin area
1. To display testimonials in the sidebar, go to "Widgets" menu and drag "Testimonials" widget into the desired widget area
1. Configure "Testimonials" to select quotes and display as needed
1. Use the `[[testimonials]]` or `[[testimonials_slider]]` shortcodes to display testimonials on a page or in a post
1. Read [theme functions usage](https://aihrus.zendesk.com/entries/23702878) for `testimonials()` and `testimonials_slider()`

= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Upload directory `testimonials-widget` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `testimonials-widget` folder contents


== Frequently Asked Questions ==

= Most Common Issues =

* Got `Parse error: syntax error, unexpected T_STATIC, expecting ')'`? Read [Most Aihrus Plugins Require PHP 5.3+](https://aihrus.zendesk.com/entries/30678006) for the fixes.
* [404 - Page not found](https://aihrus.zendesk.com/entries/23679301)
* [Change or debug CSS](https://aihrus.zendesk.com/entries/24910733) AKA "What's up with these quotes?"
* [Customize bxSlider](http://bxslider.com/examples)
* [Debug common theme and plugin conflicts](https://aihrus.zendesk.com/entries/25119302)
* [How do I change my widget's rotation speed or other options?](https://aihrus.zendesk.com/entries/27714083)
* [How do I create a testimonial record?](https://aihrus.zendesk.com/entries/30602506)
* [How to change testimonials layout](https://aihrus.zendesk.com/entries/38055707)
* [My options don't work, no matter what I do.](https://aihrus.zendesk.com/entries/30746533)
* [Pagination is broken](https://aihrus.zendesk.com/entries/23693513)
* [Test Review schema output](http://www.google.com/webmasters/tools/richsnippets)
* [Widgets don't rotate](https://aihrus.zendesk.com/entries/23693363)

= Still Stuck or Want Something Done? Get Support! =

1. [Testimonials Knowledge Base](https://aihrus.zendesk.com/categories/20104507) - read and comment upon 125+ frequently asked questions
1. [Open Testimonials Issues](https://github.com/michael-cannon/testimonials-widget/issues) - review and submit bug reports and enhancement requests
1. [Testimonials Support on WordPress](http://wordpress.org/support/plugin/testimonials-widget) - ask questions and review responses
1. [Contribute Code to Testimonials](https://github.com/michael-cannon/testimonials-widget/blob/master/CONTRIBUTING.md)
1. [Beta Testers Needed](http://aihr.us/become-beta-tester/) - get the latest Testimonials version


== Screenshots ==

1. Testimonials admin interface
2. Collapsed Testimonials options
3. Expanded "General Options" in Testimonials options
4. Testimonial widget in the sidebar 
5. [[testimonials]] in post
6. [[testimonials]] results with paging
7. Widget whitespace kept
8. Widget with clickable title and custom text/HTML on bottom
9. [WP-PageNavi compatible](http://wordpress.org/extend/plugins/wp-pagenavi/) for page numbers than default arrows
10. Poedit Catalog properties
11. Testimonials Settings > General tab
12. Expanded "Selection Options" in Testimonials options
13. Expanded "Ordering Options" in Testimonials options
14. Testimonials Settings > Selection tab
15. Testimonials Settings > Post Type tab
16. Testimonials Settings > Widget tab
17. Testimonials Settings > Compatibility & Reset tab
18. Dashboard > Right Now "Testimonials" count
19. Using Review and AggregateRating schema data structures
20. Testimonials Shortcode Examples page
21. Shortcode helpers on category and tag admin screens
22. Testimonials Settings > Columns tab

[gallery]


== Changelog ==

See [Changelog](https://github.com/michael-cannon/testimonials-widget/blob/master/CHANGELOG.md)


== Upgrade Notice ==

= 2.19.0 =

* CSS class `.title` is now `.job-title`. Thank you Mark
* Please resave your WordPress Admin > Testimonials > Settings so that missing aoptions are included again.
* Shortcode and theme function `testimonialswidget_list` being deprecated by `testimonials`
* Shortcode and theme function `testimonialswidget_widget` being deprecated by `testimonials_slider`

= 2.18.3 =

* CSS class `.hide` renamed `.display-none`
* This is the last version supporting pre-bxSlider options

= 2.18.2 =

* CSS class `.display-none` renamed `.hide`

= 2.18.1 =

* CSS is back to being always loaded in the header
* Removed "Use bxSlider?" and "Include IE7 CSS" from widget options

= 2.18.0 =

* `remove_hentry` is now true by default

= 2.16.0 =

* [Requires PHP 5.3+](https://aihrus.zendesk.com/entries/30678006)

= 2.15.0 =

* If upgrading, bxSlider will not be enabled by default. You must enable it in your widget and global settings. CSS customizations must be reviewed to have the `.active` and `.display-none` classes removed. The main `.testimonials-widget-testimonial` class also need the `display: none;` and `clear: left;` removed.

= 2.14.0 =

* **60 modifications** See [Changelog](https://github.com/michael-cannon/testimonials-widget/blob/master/CHANGELOG.md)
* CSS wp_register_style and wp_enqueue_style slug changed from 'testimonials-widget' to 'Testimonials_Widget'
* Gravatar image size now based upon Thumbnail size in Media Settings
* Scripts `ksort` removed. Use `array_unshift` in your `testimonials_widget_testimonials_js` filters instead.
* Testimonials > Settings, General tab, option Enable Review Schema? is enabled by default.

= 2.13.6 =

* IE 7 CSS moved to separate file. Include via Testimonials > Settings if needed

= 2.12.0 =

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


== Notes ==

* Default and Gravatar image size is based upon Thumbnail size in Media Settings
* Migration from from pre-2.0.0 custom table to new custom post type is automatic
* Review schema [structured data testing tool](http://www.google.com/webmasters/tools/richsnippets)
* When plugin is uninstalled, all data and settings are deleted if "Remove Plugin Data on Deletion" is checked in Settings


== API ==

* Read the [Testimonials API](https://github.com/michael-cannon/testimonials-widget/blob/master/API.md).


== Background ==

Version 2.0.0 of Testimonials is a complete rewrite based upon a composite of ideas from user feedback and grokking the plugins [Imperfect Quotes](http://www.swarmstrategies.com/imperfect-quotes/), [IvyCat Ajax Testimonials](http://wordpress.org/extend/plugins/ivycat-ajax-testimonials/), [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/), and [TB Testimonials](http://travisballard.com/wordpress/tb-testimonials/). Thank you to these plugin developers for their efforts that have helped inspire this rewrite.

Prior to version 2.0.0, this plugin was a fork of [Quotes Collection](http://srinig.com/wordpress/plugins/quotes-collection/) by [Srini G](http://wordpress.org/support/profile/SriniG) with additional contributions from [j0hnsmith](http://wordpress.org/support/profile/j0hnsmith), [ChrisCree](http://wordpress.org/support/profile/ChrisCree) and [comprock](http://wordpress.org/support/profile/comprock).


== Conflicts ==

* [ReOrder Post Within Categories](http://wordpress.org/plugins/reorder-post-within-categories/) – Uses custom ordering table


== Deprecation Notices ==

= Deprecated Shortcodes =

* `[[testimonialswidget_list]]` - Listings with paging 
* `[[testimonialswidget_widget]]` - Rotating

= Deprecated Theme Functions =

* `testimonialswidget_list()` - Testimonials listing with paging 
* `testimonialswidget_widget()` - Rotating testimonials


== Localization ==

* Dutch by Bjorn Robijns
* [Hebrew by Ahrale](http://atar4u.com/)

You can translate this plugin into your own language if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) for plugin inclusion.

**[How do I localize?](https://aihrus.zendesk.com/entries/23691557)**


== Recommendation ==

* Use Jonathan Lundström's [Drag & Drop Featured Image](http://wordpress.org/extend/plugins/drag-drop-featured-image/) to speed up loading of the featured image


== Thank You ==

A big, special thank you to [Joe Weber](https://plus.google.com/100063271269277312276/posts) of [12 Star Creative](http://www.12starcreative.com/) for creating the Testimonials banner. It's fantastic.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring meta boxes for your posts, pages or custom post types a snap.
