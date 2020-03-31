# Upgrade Notices - Testimonials Widget Premium

## 2.3.0

This is an overhaul **without** backwards compliance.

If you use the template system, class, methods, global variables of the plugin to modify Testimonials Widget and Testimonials Widget Premium output, this upgrade will not be compatible with those modifications until corrections are made.

The changes and helpers below are intended to help you update your customizations quickly. If further help is required, please request it via the support forum.

### Changes

* Class names below and all the references to these inside the plugin have been prepended with `Axl_`.
    * `Testimonials_Widget` > `Axl_Testimonials_Widget`
    * `Testimonials_Widget_Slider_Widget` > `Axl_Testimonials_Widget_Slider_Widget`
    * `Testimonials_Widget_Archives_Widget` > `Axl_Testimonials_Widget_Archives_Widget`
    * `Testimonials_Widget_Tag_Cloud_Widget` > `Axl_Testimonials_Widget_Tag_Cloud_Widget`
    * `Testimonials_Widget_Categories_Widget` > `Axl_Testimonials_Widget_Categories_Widget`
    * `Testimonials_Widget_Recent_Testimonials_Widget` > `Axl_Testimonials_Widget_Recent_Testimonials_Widget`
    * `Testimonials_Widget_Settings` > `Axl_Testimonials_Widget_Settings`
    * `Testimonials_Widget_Template_Loader` > `Axl_Testimonials_Widget_Template_Loader`
    * `Testimonials_Widget_Premium` > `Axl_Testimonials_Widget_Premium`
    * `Testimonials_Widget_Premium_Form` > `Axl_Testimonials_Widget_Premium_Form`
    * `Testimonials_Widget_Premium_Cache` > `Axl_Testimonials_Widget_Premium_Cache`
    * `Testimonials_Widget_Premium_Sticky` > `Axl_Testimonials_Widget_Premium_Sticky`
    * `Testimonials_Widget_Premium_Session` > `Axl_Testimonials_Widget_Premium_Session`
    * `Testimonials_Widget_Premium_Licensing` > `Axl_Testimonials_Widget_Premium_Licensing`
    * `Testimonials_Widget_Premium_Form_Widget` > `Axl_Testimonials_Widget_Premium_Form_Widget`
    * `Testimonials_Widget_Premium_Template_Loader` > `Axl_Testimonials_Widget_Premium_Template_Loader`
    * `Testimonials_Widget_Premium_Antispam_Akismet` > `Axl_Testimonials_Widget_Premium_Antispam_Akismet`
    * `Testimonials_Widget_Premium_Antispam_Simple` > `Axl_Testimonials_Widget_Premium_Antispam_Simple`
    * `Testimonials_Widget_Premium_Antispam_Bee_Base` > `Axl_Testimonials_Widget_Premium_Antispam_Bee_Base`
    * `Testimonials_Widget_Premium_Antispam_Bee` > `Axl_Testimonials_Widget_Premium_Antispam_Bee`
    * `Testimonials_Widget_Premium_Antispam_Base` > `Axl_Testimonials_Widget_Premium_Antispam_Base`
    * `Testimonials_Widget_Premium_Antispam` > `Axl_Testimonials_Widget_Premium_Antispam` 
* Global variables below have been prepended with `Axl_`.
    * `$Testimonials_Widget_Settings` > `$Axl_Testimonials_Widget_Settings`
    * `$Testimonials_Widget` > `$Axl_Testimonials_Widget`

###  Helpers

*Replace `Testimonials_Widget` with `Axl_Testimonials_Widget` through out all your custom plugins and themes which customize Testimonials Widget's output. Please take backup measures beforehand.*

* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#\b(Testimonials_Widget)#Axl_\1#g" {} \;`

## 2.0.0

