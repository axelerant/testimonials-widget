# CHANGELOG - Testimonials Widget Premium

## master

## 4.0.4RC1
* Removed Youtube view from readme.txt

## 4.0.3
* Confirm WordPress 5.9.3 compatibility

## 4.0.2
* Testimonials Widget Premium plugin is merged with Testimoinals Widget
* Fix XSS vulnerability issue
* Confirm WordPress 5.4.2 compatibility

## 2.5.1
* Fix Warning (Cannot modify header information) issue on class-wp-session.php file

## 2.5.0
* Confirm WordPress 5.3 compatibility
* Fix Category filter issue
* Fix Tag filter issue
* Fix slider shortcode issue
* Fix PHP 7.3 compatibility issue

## 2.4.1
* Confirm WordPress 5.0 compatibility
* Fix orderby issue
* Fix `User defined settings not being saved`

## 2.4.0
* Confirm WordPress 4.8 compatibility
* Remove review schema

## 2.3.5
* Change Axelerant's plugins FAQ links
* Confirm WordPress 4.7.2 compatibility

## 2.3.4
* Change verbiage for schema notice
* Confirm WordPress 4.6 compatibility

## 2.3.3
* Fix `Fatal error: Can't use function return value in write context`

## 2.3.2
* Add notice for existing users about review schema
* Confirm WordPress 4.5.3 compatibility
* Disable use of review schema by default

## 2.3.1
* Confirm WordPress 4.5 compatibility
* RESOLVE michael-cannon/testimonials-widget#196 `Warning: Division by zero`

## 2.3.0
* Confirm WordPress 4.4 compatibility
* Fix aggregate rating count issue
* Ignore minified JS files during compilation tests
* RESOLVE Fix database error in wp-session library
* RESOLVE michael-cannon/testimonials-widget#183 Rename core class name to prevent fatal errors
* RESOLVE michael-cannon/testimonials-widget#185 Permalinks issue on plugin activation
* RESOLVE michael-cannon/testimonials-widget#193 Warning in Google's structured data testing tool for `aggregateRating`
* RESOLVE PHP Notice Undefined index: hide_reset_button
* RESOLVE Replace deprecated 'post_permalink' with 'get_permalink'
* RESOLVE Replace deprecated 'wp_htmledit_pre' with 'format_for_editor'
* Update features list with "Sticky" functionality
* Update Readmore 2.0
* Change support email address to 'support@axelerant.com'

## 2.2.0
* RESOLVE bxslider controls.png not displaying
* RESOLVE cookie.js not minified
* RESOLVE michael-cannon/testimonials-widget#151 Make bxslider options easier to modify
* RESOLVE michael-cannon/testimonials-widget#164 Read more shows when there's only excerpt, no content
* RESOLVE michael-cannon/testimonials-widget#168 Auto save options and clear permalinks on updating
* RESOLVE michael-cannon/testimonials-widget#173 Too many queries on /wp-admin/ load
* RESOLVE michael-cannon/testimonials-widget#32 Add "Stick this testimonial to the top" like option - See Add New Post > Visibility
* Update refund text
* Update store branding

## 2.1.1
* RESOLVE Incorrect controls.png path
* RESOLVE No rotation when auto-paging is enabled

## 2.1.0
* Require Testimonials Widget 3.1.0
* RESOLVE Form results not in templates
* Revise antispam debug highlighting
* Change brand name
* Change copyright text

## 2.0.4
* RESOLVE rotate_per_page not working 
* Require Testimonials Widget 3.0.4
* Update copyright year
* Update Structured Data Testing Tool link

## 2.0.3
* Add demo link to examples
* Add review count to schema data
* Require Testimonials Widget 3.0.3
* RESOLVE Clear Cache doesn't clear all
* RESOLVE Clear cache on save
* RESOLVE michael-cannon/testimonials-widget#155 Aggregate data is off per Google
* RESOLVE michael-cannon/testimonials-widget#156 License not saving
* RESOLVE michael-cannon/testimonials-widget#165 Stack of testimonials appear on load
* RESOLVE michael-cannon/testimonials-widget#166 Unable to submit required image on testimonials form
* RESOLVE PHP Notice: Undefined variable: post_id
* RESOLVE PHP Notice: Undefined variable: rotate_per_page
* Update FAQ on shortcodes

## 2.0.2
* Require Testimonials Widget 3.0.2

## 2.0.1
* Add delete free plugin notice
* FAQ Got `The plugin does not have a valid header` error?
* Require Testimonials Widget 3.0.1
* Reset options text
* RESOLVE michael-cannon/testimonials-widget#153 Default ratings when new aren't 5-stars
* RESOLVE michael-cannon/testimonials-widget#154 Performance degredation
* RESOLVE michael-cannon/testimonials-widget#158 Minify JS
* RESOLVE michael-cannon/testimonials-widget#160 Update aggregate review counts for SEO purposes
* RESOLVE michael-cannon/testimonials-widget#162 Error: This is not a valid feed template with WordPress SEO sitemap
* RESOLVE Readmore options showing in widget
* RESOLVE Slide Width stuck on 200 pixels
* RESOLVE The plugin does not have a valid header - remove blank lines between header comments
* RESOLVE Use of undefined constant Testimonials_Widget_Premium_Form - assumed 'Testimonials_Widget_Premium_Form'
* Revise installation documentation

