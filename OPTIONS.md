# Shortcode and Widget Options - Aihrus Testimonials

## General

* Use bxSlider? - Prior to 2.15.0, Testimonials' used custom JavaScript for transitions.
* Exclude bxSlider CSS? - For a bare-bones, unthemed slider.
	* `exclude_bxslider_css` - default false; exclude_bxslider_css=true
* Exclude default CSS? - Prevent default CSS from being loaded.
	* `exclude_css` - default false; exclude_css=true
* Include IE7 CSS?
* Hide built-in quotes? - Remove open and close quote span tags surrounding testimonial content.
	* `disable_quotes` - default false; disable_quotes=true
* Remove `.hentry` CSS? – Some themes use class `.hentry` in a manner that breaks Testimonials' CSS and corrupts microdata parsing.
	* `remove_hentry` - default true; remove_hentry=false
* Use `<q>` tag? – Not HTML5 compliant.
	* `use_quote_tag` - default none; use_quote_tag=true

### Fields to Show

* Hide Gravatar Image? - Don't display Gravatar image with testimonial.
	* `hide_gravatar` - default show; hide_gravatar=true
* Hide Image? - Don't display featured image with testimonial.
	* `hide_image` - default show; hide_image=true
* Hide Image in Single View?
	* `hide_image_single` - default show; hide_image_single=true
* Hide Testimonial Content? - Don't display testimonial content in a view.
	* `hide_content` - default show; hide_content=true
* Hide Source? - Don't display testimonial title in cite.
	* `hide_source` - default show; hide_source=true
* Hide Job Title? - Don't display testimonial job title in cite.
	* `hide_title` - default show; hide_title=true
* Hide Location? - Don't display testimonial location in cite.
	* `hide_location` - default show; hide_location=true
* Hide Company? - Don't display testimonial company in cite.
	* `hide_company` - default show; hide_company=true
* Hide Email? - Don't display or link to testimonial email in cite.
	* `hide_email` - default show; hide_email=true
* Hide URL? - Don't display or link to testimonial URL in cite.
	* `hide_url` - default show; hide_url=true

### Miscellaneous

* Default Reviewed Item? - Name of thing being referenced in testimonials.
	* `item_reviewed` - default "Site Title"
* Default Reviewed Item URL? - URL of thing being referenced in testimonials.
	* `item_reviewed_url` - default `network_site_url();`
* Enable Paging? - Show paging controls for `[testimonials]` listing.
	* `paging` - default true [true|before|after|false]; paging=false
		* `true` – display paging before and after testimonial entries
		* `before` – display paging only before testimonial entries
		* `after` – display paging only after testimonial entries
	* Widget - Not functional
