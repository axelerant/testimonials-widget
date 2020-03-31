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
 * Akismet API PHP (socket) connector
 *
 * @category  Service
 * @package   Akismet
 * @author    Piotr Rzeczkowski <piotr@rzeka.net>
 * @copyright 2013-2014 rzeka.net
 * @license   https://gist.github.com/rzeka/8687183 New BSD License
 * @link      http://www.github.com/rzekanet/Akismet-API
 */
class PHP implements ConnectorInterface
{
    /**
     * Holds API host
     *
     * @var string
     */
    private $apiHost;

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
     * Constructor checks if cURL extension exists and sets API url
     */
    public function __construct()
    {
        $this->apiHost = self::AKISMET_URL;
        $this->apiUrl = sprintf('/%s/', self::AKISMET_API_VERSION);
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
            $this->apiUrl = sprintf('/%s/', self::AKISMET_API_VERSION);
            $this->apiHost = sprintf('%s.%s', $this->apiKey, self::AKISMET_URL);
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
     * @return boolean True if messahe has been marked as ham
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
        return $this->query($comment, self::PATH_CHECK, self::RETURN_TRUE);
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

        if ($path !== self::PATH_KEY) {
            $comment['blog'] = $this->url;
            if (!array_key_exists('user_ip', $comment)) { //set the user ip if not sent
                $comment['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }

            if (!array_key_exists('user_agent', $comment)) { //set the ua string if not sent
                $comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            }

            if (!array_key_exists('referrer', $comment)) { //set the referer if not set
                $comment['referrer'] = $_SERVER['HTTP_REFERER'];
            }
        }

        $request = http_build_query($comment);
        $requestLength = strlen($request);

        $headers = array(
            sprintf('POST %s%s HTTP/1.0', $this->apiUrl, $path),
            sprintf('Host: %s', $this->apiHost),
            'Content-Type: application/x-www-form-urlencoded',
            sprintf('Content-Length: %s', $requestLength),
            sprintf('User-Agent: %s', $this->userAgent),
            '', //we need an empty line in here
            $request
        );


        $headersWrite = implode("\r\n", $headers);

        $conn = fsockopen($this->apiHost, 80, $errno, $errstr, 10);

        if ($conn === false) {
            $this->error = sprintf('Socket error %s: %s', $errno, $errstr);
            return false;
        } else {
            $response = '';
            fwrite($conn, $headersWrite);

            while (!feof($conn)) {
                $response .= fgets($conn, 1160);
            }

            fclose($conn);
        }

        if (strlen($response) > 0) {
            $response = explode("\r\n\r\n", $response, 2);
            if (trim(end($response)) == $expect) {
                return true;
            } else {
                $debug = explode("\n", $response[0]);
                foreach ($debug as $header) {
                    if (stripos($header, 'X-akismet-debug-help') === 0) {
                        $this->error = trim($header);
                    }
                }
                return false;
            }

        } else {
            $this->error = 'Unknown error';
            return false;
        }
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
