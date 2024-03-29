<?php
/**
 * RSS2 Feed Template for displaying RSS2 Testimonials feed.
 *
 * @package WordPress
 */


header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option( 'blog_charset' ).'"?'.'>';

/**
 * Fires between the <xml> and <rss> tags in a feed.
 *
 * @since 4.0.2
 *
 * @param string  $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
/**
 * Fires at the end of the RSS root to add namespaces.
 *
 * @since 2.0.0
 */
do_action( 'rss2_ns' );
?>
>

<channel>
	<title><?php echo tw_get_option( 'rss_title' ); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss( 'url' ) ?></link>
	<description><?php echo tw_get_option( 'rss_description' ); ?></description>
	<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<?php
$duration = 'hourly';

/**
 * Filter how often to update the RSS feed.
 *
 * @since 2.1.0
 *
 * @param string  $duration The update period.
 *                         Default 'hourly'. Accepts 'hourly', 'daily', 'weekly', 'monthly', 'yearly'.
 */
?>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', $duration ); ?></sy:updatePeriod>
	<?php
$frequency = '1';

/**
 * Filter the RSS update frequency.
 *
 * @since 2.1.0
 *
 * @param string  $frequency An integer passed as a string representing the frequency
 *                          of RSS updates within the update period. Default '1'.
 */
?>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', $frequency ); ?></sy:updateFrequency>
	<?php
/**
 * Fires at the end of the RSS2 Feed Header.
 *
 * @since 2.0.0
 */
do_action( 'rss2_head' );

$count      = tw_get_option( 'rss_count', 10 );
$query_args = array(
	'post_type' => Axl_Testimonials_Widget::PT,
	'posts_per_page' => $count,
);

$query = new WP_Query( $query_args );
while ( $query->have_posts() ) {
	$query->the_post();
	$post_id = get_the_ID();
	$author  = get_post_meta( $post_id, 'testimonials-widget-author', true );
	if ( empty( $author ) ) {
		$author = get_the_title();
	}
	?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link_feed(); ?></comments>
		<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
		<dc:creator><![CDATA[<?php echo $author; ?>]]></dc:creator>
		<?php the_category_rss( 'rss2' ) ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
	<?php if ( get_option( 'rss_use_excerpt' ) ) { ?>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
	<?php } else { ?>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
	<?php $content = get_the_content_feed( 'rss2' ); ?>
	<?php if ( strlen( $content ) > 0 ) { ?>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
	<?php } else { ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
	<?php } ?>
	<?php } ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link( null, 'rss2' ) ); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
	<?php rss_enclosure(); ?>
	<?php
	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
	<?php } ?>
</channel>
</rss>