## 2.0.0
* Add DEPRECATED.md, EXAMPLES.md, and UPGRADING.md documentation
* Add more shortcode examples
* Add RSS feed options: Title, Description, Count
* Add UPGRADING link to Change Log section
* Don't show donation or premium upgrade links by default
* Format aggregate rating value to 2 decimal places
* Moved aggregate review schema data from Testimonials Widget
* Moved author field to Testimonials Widget
* Moved Clear Cache menu item below settings
* Moved email column and data input before URL
* Moved Tutorials to below Usage section in readme
* Readme updates
* Remove `extract` call from templates for improved performance and security
* Removed filter `twp_next_text`
* Reorganize settings
* Replace "Source" with "Author"
* RESOLVE -1 ratings showing on New testimonial submission email
* RESOLVE Author missing on New testimonial submission email
* RESOLVE Form css not included when admin
* RESOLVE Form options showing in slider options
* RESOLVE michael-cannon/testimonials-widget#102 Changing the presentation order on the form
* RESOLVE michael-cannon/testimonials-widget#103 Example of every option
* RESOLVE michael-cannon/testimonials-widget#107 Make text labels easier to replace
* RESOLVE michael-cannon/testimonials-widget#108 Search results: average rating and amount of reviews
* RESOLVE michael-cannon/testimonials-widget#109 Open full testimonial in a lightbox
* RESOLVE michael-cannon/testimonials-widget#110 Allow `post_category` to be text
* RESOLVE michael-cannon/testimonials-widget#112 Remove deprecated methods
* RESOLVE michael-cannon/testimonials-widget#113 Replace testimonials_widget_ of actions, filters, and functions with tw_
* RESOLVE michael-cannon/testimonials-widget#115 Convert README.md back to readme.txt for easier EDD upgrading
* RESOLVE michael-cannon/testimonials-widget#117 Revise benefits content
* RESOLVE michael-cannon/testimonials-widget#119 Confirm WPML integration works
* RESOLVE michael-cannon/testimonials-widget#121 Updated screenshots
* RESOLVE michael-cannon/testimonials-widget#123 Revise installation and usage instructions for ease of use
* RESOLVE michael-cannon/testimonials-widget#126 Implement jQuery based readmore action
* RESOLVE michael-cannon/testimonials-widget#137 Simple aggregrate numbers needed in freebie
* RESOLVE michael-cannon/testimonials-widget#139 Auto-generate option examples
* RESOLVE michael-cannon/testimonials-widget#141 Math captcha doesn't work when going back in history
* RESOLVE michael-cannon/testimonials-widget#143 Link expired license to renewal page
* RESOLVE michael-cannon/testimonials-widget#146 Premium examples via shortcode not showing up
* RESOLVE michael-cannon/testimonials-widget#147 Templatize read more link in `truncate_content`
* RESOLVE michael-cannon/testimonials-widget#149 Restore deprecated shortcodes and theme functions to TW
* RESOLVE michael-cannon/testimonials-widget#19 Template engine
* RESOLVE michael-cannon/testimonials-widget#22 Reset rating option in testimonial edit screen
* RESOLVE michael-cannon/testimonials-widget#23 Reorganize meta data fields - Author to top
* RESOLVE michael-cannon/testimonials-widget#24 Enable searching custom fields from edit screen
* RESOLVE michael-cannon/testimonials-widget#25 Add sortable columns to edit page
* RESOLVE michael-cannon/testimonials-widget#31 Add rating counts and averages by referenced item
* RESOLVE michael-cannon/testimonials-widget#78 Show revert link in deactivation admin notice
* RESOLVE michael-cannon/testimonials-widget#81 Form: Limit the number of characters a person can enter for the summary
* RESOLVE michael-cannon/testimonials-widget#87 Fields placeholder attributes 
* RESOLVE michael-cannon/testimonials-widget#98 Implement testimonials RSS feed
* RESOLVE Shortcode Examples and Attributes always being cached
* RESOLVE Shortcode Examples and Attributes processing causing memory limit erros
* RESOLVE Shortcode Examples not showing on front-end
* Restore deprecated shortcodes and theme functions in premium only
* Restore Testimonials Widget branding
* Restore Testimonials Widget Premium branding
* Revise descriptions
* Update form widget title linking and enclosures
* Update licensing entries
* Update OPTIONS
* Utilize placeholders and defaults for testimonials form population

## 1.20.11
* Add FAQ Gravity Forms
* Add FAQ Shortcode
* Add option Hide Author Field?
* Improve Usage guidance
* Require Testimonials Widget 2.19.9
* Require WordPress 3.6+ notice

## 1.20.10
* Add action twp_form_save
* Add form field variables to settings display
* Add testimonials_form post_category tags_input example
* Enable option Exclude default CSS?
* Hide default ratings option in widget
* RELATES michael-cannon/testimonials-widget#95 Incoming YouTube links not embedded
* Require Testimonials Widget 2.19.8
* RESOLVE michael-cannon/testimonials-widget#101 5-star ratings show up sans being set 
* RESOLVE michael-cannon/testimonials-widget#89 Show/hide reset button on form
* RESOLVE michael-cannon/testimonials-widget#90 Redirect to the landing/thank-you page on successful form submission
* RESOLVE michael-cannon/testimonials-widget#97 Custom form attributes not being saved
* RESOLVE Spelling error. Thank you Chris Cane.

