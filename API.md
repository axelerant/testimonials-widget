Testimonials Widget API Documentation
=====================================

The [Testimonials Widget plugin](http://wordpress.org/plugins/testimonials-widget/) comes with its own set of actions and filters, described below, first [actions](#actions), second [filters](#filters), and lastly [more](#more).

Actions
-------

None at this time.

Filters
-------

**Frontend**

* `testimonials_widget_cite_html`

	Adjust cite contents [example](https://aihrus.zendesk.com/entries/24012926-How-do-I-add-the-date-to-the-cite-)

* `testimonials_widget_content_more`

	More content ellipsis [example](https://aihrus.zendesk.com/entries/23691577-How-do-I-change-the-more-content-ellipsis-)

* `testimonials_widget_get_testimonial_html`

	Customize testimonial contents and layout within `get_testimonial_html`. Useful for moving processed parts around than regenerating everything from scratch. [example](https://aihrus.zendesk.com/entries/23693433-How-do-I-use-filter-testimonials-widget-get-testimonial-html-)

* `testimonials_widget_gravatar_size`

	Change the Gravatar size [example](https://aihrus.zendesk.com/entries/23679271-How-do-I-change-the-Gravatar-size-)

* `testimonials_widget_image_size`

	Change the image size [example](https://aihrus.zendesk.com/entries/23677122-How-do-I-change-the-image-size-)

* `testimonials_widget_next_posts_link_text`

	Configure Next page indicator [example](https://aihrus.zendesk.com/entries/23691587-How-do-I-configure-Next-and-Previous-page-indicators-)

* `testimonials_widget_previous_posts_link_text`

	Configure Previous page indicator [example](https://aihrus.zendesk.com/entries/23691587-How-do-I-configure-Next-and-Previous-page-indicators-)

* `testimonials_widget_testimonial_html_single_content`

	Customize single view content before appending filter `testimonials_widget_testimonial_html_single` results [example]()

* `testimonials_widget_testimonial_html_single`

	Customize testimonials single view output post `get_testimonial_html` [example](https://aihrus.zendesk.com/entries/23679391-How-do-I-customize-my-testimonial-single-output-)

* `testimonials_widget_testimonial_html`

	Customize testimonials list and widget output post `get_testimonial_html` [example](https://aihrus.zendesk.com/entries/23693413-How-do-I-customize-my-testimonial-list-and-widget-output-)

* `testimonials_widget_testimonials_css`

	Alter dynamically generated CSS [example]()

* `testimonials_widget_testimonials_js`

	Alter dynamically generated JavaScript [example]()

* `testimonials_widget_wp_pagenavi`

	Configure WP-PageNavi specifically for Testimonial Widgets [example](https://aihrus.zendesk.com/entries/23679361-How-do-I-get-page-numbers-for-pagination-)


**Backend**

* `testimonials_widget_cache_get`
	
	Caching grabber [example]()

* `testimonials_widget_cache_set`
	
	Caching setter [example]()

* `testimonials_widget_columns`

	Customize testimonial posts column headers [example]()

* `testimonials_widget_content`

	Testimonial content parser helper [example]()

* `testimonials_widget_data`

	Process testimonials data before display processing [example](https://aihrus.zendesk.com/entries/23692056-How-do-I-filter-the-testimonials-data-before-display-processing-)

* `testimonials_widget_defaults_single`

	Create a global or central Testimonials Widget configuration for single view [example](https://aihrus.zendesk.com/entries/23679071-How-do-I-add-testimonial-excerpt-to-single-view-) [example](https://aihrus.zendesk.com/entries/23679111-How-do-I-show-the-expert-and-hide-the-image-in-the-testimonial-single-view-)

* `testimonials_widget_defaults`

	Create a global or central Testimonials Widget configuration [example](https://aihrus.zendesk.com/entries/23691607-How-do-I-use-filter-testimonials-widget-defaults-)

* `testimonials_widget_meta_box`

	Modify Testimonial Data fields [example]()

* `testimonials_widget_posts_custom_column`

	Customize testimonial posts column contents [example]()

* `testimonials_widget_query_args`

	Alter WP_Query arguments for testimonial selection [example]()

* `testimonials_widget_sections`

	Alter section options [example]()

* `testimonials_widget_settings`

	Alter setting options [example]()

* `testimonials_widget_validate_settings`

	Validate settings helper [example]()

* `testimonials_widget_version`

	Version tracking for settings [example]()

* `testimonials_widget_widget_options`

	Alters displayed widget options [example]()

More?
-----

Further examples and more can be found by reading and searching the [Testimonials Widget Knowledge Base](https://aihrus.zendesk.com/categories/20104507-Testimonials-Widget).