This is a major overhaul *without* backwards compliance of over 80 changes. Please read the [Testimonials Widget 3.0 and Testimonials Widget Premium 2.0 Upgrade Notice](https://axelerant.atlassian.net/wiki/display/WPFAQ/Testimonials+Widget+3.0+and+Testimonials+Widget+Premium+2.0+Upgrade+Notice) for more help. 

If you use custom CSS, actions, or filters to modify Testimonials Widget and Testimonials Widget Premium actions or output, this upgrade will not be compatible with those modifications until corrections are made.

The changes and helpers below are intended to help you update your customizations quickly. If further help is required, please request it via commenting upon the [Testimonials Widget 3.0 and Testimonials Widget Premium 2.0 Upgrade Notice](https://axelerant.atlassian.net/wiki/display/WPFAQ/Testimonials+Widget+3.0+and+Testimonials+Widget+Premium+2.0+Upgrade+Notice).

### Changes

* As [Internet Explorer 7.0 usage](http://www.w3schools.com/browsers/browsers_explorer.asp) is under 0.5% of Internet traffic, support for it is discontinued. Option `include_ie7_css` is removed.
* Deprecated API hooks and functions of Testimonials Widget 2.19.0 and Testimonials Widget Premium 1.20.0 are removed.
* Form text "Full Testimonial" now "Complete Testimonial"
* Removed filter `twp_next_text`
* Replace `testimonials_widget_premium_` of actions, filters, and functions with `twp_`
* Replace `testimonials_widget_` of actions, filters, and functions with `tw_`
* Replaced `tba_` of actions, filters, and functions with `tw_`
* Replaced `tw_shortcodes` with `tw_examples`
* Replaced `tw_widget_options` with `tw_slider_widget_options`
* Shortcodes and theme functions renamed. See [DEPRECATED.md](https://store.axelerant.com/best-wordpress-testimonials-plugin/deprecation-notices/) for details.
* Testimonials Form option `form_table` is removed.
* Testimonials Widget pre-2.0.0 automatic migration has been removed. Install and activate Testimonials Widget 2.19.0 before installing the latest Testimonials Widget.
* Testimonials Widget pre-2.15.0 JavaScript slider code has been replaced with [bxSlider](http://bxslider.com/) and is now removed. Options `use_bxslider`, `disable_animation`, `fade_out_speed`, `fade_in_speed`, `height`, `min_height`, and `max_height` are removed.

###  Helpers

*The ordering of the helper code below is important to prevent corruption.*

* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#testimonials_widget_premium_#twp_#g" {} \;`
* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#testimonials_widget_#tw_#g" {} \;`
* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#tba_#tw_#g" {} \;`
* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#tw_widget_options#tw_slider_widget_options#g" {} \;`
* `find . -type f \( -name "*.php" -o -name "*.txt" -o -name "*.md" \) -exec perl -pi -e "s#tw_shortcodes#tw_examples#g" {} \;`

## 1.20.6

* Include Form CSS is now set by default

## 1.20.1

* Please resave your Testimonials > Settings and Widget options for defaults to be corrected

## 1.19.3

* Default value for `slide_margin` set to 10.
* Default value for `slide_width` removed.
* This is the last version supporting pre-bxSlider options

## 1.19.0

* Plugin "[Testimonials Widget](http://wordpress.org/plugins/testimonials-widget/)" is **NOT** required to be installed and activated prior to activating "Testimonials Widget Premium".

## 1.17.0

* Licensing is **required** for updating plugin
* Requires PHP 5.3+ [notice](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=12845151)

## 1.16.0

* Some of the new features only work with Testimonials 2.15.0 or higher with bxSlider enabled.

## 1.15.0

* **50 modifications** See [Changelog](https://store.axelerant.com/best-wordpress-testimonials-plugin/changelog/)
* The read more link tag surrounding the `span.image` tag, is now inside of `span.image` tag.

## 1.14.4

* **Clear the Cache** to reset it
* CSS ID `testimonials-widget-premium-form` changed to class
* Form layout CSS moved to own stylesheet for optional include via "Include Form CSS?"
* Option `hide_featured_image_url` renamed to `hide_featured_image`
* Remove `style="display: table;"` from testimonials submission form

## 1.14.0

* Clear Cache? moved from Setting to WP Admin > Testmonials > Clear Cache as a menu link

## 1.12.0

* CSS and JavaScript renaming
	* `testimonialswidget_excerpt` renamed to `testimonials-widget-premium-excerpt`
	* `testimonialswidget_testimonial` renamed to `testimonials-widget-testimonial`
	* `testimonialswidget_testimonials` renamed to `testimonials-widget-testimonials`
	* `testimonials_form` renamed to `testimonials-widget-premium-form`

## 1.11.1

* Requires at least Testimonials 2.11.2

## 1.11.0

* CSS class names are simplified. For the most part, other than `testimonialswidget_testimonial` remove `testimonialswidget_` from the CSS class name in your CSS customizations.
	* Ex: `.testimonialswidget_join` becomes `.join`
	* Ex: `.testimonialswidget_author` becomes `.author`
* Testimonials are now formatted using `blockquote` than `q` for HTML5 compliance. If you need `q` tag formatting, enable it at WP Admin > Testimonials > Settings, Compatibility & Reset tab
	* `cite` is now `div.credit`

## 1.10.2

* CSS class names are simplified. For the most part, other than `testimonialswidget_testimonial` remove `testimonialswidget_` from the CSS class name in your CSS customizations.
	* Ex: `.testimonialswidget_join` becomes `.join`
	* Ex: `.testimonialswidgetpremium_testimonials_links` to `.testimonialswidget_testimonials .links`
* Replace filter `tw_disable_more_link` with setting `hide_read_more`
* Requires at least Testimonials 2.10.3

## 1.10.0

* Requires at least Testimonials 2.10.0

## 1.9.0

* Using posts, pages, and other custom post types for rotation and testimonials is possible now

## 1.8.0

* Requires at least Testimonials 2.8.0

## 1.7.9

* Requires at least Testimonials 2.7.15

## 1.7.2

* Requires at least Testimonials 2.7.3
