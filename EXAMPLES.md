# Shortcode Examples - Testimonials Widget

## [testimonials] - Listings with paging 

* `[testimonials category="category-name"]` - Testimonial list by category
* `[testimonials category=123]` - Testimonial list by category ID 123
* `[testimonials category=product hide_not_found=true]` - Testimonial list by category and hide "No testimonials found" message
* `[testimonials category=product tags=widget limit=5]` - Testimonial list by tag, showing 5 at most
* `[testimonials char_limit=0 limit=-1]` - Show all testimonials on one page
* `[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]` - Show 3 full-length testimonials, with opening and closing quote marks removed
* `[testimonials hide_source=true hide_url=true]` - Show testimonial list with source and urls hidden
* `[testimonials ids="1,11,111" paging=false]` - Show only these 3 testimonials
* `[testimonials ids="123" paging=false]` - Show the single testimonial of ID 123 on a page
* `[testimonials meta_key=testimonials-widget-company order=asc limit=15]` - Show 15 testimonials, in company order
* `[testimonials order=ASC orderby=title]` - List testimonials by post title
* `[testimonials tags="test,fun" random=true exclude="2,22,333"]` - Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids

## [testimonials_archives] - A monthly archive of your site's testimonials

* `[testimonials_archives dropdown=true count=true]` - Testimonial's archive as dropdown with monthly count

## [testimonials_categories] - A list or dropdown of testimonials' categories

* `[testimonials_categories count=true hierarchical=true]` - Testimonial's categories as hierarchical with monthly count

## [testimonials_recent] - Your site's most recent testimonials

* `[testimonials_recent number=3 show_date=true]` - The 3 most recent testimonials with dates

## [testimonials_slider] - Displays rotating testimonials or statically

* `[testimonials_slider category=product order=asc]` - Show rotating testimonials, of the product category, lowest post ids first
* `[testimonials_slider tags=sometag random=true]` - Show rotating, random testimonials having tag "sometag"

## [testimonials_tag_cloud] - A cloud of your most used testimonials' tags

* `[testimonials_tag_cloud]` - Show testimonials post tag cloud
