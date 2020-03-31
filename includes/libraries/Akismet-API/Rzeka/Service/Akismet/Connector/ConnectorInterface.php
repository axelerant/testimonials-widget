<?php
/**
 * Akismet API
 *
 * Small library to access Akismet API
 *
 * @category  Service
 * @package   Akismet
 * @author    Piotr Rzeczkowski <piotr@rzeka.net>
 * @copyright 2013-2014 rzeka.net
 * @license   https://gist.github.com/rzeka/8687183 New BSD License
 * @link      http://www.github.com/rzekanet/Akismet-API
 */
namespace Rzeka\Service\Akismet\Connector;

/**
 * Interface for connector classes
 *
 * @category  Service
 * @package   Akismet
 * @author    Piotr Rzeczkowski <piotr@rzeka.net>
 * @copyright 2013-2014 rzeka.net
 * @license   https://gist.github.com/rzeka/8687183 New BSD License
 * @link      http://www.github.com/rzekanet/Akismet-API
 */
interface ConnectorInterface
{
    /**
     * Base URL of Akismet API
     */
    const AKISMET_URL = 'rest.akismet.com';

    /**
     * Akismet API version to use
     */
    const AKISMET_API_VERSION = '1.1';

    /*
     * ==========
     * Possible API return values
     * ==========
     */
    const RETURN_TRUE = 'true';
    const RETURN_FALSE = 'false';
    const RETURN_INVALID = 'invalid';
    const RETURN_VALID = 'valid';
    const RETURN_THANKS = 'Thanks for making the web a better place.';

    /*
     * ==========
     * API methods
     * ==========
     */

    /**
     * API method to verify key
     */
    const PATH_KEY = 'verify-key';

    /**
     * API method to check if message is spam
     */
    const PATH_CHECK = 'comment-check';

    /**
     * API method to mark message as spam
     */
    const PATH_SPAM = 'submit-spam';

    /**
     * API method to mark message as ham (not-spam)
     */
    const PATH_HAM = 'submit-ham';

    /**
     * Checks if Akismet API key is valid
     *
     * @param string $apiKey Akismet API key
     * @param string $url    The front page or home URL of the instance making the request
     *
     * @return boolean
     */
    public function keyCheck($apiKey, $url);

    /**
     * Marks message as spam
     *
     * @param array $comment Message data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * @return boolean True if message has been marked as spam
     */
    public function sendSpam(array $comment);

    /**
     * Marks message as ham (not-spam)
     *
     * @param array $comment Message data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * @return boolean True if messahe has been marked as ham
     */
    public function sendHam(array $comment);

    /**
     * Check if message is spam or not
     *
     * @param array $comment Message data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     *
     * @return boolean True if message is spam, false otherwise
     */
    public function check(array $comment);

    /**
     * Sets User Agent string for connection
     * Akismet asks to set UA string like: AppName/Version | PluginName/Version
     *
     * @param string $userAgent UA string
     *
     * @return boolean
     */
    public function setUserAgent($userAgent);

    /**
     * Gets last error occurred
     *
     * @return string Returns null if there's no error
     */
    public function getError();
}
