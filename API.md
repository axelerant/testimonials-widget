# API Testimonials Widget

The [Testimonials Widget plugin](http://wordpress.org/plugins/testimonials-widget/) comes with its own set of actions and filters, as described below.

## Actions

* `testimonials_widget_settings_add_help_tabs`

	Modify the settings page help tabs.

* `testimonials_widget_scripts`

	Make additional `wp_register_script` and `wp_enqueue_script` calls as needed.

* `testimonials_widget_styles`

	Make additional `wp_register_style` and `wp_enqueue_style` calls as needed.

* `testimonials_widget_update`

	Make additional update related calls as needed.

## Filters

### Frontend

* `testimonials_widget_cite_html`

	Adjust cite contents. [example](https://aihrus.zendesk.com/entries/24012926-How-do-I-add-the-date-to-the-cite-)

* `testimonials_widget_content_more`

	More content ellipsis. [example](https://aihrus.zendesk.com/entries/23691577-How-do-I-change-the-more-content-ellipsis-)

* `testimonials_widget_get_testimonial_html`

	Customize testimonial contents and layout within `get_testimonial_html`. Useful for moving processed parts around than regenerating everything from scratch.. [example](https://aihrus.zendesk.com/entries/23693433-How-do-I-use-filter-testimonials-widget-get-testimonial-html-)

* `testimonials_widget_gravatar_size`

	Change the Gravatar size. [example](https://aihrus.zendesk.com/entries/23679271-How-do-I-change-the-Gravatar-size-)

* `testimonials_widget_image_size`

	Change the image size. [example](https://aihrus.zendesk.com/entries/23677122-How-do-I-change-the-image-size-)

* `testimonials_widget_next_posts_link_text`

	Configure Next page indicator. [example](https://aihrus.zendesk.com/entries/23691587-How-do-I-configure-Next-and-Previous-page-indicators-)

* `testimonials_widget_previous_posts_link_text`

	Configure Previous page indicator. [example](https://aihrus.zendesk.com/entries/23691587-How-do-I-configure-Next-and-Previous-page-indicators-)

* `testimonials_widget_testimonial_html_single_content`

	Customize single view content before appending filter `testimonials_widget_testimonial_html_single` results. [example](https://gist.github.com/michael-cannon/5833657)

* `testimonials_widget_testimonial_html_single`

	Customize testimonials single view output post `get_testimonial_html`. [example](https://aihrus.zendesk.com/entries/23679391-How-do-I-customize-my-testimonial-single-output-)

* `testimonials_widget_testimonial_html`

	Customize testimonials list and widget output post `get_testimonial_html`. [example](https://aihrus.zendesk.com/entries/23693413-How-do-I-customize-my-testimonial-list-and-widget-output-)

* `testimonials_widget_get_testimonial_html_class`

	Alter dynamically generated CSS per testimonial

* `testimonials_widget_testimonials_css`

	Alter dynamically generated CSS

* `testimonials_widget_testimonials_js`

	Alter dynamically generated JavaScript. [example](https://gist.github.com/michael-cannon/5833678)

* `testimonials_widget_testimonials_js_internal`

	Inject dynamically generated JavaScript to `active/next` functional area..

* `testimonials_widget_wp_pagenavi`

	Configure WP-PageNavi specifically for Testimonial Widgets. [example](https://aihrus.zendesk.com/entries/23679361-How-do-I-get-page-numbers-for-pagination-)

* `testimonials_widget_review_name_length`

	Maximium length of review schema's name. Default is 156 characters.

* `testimonials_widget_schema`

	Customize schema contents.

* `testimonials_widget_schema_review`

	Customize schema review meta contents.

* `testimonials_widget_schema_agg_rating`

	Customize schema aggregate rating for item.

* `testimonials_widget_schema_author`

	Customize schema author contents.

* `testimonials_widget_schema_item`

	Customize schema reviewed item contents.


### Backend

* `testimonials_widget_cache_get`
	
	Caching grabber. [example](https://gist.github.com/michael-cannon/5833685)

* `testimonials_widget_cache_set`
	
	Caching setter. [example](https://gist.github.com/michael-cannon/5833685)

* `testimonials_widget_columns`

	Customize testimonial posts column headers. [example](https://gist.github.com/michael-cannon/5833693)

* `testimonials_widget_content`

	Testimonial content parser helper. [example](https://gist.github.com/michael-cannon/5833700)

* `testimonials_widget_data`

	Process testimonials data before display processing. [example](https://aihrus.zendesk.com/entries/23692056-How-do-I-filter-the-testimonials-data-before-display-processing-)

* `testimonials_widget_defaults_single`

	Create a global or central Testimonials Widget configuration for single view. [example](https://aihrus.zendesk.com/entries/23679071-How-do-I-add-testimonial-excerpt-to-single-view-). [example](https://aihrus.zendesk.com/entries/23679111-How-do-I-show-the-expert-and-hide-the-image-in-the-testimonial-single-view-)

* `testimonials_widget_defaults`

	Create a global or central Testimonials Widget configuration. [example](https://aihrus.zendesk.com/entries/23691607-How-do-I-use-filter-testimonials-widget-defaults-)

* `testimonials_widget_meta_box`

	Modify Testimonial Data fields. [example](https://gist.github.com/michael-cannon/5833704)

* `testimonials_widget_posts_custom_column`

	Customize testimonial posts column contents. [example](https://gist.github.com/michael-cannon/5833716)

* `testimonials_widget_query_args`

	Alter WP_Query arguments for testimonial selection. [example](https://gist.github.com/michael-cannon/5833740)

* `testimonials_widget_sections`

	Alter section options. [example](https://gist.github.com/michael-cannon/5833757)

* `testimonials_widget_settings`

	Alter setting options. [example](https://gist.github.com/michael-cannon/5833757)

* `testimonials_widget_validate_settings`

	Validate settings helper. [example](https://gist.github.com/michael-cannon/5833768)

* `testimonials_widget_version`

	Version tracking for settings. [example](https://gist.github.com/michael-cannon/5833774)

* `testimonials_widget_widget_options`

	Alters displayed widget options. [example](https://gist.github.com/michael-cannon/5833782)

* `testimonials_widget_display_setting`

	Alows for display of custom input types.

## Need More?

Further examples and more can be found by reading and searching the [Testimonials Widget Knowledge Base](https://aihrus.zendesk.com/categories/20104507-Testimonials-Widget) and [source code](https://github.com/michael-cannon/testimonials-widget).
