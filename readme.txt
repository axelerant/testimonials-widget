=== Testimonials Widget ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: aihrus, client, customer, portfolio, quote, quotes, random, recommendation, reference, review, reviews, slider, testimonial, testimonials, wpml
Requires at least: 3.6
Tested up to: 4.1.0
Stable tag: 3.0.1RC1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add social proofing to your website with Testimonials Widget. List or slide reviews via functions, shortcodes, or widgets.


== Description ==

Testimonials Widget lets you randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site. You can insert Testimonials content via shortcode, theme functions, or widgets with category and tag selections and having multiple display options such as random or specific ordering. Also, [Review schema](http://schema.org/Review) for improved search engine results is built-in.

= Testimonials Widget is Beyond a Simple WordPress Testimonials Slider! =

* Carousel, fade, and slide transitions
* Filter slideshow content by WordPress' categories, tags, or post ID
* Random display
* Shortcode compatible - slide content that is rendered using shortcodes. e.g. Worpdress Galleries and Gravity Forms
* Simply styled for easy theme adaption
* Slide images - responsive image slideshow
* Slide mixed content - responsively slide images, text, and video together
* Slide text - useful for rotating testimonials
* Slide videos - video slideshow
* Widget and shortcode ready 

**Video Introduction**

[youtube https://www.youtube.com/watch?v=bhUhuQ-2m8s]

**View a [Live Testimonials Widget Demo](http://aihr.us/testimonials-examples/)**

= Testimonials Widget Premium =

Testimonials Widget Premium increases all of Testimonials Widget' benefits and features to over a 100 with…

* 5-star ratings
* Caching
* Excerpts
* Free, comprehensive support
* More selection options
* Read more links
* RSS feeds
* Shortcodes and theme functions for testimonials count, form, and links summary
* Use any post types for testimonials
* User submitted testimonials

**[Buy Testimonials Widget Premium](http://aihr.us/downloads/best-wordpress-testimonials-plugin-testimonials-premium/)** plugin for WordPress.

= Testimonials Widget Premium Doesn't Work For You? =

No problem, get a 100% refund and keep the software. Your license for support and updates will be revoked though.

= General Information =

More than one testimonials shortcode or widget instance can be displayed at a time on a page. Additionally, with shortcodes and theme functions, you can display a short or long list or rotation of testimonials anywhere you need. 

Through categories and tagging, you can create organizational structures. Using categories for products, projects and services you can then apply tagging for further classification.

As an example, create a **Portfolio** category and then use tags to identify _web_, _magazine_, _media_, _public_, _enterprise_, or other niches. Then configure the Testimonial instance to show only **Portfolio** testimonials with the _public_ and _enterprise_ tags. In another Testimonial instance, you can also select only **Portfolio** testimonials, but then allow _web_ and _media_ tags.

For easy custom styling, each testimonial widget has its own CSS class identifier.

Widgets display content sans `wpautop` formatting. This means there's no forced paragraph breaks unless the content specifically contains them. If needed, you can enable `wpautop` via the "Keep whitespace?" option.

The single testimonial view supports image, author, title, location, email, company and URL details with optional Review schema.

= Shortcodes =

* `[testimonials]` - Testimonials listings with paging 
* `[testimonials_archives]` - A monthly archive of your site's testimonials
* `[testimonials_categories]` - A list or dropdown of testimonials' categories
* `[testimonials_examples]` - Displays examples of commonly used testimonials' shortcodes with attributes
* `[testimonials_options]` - Displays summary of testimonials' settings for use with shortcodes and theme functions
* `[testimonials_recent]` - Your site's most recent testimonials
* `[testimonials_slider]` - Displays rotating testimonials or statically
* `[testimonials_tag_cloud]` - A cloud of your most used testimonials' tags

= Theme Functions =

* `<?php echo testimonials(); // Testimonials listing with paging  ?>`
* `<?php echo testimonials_archives(); // A monthly archive of your site's testimonials ?>`
* `<?php echo testimonials_categories(); // A list or dropdown of testimonials' categories ?>`
* `<?php echo testimonials_examples(); // Displays examples of commonly used testimonials' shortcodes with attributes ?>`
* `<?php echo testimonials_options(); // Displays summary of testimonials' settings for use with shortcodes and theme functions ?>`
* `<?php echo testimonials_recent(); // Your site's most recent testimonials ?>`
* `<?php echo testimonials_slider(); // Displays rotating testimonials or statically ?>`
* `<?php echo testimonials_tag_cloud(); // A cloud of your most used testimonials' tags ?>`

= Shortcode Examples =

Read [EXAMPLES](https://github.com/michael-cannon/testimonials-widget/blob/master/EXAMPLES.md).

= Shortcode and Widget Options =

Please keep in mind that inheritance affects options. This means that changes made to Testimonials > Settings will not affect current widget instance options. You must change the widget instance manually. However, if you create a new widget instance, then the global settings will apply.

Further, global settings are the baseline for shortcodes. If you want to alter the shortcode appearance, then alter the shortcode options directly.

Details on the Shortcode Attributes tab at WordPress Admin > Testimonials > Settings.

= Testimonials Widget Migration Helpers =

* Testimonials Widget pre 2.0.0 - Install and activate Testimonials Widget 2.19.0 before installing the latest Testimonials Widget for automatic upgrading.
* [GC Testimonials](http://wordpress.org/plugins/gc-testimonials-to-testimonials/)
* [Testimonials by WooThemes](http://wordpress.org/plugins/wootheme-testimonials-to-testimonials/)

= 50+ Features =

* Adjustable animation speed
* Admin interface to add, edit and manage testimonials
* API to manipulate testimonials output and selection
* Archive Page URL and Testimonial Page URL are prevented from being the same or matching existing pages.
* Author field can override testimonial title
* Auto-suggest for category and tag options
* Automatic linking of email and URL fields via author or company fields
* Capable of handling multiple widgets per page or post
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
* Customizable views via filters and template files
* Date based archives
* Deletes testimonials-widget custom post type entries and settings on uninstall
* Disable self-generated quotation marks
* Easily label and link to reviewed item for Review schema
* Easy to configure Next and Previous page indicators
* Editors and admins can edit testimonial publisher
* Fields for author, testimonial, image, title, location, email, company and URL details
* Flush URLs on deactivation 
* Gravatars saved as featured images
* IDs column shown in testimonials edit page
* IE7 CSS hacks for quotes and join parts
* Image, email based Gravatar, category and tag enabled
* Include testimonials in archive and category views.
* Internal version tracking for compatibility checks and automatic settings update
* Localizable via `languages/testimonials-widget.pot`
* Lots of examples at WordPress Admin > Testimonials > Settings, Examples tab
* Minimalist CSS styling for easier theming
* Multiple paging options for testimonials listings
* Optional commenting on testimonial single-view pages
* Performance optimizations
* Recent testimonials widget
* Respects meta capabilities
* Rotation JavaScript in footer than body
* Schema.org microdata format for improved search engine results
* Settings export/import
* Settings page contextual help
* Settings screen for site-wide option defaults
* Shortcode helpers on category and tag admin screens
* Shortcodes allowed inside of testimonials content
* Shortcodes and theme functions for listings and rotation
* Single testimonial view includes image, author, title, location, email, company and URL details
* Supports [WP-PageNavi](http://wordpress.org/extend/plugins/wp-pagenavi/)
* Testimonial content supports HTML
* Testimonial, email, and URL fields are clickable – URL requires `http://` or `https://` prefix
* Testimonials archive view
* Testimonials category widget
* Testimonials output is completely customizable via filters
* Testimonials slider widget displays rotating testimonials or statically
* Testimonials support styling based upon CSS classes for category, tags and post id
* Testimonials tag cloud widget
* Unique CSS class per widget
* URLs can be opened in new windows
* Use custom taxonomy or WordPress's own for categories and tags
* Uses [bxSlider](http://bxslider.com) for transitions
* Widgets for testimonial's archives, categories, recent, slider, and tag cloud
* WordPress Multilingual enabled [WPML](http://wpml.org/)


== Installation ==

= Requirements =

* PHP 5.3+ [Read notice](https://aihrus.zendesk.com/entries/30678006) – Since 2.16.0
* WordPress 3.6+
* [jQuery 1.10+](https://aihrus.zendesk.com/entries/23693363)

= Install Methods =

* Through WordPress Admin > Plugins > Add New, Search for "Testimonials Widget"
	* Find "Testimonials Widget"
	* Click "Install Now" of "Testimonials Widget"
* Download [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `testimonials-widget.zip`
	* Click "Install Now"
* Download and unzip [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Using FTP, upload directory `testimonials-widget` to your website's `/wp-content/plugins/` directory

= Activation =

* Click the "Activate" link for "Testimonials Widget" at WordPress Admin > Plugins

= Usage =

1. Watch [Testimonial Widget](https://www.youtube.com/watch?v=zDz1igmRK2g)
1. Read [How do I create a testimonial record?](https://aihrus.zendesk.com/entries/30602506)
1. Add and manage testimonials through the **Testimonials Widget** menu in the WordPress admin area
1. To display testimonials as a widget, go to the **Appearance** > **Widgets** menu and drag the **Testimonials Slider** widget into the desired widget area. Then configure the widget to select options and display as desired.
1. Use the following shortcodes to display testimonials on a page, post, etc.
	* `[testimonials]` - Testimonials listings with paging 
	* `[testimonials_archives]` - A monthly archive of your site's testimonials
	* `[testimonials_categories]` - A list or dropdown of testimonials' categories
	* `[testimonials_examples]` - Displays examples of commonly used testimonials' shortcodes with attributes
	* `[testimonials_options]` - Displays summary of testimonials' settings for use with shortcodes and theme functions
	* `[testimonials_recent]` - Your site's most recent testimonials
	* `[testimonials_slider]` - Displays rotating testimonials or statically
	* `[testimonials_tag_cloud]` - A cloud of your most used testimonials' tags
1. Read [theme functions usage](https://aihrus.zendesk.com/entries/23702878) for using the following theme helpers.
	* `<?php echo testimonials(); // Testimonials listing with paging  ?>`
	* `<?php echo testimonials_archives(); // A monthly archive of your site's testimonials ?>`
	* `<?php echo testimonials_categories(); // A list or dropdown of testimonials' categories ?>`
	* `<?php echo testimonials_examples(); // Displays examples of commonly used testimonials' shortcodes with attributes ?>`
	* `<?php echo testimonials_options(); // Displays summary of testimonials' settings for use with shortcodes and theme functions ?>`
	* `<?php echo testimonials_recent(); // Your site's most recent testimonials ?>`
	* `<?php echo testimonials_slider(); // Displays rotating testimonials or statically ?>`
	* `<?php echo testimonials_tag_cloud(); // A cloud of your most used testimonials' tags ?>`
1. See the **Shortcode and Widget Options** section for configuring shortcode and theme function attributes.

= Tutorials =

* [Adding or Editing Testimonial Widget on Your WordPress Website](https://www.youtube.com/watch?v=YaXCJppYOUM)
* [Adding Rotating Testimonials to WordPress Tutorial](https://www.youtube.com/watch?v=QoFCZgLAUSE)
* [Testimonials Widget - WordPress Training Video](https://www.youtube.com/watch?v=ybV5mKZlegA)
* [Testimonials Widget for WordPress](https://www.youtube.com/watch?v=LqZ-vcZD8E0)
* [WordPress Testimonial Plugin Update](https://www.youtube.com/watch?v=b81oz7k1wbM)

= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip [`testimonials-widget.zip`](http://downloads.wordpress.org/plugin/testimonials-widget.zip) locally
	* Upload directory `testimonials-widget` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `testimonials-widget` folder contents

= Deactivation =

* Click the "Deactivate" link for "Testimonials Widget" at WordPress Admin > Plugins

= Deletion =

* Click the "Delete" link for "Testimonials Widget" at WordPress Admin > Plugins
* Click the "Yes, Delete these files and data" button to confirm "Testimonials Widget" plugin removal


== Frequently Asked Questions ==

= Testimonials Widget 3.0 Upgrading =

This is a major overhaul *without* backwards compliance of over 80 changes. Please read the [Testimonials Widget 3.0 and Testimonials Widget Premium 2.0 Upgrade Notice](https://aihrus.zendesk.com/entries/52514055) for more help. 

= Most Common Issues =

* Got `Parse error: syntax error, unexpected T_STATIC…`? See [Most Aihrus Plugins Require PHP 5.3+](https://aihrus.zendesk.com/entries/30678006)
* Shortcode not working? Make sure your theme isn't using same shortcode.
* [404 - Page not found](https://aihrus.zendesk.com/entries/23679301)
* [Add testimonials using Gravity Forms](http://webtrainingwheels.com/how-to-collect-user-submitted-testimonials-wordpress/)
* [Change styling or debug CSS](https://aihrus.zendesk.com/entries/24910733)
* [Customize bxSlider](http://bxslider.com/examples)
* [Debug theme and plugin conflicts](https://aihrus.zendesk.com/entries/25119302)
* [How do I create a testimonial record?](https://aihrus.zendesk.com/entries/30602506)
* [How do I reset options?](https://aihrus.zendesk.com/entries/30746533)
* [How to change testimonials layout](https://aihrus.zendesk.com/entries/38055707)
* [My options don't work](https://aihrus.zendesk.com/entries/30746533)
* [Pagination is broken](https://aihrus.zendesk.com/entries/23693513)
* [Test Review schema output](http://www.google.com/webmasters/tools/richsnippets)
* [Testimonials slider doesn't rotate](https://aihrus.zendesk.com/entries/23693363)

= Still Stuck or Want Something Done? Get Support! =

1. [Knowledge Base](https://aihrus.zendesk.com/categories/20104507) - read and comment upon 125+ frequently asked questions
1. [Open Issues](https://github.com/michael-cannon/testimonials-widget/issues) - review and submit bug reports and enhancement requests
1. [Support on WordPress](http://wordpress.org/support/plugin/testimonials-widget) - ask questions and review responses
1. [Contribute Code](https://github.com/michael-cannon/testimonials-widget/blob/master/CONTRIBUTING.md)
1. [Beta Testers Needed](http://aihr.us/become-beta-tester/) - provide feedback and direction to plugin development
1. [Old Plugin Versions](http://wordpress.org/plugins/testimonials-widget/developers/)


== Screenshots ==

1. Testimonials Slider widget in use
2. [testimonials_slider] demo
3. [testimonials] demo
4. [testimonials_archives] demo
5. [testimonials_categories] demo
6. [testimonials_tag_cloud] demo
7. [testimonials_recent] demo
8. Single page testimonial view
9. Testimonials edit page
10. Testimonials Settings page
11. Testimonials Categories page
12. Poedit Catalog properties
13. Using Review schema data structures

[gallery]


== Changelog ==

Read [CHANGELOG](https://github.com/michael-cannon/testimonials-widget/blob/master/CHANGELOG.md).

Read [UPGRADING](https://github.com/michael-cannon/testimonials-widget/blob/master/UPGRADING.md).


== Upgrade Notice ==

= 3.0.0 =

This is a major overhaul *without* backwards compliance of over 80 changes. Please read the [Testimonials Widget 3.0 and Testimonials Widget Premium 2.0 Upgrade Notice](https://aihrus.zendesk.com/entries/52514055) for more help. 

If you use custom CSS, actions, or filters to modify Testimonials Widget and Testimonials Widget Premium actions or output, this upgrade will not be compatible with those modifications until corrections are made.

= Older Versions =

Read [UPGRADING](https://github.com/michael-cannon/testimonials-widget/blob/master/UPGRADING.md).


== API ==

* Read [API](https://github.com/michael-cannon/testimonials-widget/blob/master/API.md).


== Conflicts ==

* [ReOrder Post Within Categories](http://wordpress.org/plugins/reorder-post-within-categories/) – Uses custom ordering table


== Deprecation Notices ==

Read [DEPRECATED](https://github.com/michael-cannon/testimonials-widget/blob/master/DEPRECATED.md).


== Localization ==

* Dutch by Bjorn Robijns
* [Hebrew by Ahrale](http://atar4u.com/)

You can translate this plugin into your own language if it's not done so already. The localization file `testimonials-widget.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) for plugin inclusion.

**[How do I localize?](https://aihrus.zendesk.com/entries/23691557)**


== Notes ==

* Default and Gravatar image size is based upon Thumbnail size in Media Settings
* Review schema [structured data testing tool](http://www.google.com/webmasters/tools/richsnippets)
* When plugin is uninstalled, all data and settings are deleted if "Remove Plugin Data on Deletion" is checked in Settings


== Recommendation ==

* Use Jonathan Lundström's [Drag & Drop Featured Image](http://wordpress.org/extend/plugins/drag-drop-featured-image/) to speed up loading of the featured image


== Thank You ==

A big, special thank you to [Joe Weber](https://plus.google.com/100063271269277312276/posts) of [12 Star Creative](http://www.12starcreative.com/) for creating the Testimonials Widget banner. It's fantastic.

A cool thanks to RedRokk Library for the [redrokk_metabox_class](https://gist.github.com/1880770). It makes configuring meta boxes for your posts, pages or custom post types a snap.
