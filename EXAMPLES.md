# Shortcode Examples - Testimonials Widget

## [testimonials]

* `[testimonials category="category-name"]` - Testimonial list by category
* `[testimonials category=product hide_not_found=true]` - Testimonial list by category and hide "No testimonials found" message
* `[testimonials category=product tags=widget limit=5]` - Testimonial list by tag, showing 5 at most
* `[testimonials char_limit=0 limit=-1]` - Show all testimonials on one page
* `[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]` - Show 3 full-length testimonials, with opening and closing quote marks removed
* `[testimonials hide_source=true hide_url=true]` - Show testimonial list with source and urls hidden
* `[testimonials ids="1,11,111" paging=false]` - Show only these 3 testimonials
* `[testimonials meta_key=testimonials-widget-company order=asc limit=15]` - Show 15 testimonials, in company order
* `[testimonials order=ASC orderby=title]` - List testimonials by post title
* `[testimonials tags="test,fun" random=true exclude="2,22,333"]` - Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids

## [testimonials_slider]

* `[testimonials_slider category=product order=asc]` - Show rotating testimonials, of the product category, lowest post ids first
* `[testimonials_slider tags=sometag random=true]` - Show rotating, random testimonials having tag "sometag"
