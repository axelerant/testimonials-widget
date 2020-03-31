# Shortcode Examples - Testimonials Widget Premium

## [[testimonials]] - Testimonials listing with paging

* List of testimonials only 200 characters long with only author shown in credit box - `[[testimonialswidget_list char_limit=200 hide_title=true hide_email=true hide_url=true hide_location=true hide_company=true]]`
* List testimonials by post title - `[[testimonials order=ASC orderby=title]]`
* Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids - `[[testimonials tags="test,fun" random=true exclude="2,22,333"]]`
* Show 15 testimonials, in company order - `[[testimonials meta_key=testimonials-widget-company order=asc limit=15]]`
* Show 3 full-length testimonials, with opening and closing quote marks removed - `[[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]]`
* Show 5 full-length testimonials, with paging - `[[testimonials limit=5 paging=true char_limit=0]]`
* Show all testimonials on one page - `[[testimonials char_limit=0 limit=-1]]`
* Show one random testimonial - `[[testimonials limit=1 no_cache=true random=true]]`
* Show only these 3 testimonials - `[[testimonials ids="1,11,111" paging=false]]`
* Show testimonial list with author and urls hidden - `[[testimonials hide_source=true hide_url=true]]`
* Show the single testimonial of ID 123 on a page - `[[testimonials ids="123" paging=false]]`
* Testimonial list by category - `[[testimonials category="category-name"]]`
* Testimonial list by category and hide "No testimonials found" message - `[[testimonials category=product hide_not_found=true]]`
* Testimonial list by category ID 123 - `[[testimonials category=123]]`
* Testimonial list by tag, showing 5 at most - `[[testimonials category=product tags=widget limit=5]]`
* Testimonial list using Readmore JS with 100 pixel collasped height - `[[testimonials use_readmore_js=true readmore_max_height=100]]`

## [[testimonials_archives]] - A monthly archive of your site's testimonials

* Testimonial's archive as dropdown with monthly count - `[[testimonials_archives dropdown=true count=true]]`

## [[testimonials_categories]] - A list or dropdown of testimonials' categories

* Testimonial's categories as hierarchical with monthly count - `[[testimonials_categories count=true hierarchical=true]]`

## [[testimonials_recent]] - Your site's most recent testimonials

* The 3 most recent testimonials with dates - `[[testimonials_recent number=3 show_date=true]]`

## [[testimonials_count]] - Count of testimonials

* Display count of posts - `[[testimonials_count post_type=post]]`
* Display count of testimonials of category wordpress - `[[testimonials_count category=wordpress]]`
* Display count of testimonials of category wordpress and tagged with support - `[[testimonials_count category=wordpress tags=support]]`
* Display count of testimonials tagged with support - `[[testimonials_count tags=support]]`
* Display the number of "Product A" category testimonials - `[[testimonials_count category=product-a]]`

## [[testimonials_form]] - Testimonials entry form

* Automatically assign "30 Day Lift" and tag "osa" to the user submitted testimonial - `[[testimonials_form meta_item="30 Day Lift" tags_input="osa"]]`
* Automatically assign "my product" and hide that field for the user submitted testimonial - `[[testimonials_form meta_item="my product" hide_meta_item=true]]`
* Display a testimonial input form on the front-end sans email, but enables tags entry - `[[testimonials_form hide_meta_email=true hide_tags_input=false]]`
* Display a testimonial input form on the front-end that submits to the "Services" category with ID 123 - `[[testimonials_form post_category=123]]` or `[[testimonials_form post_category="Services"]]`
* Hide most fields and assign testimonials to category 41 - `[[testimonials_form hide_post_excerpt=true hide_meta_title=true hide_meta_location=false hide_meta_email=false hide_meta_company=true hide_meta_url=true hide_featured_image_url=true post_category=41]]`
* Simple form submission - `[[testimonials_form hide_post_excerpt=true hide_meta_title=true hide_meta_location=true hide_meta_email=true hide_meta_company=true hide_featured_image=true hide_meta_read_more_link=true hide_meta_item_url=true]]`
* Very simple testimonial submission form - `[[testimonials_form hide_post_excerpt=true hide_meta_title=true hide_meta_location=true hide_meta_email=true hide_meta_company=true hide_meta_url=true hide_featured_image=true]]`

## [[testimonials_links]] - List of testimonial author and title linking to full entry

* Display a list of all testimonials without content or company details - `[[testimonials_links hide_company=true]]`

## [[testimonials_slider]] - Displays rotating testimonials or statically

* Automatically rotate testimonials page to page, even if next testimonial is clicked - `[[testimonials_slider category=quote rotate_per_page=true show_controls=true]]`
* Display only short testimonials - `[[testimonials_slider maximum_length=100]]`
* Display only testimonials with excerpts and featured images. Great for lead-ins - `[[testimonials_slider require_image=true require_excerpt=true]]`
* Display posts instead of testimonials - `[[testimonials_slider post_type=post]]`
* Display rotating testimonials with link to next testimonial - `[[testimonials_slider show_controls=true]]`
* Ignore short testimonials for display - `[[testimonials_slider minimum_length=250]]`
* Prevent showing previously selected testimonials on a page - `[[testimonials_slider unique=true]]`
* Show "liposuction" testimonials in oldest first sorting - `[[testimonials_slider category=liposculpture order=asc]]` 
* Show a horizontal carousel with 3 rated testimonials - `[[testimonials_slider carousel_count=3 transition_mode=horizontal slide_width=250 slide_margin=25 require_ratings=1]]`
* Show a slider of testimonials with excerpts, don't show ratings or the play and pause buttons - `[[testimonials_slider show_controls=false show_ratings=false require_excerpt=true]]`
* Show random "dreamweaver" testimonials. Enable play and pause buttons - `[[testimonials_slider category="dreamweaver" random="true" show_controls="true"]]`
* Show rotating testimonials, of the product category, lowest post ids first - `[[testimonials_slider category=product order=asc]]`
* Show rotating, random testimonials having tag "sometag" - `[[testimonials_slider tags=sometag random=true]]`
* Show testimonials having at least 4 stars and a featured image, but don't show the excerpt - `[[testimonials_slider limit=3 show_controls=true require_ratings=4 require_image=true hide_excerpt=true]]`
* Show vertical carousel with 2 testimonials - `[[testimonials_slider carousel_count=2 transition_mode=vertical slide_margin=50 rotate_per_page=false]]`

## [[testimonials_tag_cloud]] - A cloud of your most used testimonials' tags

* Show testimonials post tag cloud - `[[testimonials_tag_cloud]]`
