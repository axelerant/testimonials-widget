# Shortcode Examples - Testimonials Widget

## [testimonials] - Listings with paging 

* List of testimonials only 200 characters long with only source shown in credit box - `[testimonialswidget_list char_limit=200 hide_title=true hide_email=true hide_url=true hide_location=true hide_company=true]`
* List testimonials by post title - `[testimonials order=ASC orderby=title]`
* Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids - `[testimonials tags="test,fun" random=true exclude="2,22,333"]`
* Show 15 testimonials, in company order - `[testimonials meta_key=testimonials-widget-company order=asc limit=15]`
* Show 3 full-length testimonials, with opening and closing quote marks removed - `[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]`
* Show 5 full-length testimonials, with paging - `[testimonials limit=5 paging=true char_limit=0]`
* Show all testimonials on one page - `[testimonials char_limit=0 limit=-1]`
* Show one random testimonial - `[testimonials limit=1 no_cache=true random=true]`
* Show only these 3 testimonials - `[testimonials ids="1,11,111" paging=false]`
* Show testimonial list with source and urls hidden - `[testimonials hide_source=true hide_url=true]`
* Show the single testimonial of ID 123 on a page - `[testimonials ids="123" paging=false]`
* Testimonial list by category - `[testimonials category="category-name"]`
* Testimonial list by category and hide "No testimonials found" message - `[testimonials category=product hide_not_found=true]`
* Testimonial list by category ID 123 - `[testimonials category=123]`
* Testimonial list by tag, showing 5 at most - `[testimonials category=product tags=widget limit=5]`

## [testimonials_archives] - A monthly archive of your site's testimonials

* Testimonial's archive as dropdown with monthly count - `[testimonials_archives dropdown=true count=true]`

## [testimonials_categories] - A list or dropdown of testimonials' categories

* Testimonial's categories as hierarchical with monthly count - `[testimonials_categories count=true hierarchical=true]`

## [testimonials_recent] - Your site's most recent testimonials

* The 3 most recent testimonials with dates - `[testimonials_recent number=3 show_date=true]`

## [testimonials_slider] - Displays rotating testimonials or statically

* Change rotation speed to 3 seconds - `[testimonialswidget_widget refresh_interval=3]`
* Show "liposuction" testimonials in oldest first sorting - `[testimonials_slider category=liposculpture order=asc]` 
* Show random "dreamweaver" testimonials - `[testimonials_slider category="dreamweaver" random="true"]`
* Show rotating testimonials, of the product category, lowest post ids first - `[testimonials_slider category=product order=asc]`
* Show rotating, random testimonials having tag "sometag" - `[testimonials_slider tags=sometag random=true]`

## [testimonials_tag_cloud] - A cloud of your most used testimonials' tags

* Show testimonials post tag cloud - `[testimonials_tag_cloud]`

## [testimonials_examples] - Displays examples of commonly used testimonials' shortcodes with attributes

* Show testimonials' shortcodes with attributes - `[testimonials_examples]`

## [testimonials_options] - Displays summary of testimonials' settings for use with shortcodes and theme functions

* Show summary of testimonials shortcode and theme function attributes - `[testimonials_options]`
