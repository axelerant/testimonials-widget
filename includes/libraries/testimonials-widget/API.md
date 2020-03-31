# API - Testimonials Widget

The [Testimonials Widget plugin](http://wordpress.org/plugins/testimonials-widget/) comes with its own set of actions and filters, as described below.

## Actions

* `tw_settings_add_help_tabs`

	Modify the settings page help tabs.

* `tw_scripts`

	Make additional `wp_register_script` and `wp_enqueue_script` calls as needed.

* `tw_styles`

	Make additional `wp_register_style` and `wp_enqueue_style` calls as needed.

* `tw_update`

	Make additional update related calls as needed.

## Filters

### Frontend

* `tw_cite_html`

	Adjust cite contents. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+add+the+date+to+the+cite)

* `tw_content_more`

	More content ellipsis. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+change+the+more+content+ellipsis)

* `tw_get_testimonials_html`

	Customize the contents and layout within `get_testimonials_html`.

* `tw_get_testimonial_html`

	Customize testimonial contents and layout within `get_testimonial_html`. Useful for moving processed parts around than regenerating everything from scratch. [example](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=14024720)

* `tw_gravatar_size`

	Change the Gravatar size. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+change+the+Gravatar+size)

* `tw_image_size`

	Change the image size. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/Follow+How+do+I+change+the+image+size)

* `tw_next_posts_link_text`

	Configure Next page indicator. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+configure+Next+and+Previous+page+indicators)

* `tw_previous_posts_link_text`

	Configure Previous page indicator. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+configure+Next+and+Previous+page+indicators)

* `tw_testimonial_html_single_content`

	Customize single view content before appending filter `tw_testimonial_html_single` results. [example](https://gist.github.com/michael-cannon/5833657)

* `tw_testimonial_html_single`

	Customize testimonials single view output post `get_testimonial_html`. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+customize+my+testimonial+single+output)

* `tw_testimonial_html`

	Customize testimonials list and widget output post `get_testimonial_html`. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+customize+my+testimonial+list+and+widget+output)

* `tw_get_testimonial_html_class`

	Alter dynamically generated CSS per testimonial

* `tw_testimonials_css`

	Alter dynamically generated CSS

* `tw_testimonials_js`

	Alter dynamically generated JavaScript. [example](https://gist.github.com/michael-cannon/5833678)

* `tw_testimonials_js_internal`

	Inject dynamically generated JavaScript to `active/next` functional area..

* `tw_wp_pagenavi`

	Configure WP-PageNavi specifically for Testimonial Widgets. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/Follow+How+do+I+get+page+numbers+for+pagination)