* Enable Review Schema? – Adds HTML tag markup per the [Review schema](http://schema.org/Review) to testimonials. Search engines including Bing, Google, Yahoo! and Yandex rely on this markup to improve the display of search results.
	* `enable_schema` - default true; enable_schema=false
* Enable Video? - Only enable when displaying video content.
	* `enable_video` - default false; enable_video=true
* Enable [shortcodes]? - If unchecked, shortcodes are stripped.
	* `do_shortcode` - default false; do_shortcode=true
* Hide "Testimonials Not Found"?
	* `hide_not_found` - default show; hide_not_found=true
* URL Target - Add target to all URLs; leave blank if none.
	* `target` - default none; target=_new

## Selection

* Category Filter - Comma separated category names.
	* `category` - default none; category=product or category="category-a, another-category"
* Tags Filter - Comma separated tag names.
	* `tags` - default none; tags=fire or tags="tag-a, another-tag"
* Require All Tags - Select only testimonials with all of the given tags.
	* `tags_all` - default OR; tags_all=true
* Include IDs Filter - Comma separated IDs.
	* `ids` - default none; ids=2 or ids="2,4,6"
* Exclude IDs Filter - Comma separated IDs.
	* `exclude` - default none; exclude=2 or exclude="2,4,6"
* Limit - Number of testimonials to rotate through via widget or show at a time when listing.
	* `limit` - default 10; limit=25

## Ordering

* Random Order? - Unchecking this will rotate testimonials per ORDER BY and ORDER BY Order. Widgets are random by default automatically.
	* `random` - default false; random=true (overrides `order` and `orderby`)
	* Widget - default true
* ORDER BY - Used when Random order is disabled.
	* `orderby` - [default ID](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); orderby=title
* ORDER BY meta_key - Used when "Random Order" is disabled and sorting by a testimonials meta key is needed.
	* `meta_key` - default none [[testimonials-widget-company|testimonials-widget-email|testimonials-widget-title|testimonials-widget-location|testimonials-widget-url]]; meta_key=testimonials-widget-company
* ORDER BY Order - DESC or ASC.
	* `order` - [default DESC](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters); order=ASC

## Widget

* Widget Title
	* `title` - default "Testimonials"
* Title Link - URL or Post ID to link widget title to.
	* `title_link` - default none; title_link=123, title_link=http://example.com
* Character Limit - Number of characters to limit testimonial views to.
	* `char_limit` - default none; char_limit=200
	* Widget - default 500
* Rotation speed - Seconds between testimonial rotations or 0 for no rotation at all.
	* `refresh_interval` - default 5; refresh_interval=0
* Transition Mode? - Type of transition between slides.
	* `transition_mode` - default fade; transition_mode=horizontal|vertical|fade
* Show Play/Pause? - Display start and stop buttons underneath the testimonial slider.
	* `show_start_stop` - default true; show_start_stop=false
* Enable Video? - Only enable when displaying video content.
	* `enable_video` - default false; enable_video=true
* Keep Whitespace? - Keeps testimonials looking as entered than sans auto-formatting.
	* `keep_whitespace` - default none; keep_whitespace=true
	* The citation has no whitespace adaptations. It's straight text, except for email or URL links. The presentation is handled strictly by CSS.
* Testimonial Bottom Text - Custom text or HTML for bottom of testimonials.
	* `bottom_text` - default none; bottom_text="`&lt;h3&gt;&lt;a href="http://example.com"&gt;All testimonials&lt;/a&gt;&lt;/h3&gt;`"

## Post Type

* Allow Comments? – Only affects the Testimonials post edit page. Your theme controls the front-end view.
* Archive Page URL – URL slug-name for testimonials archive page. After changing, you must click "Save Changes" on Permalink Settings to update them.
* Testimonial Page URL – URL slug-name for testimonial view pages. After changing, you must click "Save Changes" on Permalink Settings to update them.

## Columns

### Columns to display on the Testimonial's edit page

* Hide ID?	 
* Hide Image?	 
* Hide Shortcode?	 
* Hide Author?	 
* Hide Job Title?	 
* Hide Location?	 
* Hide Company?	 
* Hide Email?	 
* Hide URL?	 

## Reset

* Don't Use Default Taxonomies? – If checked, use Testimonials' own category and tag taxonomies instead.
* Export Settings – These are your current settings in a serialized format. Copy the contents to make a backup of your settings.
* Import Settings – Paste new serialized settings here to overwrite your current configuration.
* Remove Plugin Data on Deletion? - Delete all Testimonials data and options from database on plugin deletion.
* Reset to Defaults? – Check this box to reset options to their defaults.

### Version Based Options

* Disable Animation? - Disable animation between testimonial transitions. Useful when stacking.
	* `disable_animation` - default false; disable_animation=true
* Fade Out Speed - Transition duration in milliseconds; higher values indicate slower animations, not faster ones.
	* `fade_out_speed` - default 1250; fade_out_speed=400
* Fade In Speed - Transition duration in milliseconds; higher values indicate slower animations, not faster ones.
	* `fade_in_speed` - default 500; fade_in_speed=800
* Height - Testimonials height, in pixels. Overrides minimum and maximum height.
	* `height` - default none; height=300
* Minimum Height - Set for minimum display height, in pixels.
	* `min_height` - default none; min_height=100
* Maximum Height - Set for maximum display height, in pixels.
	* `max_height` - default none; max_height=250