## 1.20.9
* Require Testimonials Widget 2.19.7

## 1.20.8
* Add filter `twp_send_mail_notification_attachment`
* Add filter `twp_send_mail_notification_body`
* Add filter `twp_send_mail_notification_headers`
* Add filter `twp_send_mail_notification_subject`
* Add filter `twp_send_mail_notification_to`
* Add tutorial videos
* README description updates
* Require Testimonials Widget 2.19.6
* RESOLVE michael-cannon/testimonials-widget#91 Multisite referral check fails when sending testimonials from a subsite
* Tested up to: 4.0.2
* Update TWP URL

## 1.20.7
* Coding standards
* Escape readme shortcode examples
* Incorporate free readme details into premium
* Require Testimonials Widget 2.19.5
* Update Aihrus links

## 1.20.6
* Include Form CSS is now set by default
* Remove Testimonials to Dashboard's At a Glance
* Require Testimonials Widget 2.19.4
* RESOLVE michael-cannon/testimonials-widget#84 Form field required asterisk not always red
* RESOLVE michael-cannon/testimonials-widget#85 New testimonial rating field is blank
* RESOLVE WordPress Akismet 3.0 class conflict
* Revise readme options layout
* Revise screenshots
* Revise settings options layout

## 1.20.5
* Add refund text to readme
* Cache dashboard_count
* Change form Summary to Testimonial Summary with small description
* Change form Text to Full Testimonial
* Convert [[/]] to [/]
* Licensing notice display to once per day
* Require Testimonials Widget 2.19.3
* RESOLVE Akismet class being redeclared
* RESOLVE Form options showing as Testimonials widget options
* RESOLVE michael-cannon/testimonials-widget#80 DB Performance Issues
* Retain license for one year
* Revise *_update_license handling
* Revise cache_get & cache_set handling
* Revise FAQ section
* Revise license handling
* Revise readme description placement

## 1.20.4
* Add option Show Content as Excerpt?
* Revise form text labels
* Show ratings label on form

## 1.20.3
* Require Testimonials Widget 2.19.2

## 1.20.2
* Correct form widget inclusion
* Require Testimonials Widget 2.19.1

## 1.20.1
* Please resave your Testimonials > Settings and Widget options for defaults to be corrected
* RESOLVE Two "Premium Options" headings showing in widget options
* Update examples

## 1.20.0
* Add form option Disable Image Upload via URL?
* Coding standards
* Require Aihrus 1.1.0
* RESOLVE michael-cannon/testimonials-widget#20 Simplify shortcodes
* RESOLVE michael-cannon/testimonials-widget#56 Prevent edit page column overload
* RESOLVE michael-cannon/testimonials-widget#66 Vertical transitions not working in widget
* RESOLVE michael-cannon/testimonials-widget#67 Use .job-title than .title for job title span class
* RESOLVE michael-cannon/testimonials-widget#73 Add option to disable image upload by URL
* RESOLVE michael-cannon/testimonials-widget#74 Form options showing when disabled
* Shortcode and theme function `testimonialswidgetpremium_count` being deprecated by `testimonials_count`
* Shortcode and theme function `testimonialswidgetpremium_form` being deprecated by `testimonials_form`
* Shortcode and theme function `testimonialswidgetpremium_link_list` being deprecated by `testimonials_links`
* Update Aihrus integration
* Update deactivation routines
* Update Testimonials 2.19.0

## 1.19.4
* RESOLVE Notice undefined index line 1156
* Update Testimonials 2.18.4

## 1.19.3
* Enable rotate per page for carousel
* RESOLVE michael-cannon/testimonials-widget#61 Horizontal and Vertical transitions not working in widget
* Revise `slide_margin` and `slide_width` defaults
* Skip rotate_per_page JS if no scripts to manipulate
* Update copyright year
* Update EDD_SL_Plugin_Updater 1.1

## 1.19.2
* Add ellipsis and read more text filter example
* Disable rotate per page when using carousel mode
* Require Testimonials Widget 2.18.2
* RESOLVE michael-cannon/testimonials-widget#55 Ratings not showing during carousel paging
* REOPEN michael-cannon/testimonials-widget#59 Blazing fast rotation, always goes to same testimonial
* RESOLVE Notice: Undefined variable: javascript  in includes/class-testimonials-widget-premium.php on line 1250
* RESOLVE Notice: Undefined variable: key in includes/class-testimonials-widget-premium.php on line 1250
* Use YouTube https

## 1.19.1
* Add FAQ How do I create a testimonial record?
* Add form option Hide Form Header?
* jQuery 1.10+ note
* NOTE michael-cannon/testimonials-widget#55 Ratings not showing during carousel paging
* Remove "Testimonials plugin is required to be activated." from header
* Require Testimonials Widget 2.18.1
* RESOLVE CSS controls.png pathing
* RESOLVE Form CSS pathing
* RESOLVE Form header shortcode attribute request
* RESOLVE Form scripts not loading in footer
* RESOLVE michael-cannon/testimonials-widget#8 Validate HTML
* RESOLVE Not recognizing base plugin requirements failure

