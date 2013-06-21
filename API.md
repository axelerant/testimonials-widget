Testimonials Widget API Docs
======================

The [Testimonials Widget plugin](http://wordpress.org/plugins/testimonials-widget/) comes with its own set of actions and filters, described below, first the [actions](#actions), then the [filters](#filters), then some [example use cases](#examples).

Actions
-------

*   **Frontend**

    *   `wpseo_head`

        Runs at the end of the output of _all_ SEO meta tags by the plugin, before the closing debug comment.
    *   `wpseo_opengraph`

        Runs at the end of the output of OpenGraph meta tags by the plugin, before the closing debug comment.
    *   `wpseo_do_sitemap_&lt;$type&gt;`

        Runs in the creation of XML sitemaps, $type can **not** be a valid post type or taxonomy, when they are this action will not trigger.

*   **Backend**

    *   `wpseo_dashboard`

        Runs on the SEO admin dashboard page.
    *   `wpseo_xmlsitemaps_config`

        Runs on the SEO Admin page for XML Sitemaps.

Filters
-------

*   **Frontend**

    *   `wpseo_canonical` (string)

        Allows changing of the canonical URL. Returning false will disable the canonical.
    *   `wpseo_title` (string)

        Allows changing the title being output.
    *   `wpseo_metadesc` (string)

        Allows changing of the meta description. Returning false will disable the meta description.
    *   `wpseo_author_link`

        Change the author link added in the `&lt;head&gt;` or return false to disable it.
    *   `wpseo_metakey` (string)

        Allows changing of the meta keywords. Returning false will disable the meta keywords.
    *   `wpseo_locale` (string)

        Allows changing the locale used in the opengraph set (and possibly in other locations later on).
    *   `wpseo_opengraph_type` (string)

        Allows changing the content type for the current page being output in the opengraph set.
    *   `wpseo_opengraph_image` (string)

        Allows changing the image(s) being output in the opengraph set.
    *   `wpseo_opengraph_image_size` (int)

        Allows changing the image size used for the output of the featured image in the opengraph set.
    *   `wp_seo_get_bc_title` (string)

        Allows changing the title used for the current page in the breadcrumb.
    *   `wp_seo_get_bc_ancestors` (array)

        Allows changing the ancestors for the current page in the breadcrumb.
    *   `wpseo_whitelist_permalink_vars` (array)

        Allows changing the array of whitelisted permalink variables, useful for plugin authors who get complaints from users who are using the plugins permalink redirect function.
    *   `wpseo_prev_rel_link` and `wpseo_next_rel_link` (string)

        Filter to change the `rel="next"` and `rel="prev"` links output by Testimonials Widget, by returning false they won’t show.
    *   `wpseo_xml_sitemap_img_src` (string)

        Allows you to change the URL for images embedded in the XML sitemap. Most common usecase is to make sure the CDN URL is embedded.

*   **Backend**

    *   `wpseo_options` (array)

        Allows you to filter the array of options used by the WP SEO plugin, mostly so you can add your own if needed.
    *   `wpseo_use_page_analysis` (boolean)

        Returning false on this will disable the page analysis score from showing up in publish box and edit posts pages.
    *   `wpseo_show_date_in_snippet_preview`&nbsp;(boolean)

        Returning false on this will prevent the date from showing up in the snippet preview.

Examples
--------

*   **Disable canonical URLs**

    To disable the canonical entirely, you could do the following:

*   **Disable rel=”next” on home**

    To disable the rel=”next” link on your homepage if you have a static homepage but are not using the corresponding WordPress settings:

*   **Change meta keywords**

    To always add ‘yoast’ to your meta keywords (which is a bad idea):

*   **Change the OpenGraph type of a page**

    To change the OpenGraph type of page X, do the following:

*   **Switch XML Sitemap image URLs to CDN**

    The following, when replaced with your domain name and CDN url, would make the sitemap contain your CDN image URLs:
