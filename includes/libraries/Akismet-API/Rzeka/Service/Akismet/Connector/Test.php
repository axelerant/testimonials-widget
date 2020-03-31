<?php
/**
 * Akismet API cURL connector
 *
 * @category  Service
 * @package   Akismet
 * @author    Jonathan Kim <jkimbo@gmail.com>
 * @copyright 2013-2014 rzeka.net
 * @license   https://gist.github.com/rzeka/8687183 New BSD License
 * @link      http://www.github.com/rzekanet/Akismet-API
 */
namespace Rzeka\Service\Akismet\Connector;

use \Exception;

/**
 * Akismet API Test connector that just mocks out api calls
 *
 * @category  Service
 * @package   Akismet
 * @author    Jonathan Kim <jkimbo@gmail.com>
 * @copyright 2013-2014 rzeka.net
 * @license   https://gist.github.com/rzeka/8687183 New BSD License
 * @link      http://www.github.com/rzekanet/Akismet-API
 */
class Test implements ConnectorInterface
{
    /**
     * Holds API key
     *
     * @var string
     */
    private $apiKey;

    /**
     * URL to query
     *
     * @var string
     */
    private $apiUrl;

    /**
     * User Agent string sent in query
     *
     * @var string
     */
    private $userAgent;

    /**
     * The front page or home URL of the instance making the request
     *
     * @var string
     */
    private $url;

    /**
     * Last error message. It's null if there is no error
     *
     * @var string
     */
    private $error;

    /**
     * Comment author that should always be flagged as spam
     *
     * @var string
     */
    private $spamAuthor = "viagra-test-123";

    /**
     * Constructor checks if cURL extension exists and sets API url
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->apiUrl = sprintf('http://%s/%s/', self::AKISMET_URL, self::AKISMET_API_VERSION);
    }

    /**
     * Checks if Akismet API key is valid
     *
     * @param string $apiKey Akismet API key
     * @param string $url    The front page or home URL of the instance making the request
     *
     * @return boolean
     */
    public function keyCheck($apiKey, $url)
    {
        $check = $this->query(
            array(
                'key' => $apiKey,
                'blog' => $url
            ),
            self::PATH_KEY,
            self::RETURN_VALID
        );

        if ($check === true) {
            $this->apiKey = $apiKey;
            $this->url = $url;
            $this->apiUrl = sprintf('http://%s.%s/%s/', $this->apiKey, self::AKISMET_URL, self::AKISMET_API_VERSION);
            return true;
        } else {
            return false;
        }
    }

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
     * @return boolean True if message has been marked as ham
     */
    public function sendHam(array $comment)
    {
        return $this->query($comment, self::PATH_HAM, self::RETURN_THANKS);
    }

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
    public function sendSpam(array $comment)
    {
        return $this->query($comment, self::PATH_SPAM, self::RETURN_THANKS);
    }

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
    public function check(array $comment)
    {
        // If spam on purpose return true
        if ($comment['comment_author'] == $this->spamAuthor) {
            return true;
        }

        // Otherwise it is never spam
        return false;
    }

    /**
     * Makes query to Akismet API and checks the response
     *
     * @param array  $comment Message data. Required keys:<br />
     *      permalink - the permanent location of the entry the comment was submitted to<br />
     *      comment_type - may be blank, comment, trackback, pingback, or a made up value like "registration"<br />
     *      comment_author - name submitted with the comment<br />
     *      comment_author_email - email address submitted with the comment<br />
     *      comment_author_url - URL submitted with comment<br />
     *      comment_content - the content that was submitted
     * @param string $path    API method to use self::PATH_*
     * @param string $expect  Expected response self::RETURN_*
     *
     * @return boolean True is response is same as expected
     */
    private function query(array $comment, $path = self::PATH_CHECK, $expect = self::RETURN_TRUE)
    {
        $this->error = null;

        // Always return true
        return true;
    }

    /**
     * Sets User Agent string for connection
     * Akismet asks to set UA string like: AppName/Version | PluginName/Version
     *
     * @param string $userAgent UA string
     *
     * @return boolean
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return true;
    }

    /**
     * Gets last error occurred
     *
     * @return string Returns null if there's no error
     */
    public function getError()
    {
        return $this->error;
    }
}