## 1.19.0
* CLOSE michael-cannon/testimonials-widget#41 Widget submission form goes red when empty and shortcode form is used - Unable to replicate
* CLOSE michael-cannon/testimonials-widget#42 submission using “Image by URL” doesn’t work if the Form Widget and shortcode form exist together - Unable to replicate
* Include plugin Testimonials Widget directly
* Move ci to tests
* Move files to assets
* Move lib to includes/libraries
* Rename custom meta aaa_request to testimonials-widget-premium-form-request
* Rename custom meta aaa_server to testimonials-widget-premium-form-server
* RESOLVE michael-cannon/testimonials-widget#9 Show part of the post_content on the testimonials edit page
* RESOLVE michael-cannon/testimonials-widget#37 Fatal error: Call to undefined method Testimonials_Widget::strip_protocol()
* RESOLVE michael-cannon/testimonials-widget#40 “Rating” stars won’t show on the sidebar form if the shortcode submission form exists
* RESOLVE michael-cannon/testimonials-widget#46 Set default rating
* RESOLVE michael-cannon/testimonials-widget#47 Slow query issues
* RESOLVE michael-cannon/testimonials-widget#49 Testimonials missing from At a Glance widget on Dashboard
* Specify a “Text Domain” and “Domain Path”
* Update Testimonials Widget Premium Shortcode Examples 

## 1.18.2
* RESOLVE Testimonials Widget Premium load issue

## 1.18.1
* Add filter twp_scripts_display
* BUGFIX Fatal error due to inactive REQ_BASE via old Aihrus Framework
* RESOLVE michael-cannon/testimonials-widget#13 Change the star image for reviews
* RESOLVE michael-cannon/testimonials-widget#14 Update Antispam bee library
* RESOLVE michael-cannon/testimonials-widget#15 License activated?, but still showing notice
* RESOLVE michael-cannon/testimonials-widget#29 Rating stars on non-secure URL
* RESOLVE michael-cannon/testimonials-widget#30 Possible license disappearing after activation
* RESOLVE michael-cannon/testimonials-widget#33 License activated?, but still showing notice
* RESOLVE michael-cannon/testimonials-widget#35 PHP Fatal error: Class 'Testimonials_Widget_Premium_Antispam' not found
* RESOLVE michael-cannon/testimonials-widget#36 Form checking valid_human is failing despite no traffic
* Use Aihrus Framework 1.0.1
* Use aihr_check_aihrus_framework

## 1.18.0
* BUGFIX #3 PHP Warning: session_destroy
* BUGFIX #6 Testimonials not activated causes error
* BUGFIX Notices not showing after deactivation
* Hide Item URL Field by default
* Implement PHP version checking
* Implement WordPress version checking
* Improve `valid_human` debug details
* Replace TODO with https://github.com/michael-cannon/testimonials-widget-premium/issues
* Revise anti-spam check human notice
* Revise readme structure
* Tested up to 3.9.0
* Update support FAQ

## 1.17.5
* Update Aihrus framework

## 1.17.4
* $this to __CLASS__
* BUGFIX Non static deactivation method called

## 1.17.3
* Update Aihrus framework

## 1.17.2
* Add PHP 5.3+ required notice
* Add default gallery
* Check for PHP 5.3

## 1.17.1
* BUGFIX Field codes showing on form

## 1.17.0
* Absolute path includes
* Add LICENSE
* Add Shortcodes helper page - Thank you Scott Hendison
* Add TW_PLUGIN_DIR path
* Add buy link to premium license notice
* Add valid_referer debugging
* Allow plugin usage without license
* Always `check_notices`
* BUGFIX Adjust valid_referer checking for sub-directory installations
* BUGFIX Clear cache deleted license key
* BUGFIX JavaScript generated even when no testimonials
* BUGFIX Testimonial updates don't clear their related cache entries
* Begin abstracting common methods
* Consolidate notices to aihrus
* Convert include_once to require_once
* Deactivate license on uninstall
* Delete notices on deactivation
* Disable upgrading when license in invalid
* Enable `set_notice` with frequency limits
* Enable activation and version checking
* Enable upgrading when plugin isn't activated
* Hide premium options until valid license activation
* Include settings earlier
* Mark cacheables with similar key for easier cleanup
* Move `notice_version` to aihrus framework
* Move notification framework to aihrus directory
* Moved `testimonials-widget-premium-form.css` to css directory
* Properly enable licensing
* Rebrand Testimonials Widget as Testimonials
* Refactor Aihrus licensing for class inheritance
* Refactor license handling
* Remove API's source code link
* Remove donation buttons from plugins page
* Rename PLUGIN_FILE to PLUGIN_BASE
* Rename `TRANSIENT_BASE` as `SLUG`
* Show enable licensing notice as needed
* Update TODO
* Update readme options
* Use transients speed up license checking

## 1.16.0
* Add option Show Next/Prev Controls?
* Add option controls
* Add option pager
* BUGFIX Auto-rotate per page indexing incorrectly
* BUGFIX Form CSS is missing
* Enable carousel options to display multiple testimonials at once and rotate them
* Focus on first form error
* Revise readme features
* Update PHPCS config
* Update for bxSlider
* Use EDD License Handler licensing and updating
* Use const JS_KEY
* Use startSlide for rotate_per_page