* `tw_get_template_part`

	Allow template choices to be filtered. [Usage](https://github.com/GaryJones/Gamajo-Template-Loader/blob/develop/class-gamajo-template-loader.php#L120)

* `tw_template_paths`

	Allow ordered list of template paths to be amended. [Usage](https://github.com/GaryJones/Gamajo-Template-Loader/blob/develop/class-gamajo-template-loader.php#L201)

* `tw_categories_dropdown_args`

	Used for modifying [wp_dropdown_categories](http://codex.wordpress.org/Function_Reference/wp_dropdown_categories) as part of the testimonials' categories widget.

* `tw_categories_args`

	Used for modifying [wp_list_categories](http://codex.wordpress.org/Function_Reference/wp_list_categories) as part of the testimonials' categories widget.

* `tw_tag_cloud_args`

	Used for modifying [wp_tag_cloud](http://codex.wordpress.org/Function_Reference/wp_tag_cloud) as part of the testimonials' tag cloud widget.

* `tw_recent_testimonials_args`

	Used for modifying [WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query) as part of the recent testimonials widget.

* `tw_archives_dropdown_args`

	Used for modifying [wp_get_archives](http://codex.wordpress.org/Function_Reference/wp_get_archives) as part of the testimonials archive widget.

* `tw_archives_args`

	Used for modifying [wp_get_archives](http://codex.wordpress.org/Function_Reference/wp_get_archives) as part of the testimonials archive widget.

### Backend

* `tw_cache_get`
	
	Caching grabber. [example](https://gist.github.com/michael-cannon/5833685)

* `tw_cache_set`
	
	Caching setter. [example](https://gist.github.com/michael-cannon/5833685)

* `tw_columns`

	Customize testimonial posts column headers. [example](https://gist.github.com/michael-cannon/5833693)

* `tw_content`

	Testimonial content parser helper. [example](https://gist.github.com/michael-cannon/5833700)

* `tw_data`

	Process testimonials data before display processing. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+filter+the+testimonials+data+before+display+processing)

* `tw_defaults_single`

	Create a global or central Testimonials configuration for single view. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+add+testimonial+excerpt+to+single+view). [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+show+the+expert+and+hide+the+image+in+the+testimonial+single+view)

* `tw_defaults`

	Create a global or central Testimonials configuration. [example](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=13566004)

* `tw_meta_box`

	Modify Testimonial Data fields. [example](https://gist.github.com/michael-cannon/5833704). [Configuration examples](https://github.com/michael-cannon/aihrus-framework/blob/master/includes/libraries/class-redrokk-metabox-class.php#L815) 

* `tw_posts_custom_column`

	Customize testimonial posts column contents. [example](https://gist.github.com/michael-cannon/5833716)

* `tw_query_args`

	Alter WP_Query arguments for testimonial selection. [example](https://gist.github.com/michael-cannon/5833740)

* `tw_sections`

	Alter section options. [example](https://gist.github.com/michael-cannon/5833757)

* `tw_settings`

	Alter setting options. [example](https://gist.github.com/michael-cannon/5833757)

* `tw_testimonial_query`

	Alter testimonials WP_Query within `get_testimonials`

* `tw_validate_settings`

	Validate settings helper. [example](https://gist.github.com/michael-cannon/5833768)

* `tw_version`

	Version tracking for settings. [example](https://gist.github.com/michael-cannon/5833774)

* `tw_slider_widget_options`

	Alters displayed widget options. [example](https://gist.github.com/michael-cannon/5833782)

* `tw_categories_widget_options`

	Used for modifying widget options as part of the testimonials' categories widget. [similar example](https://gist.github.com/michael-cannon/5833782)

* `tw_tag_cloud_widget_options`

	Used for modifying widget options as part of the testimonials' tag cloud widget. [similar example](https://gist.github.com/michael-cannon/5833782)

* `tw_recent_testimonials_widget_options`

	Used for modifying widget options as part of the recent testimonials widget. [similar example](https://gist.github.com/michael-cannon/5833782)

* `tw_archives_widget_options`

	Used for modifying widget options as part of the testimonials archives widget. [similar example](https://gist.github.com/michael-cannon/5833782)

* `tw_display_setting`

	Alows for display of custom input types.

* `tw_settings_defaults`

	Override settings defaults with your own.

* `tw_register_post_type_args`

	Overrides `register_post_type` arguments. [example](https://gist.github.com/michael-cannon/8fc217199ae1e8d5eecb)

* `tw_register_category_args`

	Overrides `register_taxonomy` arguments.

* `tw_register_tags_args`

	Overrides `register_taxonomy` arguments.

* `tw_examples_html`

	Enhance or override examples HTML.

* `tw_used_with_codes`

	Enhance or override primary widgets shortcode and theme function accessibility.

* `tw_options_widgets`

	Enhance or override widgets included for options documentation.

* `tw_used_with_codes_widgets`

	Enhance or override additionally widgets shortcode and theme function accessibility.

* `tw_options_html`

	Enhance or override options HTML.

## Need More?

Further examples and more can be found by reading and searching the [Testimonials Knowledge Base](https://axelerant.atlassian.net/wiki/display/WPFAQ/) and [source code](https://github.com/michael-cannon/testimonials-widget).
