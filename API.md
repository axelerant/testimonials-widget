# API - Testimonials Widget Premium

## Actions

### Backend

* `twp_form_save`

	Save or modify form fields upon testimonials form submission. [example](https://gist.github.com/michael-cannon/7ec3d55893d8aa9eb689)

## Filters

### Frontend

* `twp_scripts_display`

	Customize `$scripts_display` contents. Useful for changing the star image path to something you'd like to use.

* `twp_html_link`

	Link filtering for the `testimonialswidgetpremium_link_list` shortcode.

* `twp_more_text`

	'Read more' text replacement. [Example](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=14024721)

* `twp_link_title_text`

	'Complete testimonial by ' text replacement.

* `twp_more_ellipsis`

	Ellipsis, '…', text replacement. [Example](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=14024721)

* `tw_cite_html`

	Adjust cite contents. [Example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+add+the+date+to+the+cite)

* `twp_form_heading`

	Form header text replacement. [Example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+change+the+form+heading)

### Backend

* `tw_disable_cache`

	Accepts boolean to use caching or not.

* `tw_cache_get`
	
	Caching grabber. [Example](https://gist.github.com/michael-cannon/5833685)

* `tw_cache_set`
	
	Caching setter.

* `twp_form_options`

	Filter form options. [Example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+change+form+labels+and+options) [Add a field](https://gist.github.com/michael-cannon/7ec3d55893d8aa9eb689)

* `twp_send_mail_notification_to`

	Alter recipient for `send_mail_notification`.
	
	`$to = apply_filters( 'twp_send_mail_notification_to', $to, $post_id );`

* `twp_send_mail_notification_subject`

	Alter subject for `send_mail_notification`.
	
	`$subject = apply_filters( 'twp_send_mail_notification_subject', $subject, $post_id );`

* `twp_send_mail_notification_body`

	Alter body content for `send_mail_notification`.
	
	`$body = apply_filters( 'twp_send_mail_notification_body', $body, $post_id );`

* `twp_send_mail_notification_headers`

	Alter headers for `send_mail_notification`.
	
	`$headers = apply_filters( 'twp_send_mail_notification_headers', $headers, $post_id );`

* `twp_send_mail_notification_attachment`

	Alter attachment for `send_mail_notification`.
	
	`$attachment = apply_filters( 'twp_send_mail_notification_attachment', self::$mail_attachment, $post_id );`

## Need More?

Further Examples and more can be found by reading and searching the [Testimonials Knowledge Base](https://axelerant.atlassian.net/wiki/label/WPFAQ/twp) and source code.