## 1.15.0
* Adapt JavaScript for animatation transitions
* Add Review schema to link shortcode code
* Add rating, item name, and item url to form
* Add ratings to testimonials display
* Add screenshot 22. Using Review and AggregateRating schema data structures
* Added author field to override Review schema author
* Added item reviewed and url fields for Review schema per testimonial
* Added simple rating to testimonials
* Automatically clear cache on updates
* BUGFIX Auto-rotate per page breaks testionial widget viewing
* BUGFIX Caching is happening despite `no_cache` is set
* BUGFIX Combined required states not selecting unique testimonials
* BUGFIX Correct Review schema property name to description
* BUGFIX Failed combined required states selecting all testimonials
* BUGFIX Form CSS always included
* BUGFIX Image not linked to read more alternative. Thank you Jay Ramirez.
* BUGFIX Image span is wrapped by a tag 
* BUGFIX Multiple same rating instances on a page fails
* BUGFIX No rating value default
* BUGFIX PHP Notice `no_cache`
* BUGFIX Rating label missing on form entry
* BUGFIX Ratings break in testmode
* BUGFIX Schema properties contain HTML and entities
* BUGFIX Stars disappear after initial list view when cached
* BUGFIX Stars disappear after initial single view when cached
* BUGFIX Stars disappear after initial widget view when cached
* BUGFIX query_args not respecting posts to include
* Bring CSS up to coding standards
* Clean up JavaScript
* Display ratings as images in admin
* Enable rich snippets/structured data per [review schema](http://schema.org/Review)
* Exclude `js/jquery.raty.min.js` from phpcs
* Integrate [jQuery Raty](http://wbotelhos.com/raty) – A Star Rating Plugin
* Load scripts and stylesheets only when needed
* Made centering linked featured image easier 
* Move ratings input and view before excerpt
* Remove author lines
* Require minimumal testimonial rating for display
* Revise ratings in emails with max
* SEO tweaks
* Set rating via stars on form
* Travis ignore WordPress.WhiteSpace.ControlStructureSpacing - false positives
* Update TODO
* Update readme
* Update schema handling
* Update screenshots
* Updated link shortcode code
* Use API tw_schema_review for setting review name
* Use `is_true` validation
* Use excerpt as name for Review schema

## 1.14.4
* Add Admin Antispam Debug Help Text
* Add option Include Form CSS?
* Add option Use Table Form Layout?
* Add settings page help tab
* Add testimonials-widget-premium-form.css
* BUGFIX plugin_row_meta not returning default state
* CSS ID `testimonials-widget-premium-form` changed to class
* Clean up `testmode` debug display
* Option `hide_featured_image_url` renamed to `hide_featured_image`
* Remove unused `$period` paramter from `twp_set_transient`
* Show Antispam Bee reason during `testmode
* Update TODO
* Update form related screenshots
* Update phpcs ruleset
* Upload testimonial image via URL on front-end
* Use CSS based form layout to aid small space adaption

## 1.14.3
* BUGFIX Auto-rotate by page not sequential

## 1.14.2
* BUGFIX Caching defaults not correct

## 1.14.1
* BUGFIX Caching defaults not correct

## 1.14.0
* Add WP Admin > Testmonials > Clear Cache menu link
* Add option Auto-Rotate Page-to-Page?
* Add option Cache Per Page?
* Add option Disable Donate Text?
* Add screenshot 19. Removed donation and premium plugin purchase links
* Add screenshot 20. Clear Cache page results
* BUGFIX instance numbers breaking caching
* BUGFIX second testimonial showing when Auto-Rotate Page-to-Page is enabled
* Change &$this variable calls to $this
* Consolidate clear cache calls
* Remove Clear Cache from Setting
* Remove unused first parameter from `cache_get`
* [Holding the testimonials position from page to page](https://aihrus.zendesk.com/agent/#/tickets/489)

## 1.13.5
* Clear cache when WP Super Cache, FlexiCache, Hyper Cache, DB Cache Reloaded Fix does
* Create working plugin install and activate notice links
* Don't error out on activation if no free version is active
* Rename Title to Job Title
* Update TODO

## 1.13.4
* Add StillMaintained.com notice
* BUGFIX Warning strlen on is_email
* Prevent checking disable cache and prevent duplicates at same time
* Update POT
* Update README.md
* Use esc_html__ on localization

## 1.13.3
* Add video introduction
* BUGFIX Excerpt missing from post and CPT edit page
* Correct load ordering
* Move localization load to init()
* Rename PLUGIN_FILE to ID
* Rename internal TW constants to FREE

## 1.13.2
* Add filter twp_form_heading to customize form heading
* Add initial API doc
* BUGFIX Blank testimonial form submissions allowed
* BUGFIX session_destory PHP warning
* Begin Circle CI integration
* Coding standards code updates
* Correct settings class filename
* Correctly spell premium in filter names
* Create TODO doc
* Don't load plugin until all are loaded
* Enable PHP Mess Detector for CI
* TODO Upload image via link

## 1.13.1
* BUGFIX Include settings class for tw_set_option
* BUGFIX Link targets affected page navigation
* BUGFIX Saving post submissions despite being invalid or spam
* Convert 'Are you a real person?' to math captcha
* Enable 'custom-fields' for Admins - helpful to debug spam submissions
* Implement sessions form anti-spam checks
* Incorporate Antispam Bee methods directly
* Rename 'Are you a real person?' to 'What is the sum of…?'
* Show Akismet API ID if nothing given
* Track _SERVER & _REQUEST for spam purposes
* Update readme.txt with Form Options
* Validate form submission before anti-spam checks

## 1.13.0
* Add form option: Hide "Are you a real person?" – A simple anti-spam helper
* Add referrer and user_agent checking
* Attach user image to testimonial submission email
* Breakout anti-spam helpers into individual class files
* Change hpsc_session on every page load
* Dashboard testimonials pending count statistics
* Disable Antispam Bee helper until private methods become protected
* Enable Akismet anti-spam checking
* FEATURE Multiple antispam techniques
* Move antispam checks to Testimonials_Widget_Premium_Antispam_Simple class
* Prevent multiple form instances from tracking each other
* Revise "a real person" error check message
* Revise TODOs
* Revise activation and deactivation handling
* Update form related screenshots
* Use donate button than text

## 1.12.1
* BUGFIX [Save form posting on specific category](https://aihrus.zendesk.com/agent/#/tickets/44)
* BUGFIX [Testimonials Widget Premium getting SPAMMED](http://wordpress.org/support/topic/testimonials-premium-getting-spammed)
* BUGFIX [image/gravatar image cant be hidden](http://wordpress.org/support/topic/imagegravatar-image-cant-be-hidden?replies=1)
* Enable disallow_edit in widget options
* FEATURE Multiple anti-spam traps
* FEATURE [Show thank you than edit on form submission](https://aihrus.zendesk.com/agent/#/tickets/46)
* Invoke class for active TW or TWP plugin - helps ensure deactivate fires as needed
* Invoke widget instance count for shortcode calls
* Remove unused code
* TODO Twitter referencing
* Update FAQ & support links to knowledge base
* Update Premium product page URL
* Update screenshots

## 1.12.0
* Add filter `twp_form_options` - Customize form options
* Added simple honey pot spam trap
* BUGFIX Non-static method Testimonials_Widget_Premium_Form::show_form() should not be called statically on line 317 in file /Users/michael/Sites/wp/wp-content/plugins/testimonials-widget-premium/lib/class-testimonials-widget-premium-form.php
* BUGFIX Non-static method Testimonials_Widget_Widget::display_setting() should not be called statically, assuming $this from incompatible context on line 144 in file /Users/michael/Sites/wp/wp-content/plugins/testimonials-widget-premium/lib/class-testimonials-widget-premium-form-widget.php
* BUGFIX [Read more showing when not needed](http://wordpress.org/support/topic/premium-read-more-link-showing-up-on-short-testimonials-that-dont-need-it)
* CSS rename testimonialswidget_excerpt to testimonials-widget-premium-excerpt
* CSS rename testimonialswidgetpremium_form to testimonials-widget-premium-form
* Clean out TODOs
* Default Testimonial Category picker
* Enable [Require email](http://wordpress.org/support/topic/some-questions-about-the-premium-version) via form_options filter
* FEATURE Testimonials submission form widget
* Form test mode
* Remove $blank from testimonials_truncate()
* Remove $testimonials from get_testimonials_paging
* Remove unused code
* Rename testimonials-widget-premium-cache.php class-testimonials-widget-premium-cache.php 
* Rename testimonials-widget-premium-form.php class-testimonials-widget-premium-form.php
* Spellcheck readme.txt
* Update CSS for WordPress Coding Standards
* Update JavaScript for WordPress Coding standards
* Update PHP for WordPress Coding Standards
* Update POT
* Update screenshots

## 1.11.2
* BUGFIX [Can't set featured image](http://wordpress.org/support/topic/cant-set-featured-image-3)
* Begin WordPress coding standard cleanup
* Clean up static method calls to prevent PHP Strict notices
* Correct WordPress URL usage for plugin updates
* Remove unused activation method
* Update FAQ URL
* Update POT

## 1.11.1
* Activation hook placeholder
* Add plugin screen link to `notice_version`
* BUGFIX [preventing the site from communicating with WP plugins API](http://wordpress.org/support/topic/premium-options-missing?replies=5#post-4063230)
* Form widget placeholder
* Require TW version 2.11.1
* Set sections and settings filters via constructor
* Update TODOs
* Update text domain for localization and POT

## 1.11.0
* Add Donate link to plugin row meta
* Add Location field to link listing
* Add Mail Notification option to form
* Add Settings to plugin action links
* Auto-update disable if not on plugins page
* BUGFIX [Convert q tags to blockquote](http://wordpress.org/support/topic/open-link-in-new-tab-html-validation) [Why?](http://www.w3schools.com/tags/tag_blockquote.asp)
* BUGFIX [How to hide "Read more" on testimonials not exceeding the characterlimit?](http://wordpress.org/support/topic/how-to-hide-read-more-on-testimonials-not-exceeding-the-characterlimit)
* BUGFIX check for global post when no posts
* Deactivate if no active or incorrect version of Testimonials Widget plugin
* Description update
* Email notification for user submitted testimonials
* Enable Default Testimonial Author and Mail Recipient for user submission based upon administrator and editor users
* Ignore excerpt in single view
* Ignore init() during AJAX and autosave operations
* Install or update Testimonials Widget plugin notice
* Replace filter `tw_disable_more_link` with setting `hide_read_more`
* Screenshot 15 New testimonial submission email
* Simplify CSS naming
* Update POT
* Update TODOs
* Update features
* Update general verbiage
* Update internal version tracking
* Update readme formatting
* Use self::$base for self checking

## 1.10.1
* BUGFIX [Testimonials Widget Premium crashed my site](http://wordpress.org/support/topic/testimonials-premium-crashed-my-site)
* Moved [FAQ](http://aihr.us/testimonials-widget-premium/faq/)
* Update POT
* Update Premium link

## 1.10.0
* Add form fields
* Add option - Add `nofollow` to "Read more" Links?
* Add screenshot 13 `[[testimonialswidgetpremium_form]]` – Add a Testimonial
* Add screenshot 14 Testimonials Widget Settings > Entry Form tab
* Apply nonce
* Begin `[[testimonialswidgetpremium_form]]` work
* Change HTML layout to id than class
* Clean language domains
* Create form and cache library files
* Default post author, category, and status settings
* Form demo link
* Move Clear Cache option to top
* Move Changelog to changelog.txt
* Move validation to configuration
* Revise entry form verbiage
* Revise form labels
* Revise form_options loading
* Revise style register and encoding key
* Screenshot 3 updated
* Set user submitted meta data
* Strip slashes on user input for front-end
* Update Form Shortcode Options
* Update description
* Update POT
* Upload and show image
* Validate form via Testimonials_Widget_Settings::validate_settings
* Validate with errors on show_form

## 1.9.1
* Testimonials entry form `[[testimonialswidgetpremium_form]]` – Coming Soon
* Version tracking for default settings 
* `post_type=post` shortcode example

## 1.9.0
* FEATURE Rotate built-in and custom post types
* Screenshot 3 updated
* Screenshot 12 updated
* Update POT
* Update description

## 1.8.0
* Centralize settings, validation and defaults for use with Settings and Widgets
* FEATURE Settings screen
* FEATURE Wrap image in link
* FEATURE Force "Read more" link
* Localization, Spanish updates
* Remove `defaults`
* Screenshot 3 updated
* Screenshot 12 added
* Update POT
* Variable rename
* Verbiage updates

## 1.7.11
* BUGIX $response var warning
* BUGIX "Hide Read More" works with excerpts now
* CSS Show cursor on "Next testimonial" text – Thank you [LittleFish56](http://wordpress.org/support/topic/next-testimonial-link-not-showing-hand-on-hover) for this suggestion
* Localization, Spanish

## 1.7.10
* Add filter `twp_next_text`
* FAQ 20 Change the 'Next testimonial…' text

## 1.7.9
* BUGFIX Purge transients time adjustment
* Begin [[testimonialswidgetpremium_form]] shortcode
* FAQ 19 Style alternating background colors
* Features update
* Localization, Hebrew
* Screenshot 11. Alternating background colors – Courtesy of [placeofstillness](http://www.heartattune.com/clients-say/)

## 1.7.8
* BUGFIX Post caching `unique` not always unique
* Change Aihrus support email to contact form
* Revise cache clearing
* Screenshot 2 updated
* Update features

## 1.7.7
* Add option `require_excerpt`
* BUGFIX extra excerpt in single view
* Screenshot 3 updated
* Update features
* Update shortcode examples

## 1.7.6
* Enable `before` and `after` for `paging`
* Enable excerpts for `is_list` mode
* Feature - Ensure unique testimonial display per page
* Only show "…" if content is truncated when appeding "Read more"
* Revert on-demand CSS to always included
* Support section updated
* Update description

## 1.7.5
* Author URL update
* Enable CSS class `.even` and `.odd` for testimonial entries
* FAQ 18 Alternating `.even` and `.odd` CSS classes
* Only include CSS if testimonials instance called
* Screenshot 3 update
* Update POT

## 1.7.4
* BUGFIX PHP Warning missing `no_cache`
* Escape shortcode examples
* FAQ 1 update
* FAQ 17 Show multiple testimonials in rotation
* Prevent on page duplicates
* Proper caching for `unique`. Maybe implement counter or tracker to only do sequence once on a page
* Screenshot 1 revised
* Screenshot 10 added
* Screenshot 4 revised
* Screenshot 7 updated
* Screenshot 9 added
* Update premium features
* WPML compatible
* `unique` Requires caching to be enabled

## 1.7.3
* `no_cache` option
* Screenshot 2 updated
* Screenshot 3 updated

## 1.7.2
* BUGFIX $wp_version PHP Notice
* Apply CSS `close_quote` class after truncation for Testimonials Widget 2.7.3

## 1.7.1
* CSS `.testimonialswidget_excerpt` styling
* FAQ 6 update
* FAQ 16 Show the expert and hide the image in the testimonial single view
* Filter `tw_disable_cache` default state now true
* Hide Excerpt option added
* Remove `deactivate_plugins( Testimonials_Widget_Premium::Testimonials_Widget_Premium_Plugin )` when Testimonials Widget plugin isn't active
* Screenshot 2 updated
* Screenshot 3 updated
* Screenshot 4 updated
* Screenshot 5 updated
* Screenshot 6 updated
* Screenshot 7 updated
* Screenshot 8 revised
* Update POT

## 1.7.0
* Information relocation…
* Swap screenshot 3 & 8
* TODO Update notification link
* Tags update
* Update notification for automatic upgrading
* Whitespacing

## 1.6.0
* Add "Read more" link column to testimonial posts page
* Deletes related cache entries on testimonial update
* Enabled alternate destinations for "Read more" links
* FAQ 15 clear old `testimonialswidget` transients from `wp_options`
* Increase `.testimonialswidget_testimonial .testimonialswidget_next` top margin spacing
* Purge old style transient cache entries on activation
* Rename cron scheduling functions
* Update POT

## 1.5.0
* Add [Next testimonial](http://wordpress.org/support/topic/plugin-testimonials-widget-next-testimonial-not-pagination) link to widget display
* Add shortcode [[testimonialswidgetpremium_count]] for count of selected testimonials
* Add theme functions `testimonialswidgetpremium_count`, `testimonialswidgetpremium_link_list`
* BUGFIX - New premium options have no defaults
* Compatible with WordPress 3.6
* Delete old transient cache entries
* Description update
* Enable selecting only testimonials with featured image
* Enabled minimum and maximum testimonial length requirements - only select testmonials meeting these needs. Helps only select shorter testimonials for small boxes and longer for large. :)
* FAQ 11 No testimonial content for [[testimonialswidgetpremium_link_list]]
* FAQ 12 [[testimonialswidgetpremium_link_list]] paging only works on pages
* FAQ 13 Use the theme functions
* FAQ 14 Style "Next testimonial…" text block
* Fixed paging for [list of links to all testimonials](http://wordpress.org/support/topic/list-of-testimonials-links-to-each)
* Refactor `query_args` selection process
* Remove `activation` code bits
* Screenshot 3 update
* Screenshot 7 `require_image`, `minimum_length` and `maximum_length` option examples
* Screenshot 8 Widget with 'Next testimonial…' link
* Set transient base name
* TODO Add – Delete old transient cache entries
* TODO Add – Select only testimonials with featured image
* TODO Remove – Enable Testimonials Widget activation on `activation`
* Update POT
* Update Shortcode Examples

## 1.4.0
* Add "Shortcode Examples"
* Add excerpt support in edit page and widget view
* Copyright year update
* FAQ 9. Testimonial excerpt in single view
* FAQ 10. Style single view excerpt
* Minor code clean up
* Optionally add excerpt to single view
* Screenshot 5. Widget with 'sample excerpt' and 'Read more' link
* Screenshot 6. Single view with 'sample excerpt'
* TODO Add - Client supplied testimonials with Dashboard review

## 1.3.3
* BUGFIX `truncate_content` worked on "No testimonials found"

## 1.3.2
* Add filter `twp_html_link`
* Add FAQ 8 Customize testimonials title links list
* Remove TODO [Template engine](http://wordpress.org/support/topic/move-image-to-below-quote?replies=1) - using filters

## 1.3.1
* Aihrus branding
* Description update

## 1.3.0
* Adapt for `keep_whitespace`
* Add filters verbiage to description
* Correct `clearcache` query arg - darn `get_query_var` doesn't work
* Donate to purchase verbiage change - One bad experience ruins it for all
* TODO clean up from free version
* Tested up to 3.5.0

## 1.2.4
* FAQ 2 Update `add_filter` helper for `twp_more_ellipsis`
* Filters list update
* Update POT

## 1.2.3
* BUGFIX Missing CSS fix 

## 1.2.2
* Add screenshot 4
* FEATURE Add shortcode `[[testimonialswidgetpremium_link_list]]`

## 1.2.1
* Donate link update
* [More text verbiage improvement](http://wordpress.org/support/topic/very-easy-to-use-moderately-easy-to-style?replies=14)
* TODO Add alternate read more links

## 1.2.0
* Add "Filter Options" section
* Add filter `tw_disable_cache`
* Add filter `tw_disable_more_link`
* BUGFIX [No image](http://wordpress.org/support/topic/update-17?replies=4) in [widget](http://wordpress.org/support/topic/plugin-testimonials-widget-short-rotating-testimonial-link-to-the-full-testimonial?replies=16)
* Begin coding shortcode `testimonialswidgetpremium_link_list`
* FAQ 6 Disable caching
* FAQ 7 Disable read more links
* TODO Add template engine
* TODO Update notifications
* Update donate link
* Use `get_query_var` than $_REQUEST

## 1.1.1
* Activate only if Testimonials Widget plugin is active
* Clean up tags per [plugin guidelines](http://wordpress.org/extend/plugins/about/guidelines/)
* FAQ 5 No activation
* OPTION add `hide_read_more`
* SCREENSHOT Widget Premium Options

## 1.1.0
* Add CSS file
* Add `clearcache` option
* Add language file
* Add read more links
* FAQ 2 `add_filter` helper for `twp_more_text`
* FAQ 3 "Page not found" URL
* FAQ 4 `add_filter` helper for `twp_link_title_text`
* SCREENSHOTs 'Read more' links

## 1.0.0
* Revise as WordPress plugin

## 20121101
* Initial release