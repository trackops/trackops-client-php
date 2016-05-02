<?php

namespace Trackops\Api;

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Client as GuzzleClient;
use Trackops\Api\RequestInterface;
use Trackops\Api\Exception\RequestException;

class GuzzleRequest implements RequestInterface
{
    /**
     * The URL used to run API calls.  The %subdomain% variable will be
     * replaced with the actual subdomain passed at runtime.
     *
     * @var string
     */
    protected $url = 'http://%subdomain%.viewcases.com/api/v1';

    /**
     * The API component that is to be called (e.g. cases, expense/entries)
     *
     * @var string
     */
    protected $path;

    /**
     * The full access username that will be used to make the API call.
     *
     * @var string
     */
    protected $username;

    /**
     * The API token that will be used to make the API call. For security,
     * it is recommended to pass this value in from an environment variable.
     *
     * @var string
     */
    protected $token;

    public function __construct($subdomain, $username, $token)
    {
        $this->url = str_replace('%subdomain%', $subdomain, $this->url);
        $this->username = $username.'/token';
        $this->token = $token;
    }

    /**
     * Shortcut for execute() with a GET method
     *
     * @param string $path
     * @param array $params
     * @return Trackops\Api\GuzzleResponse
     */
    public function get($path, array $params = [])
    {
        return $this->execute('GET', $path, $params);
    }

    /**
     * Returns a record count and page count given the supplied parameters
     *
     * @param string $path
     * @param array $params
     * @return Trackops\Api\GuzzleResponse
     */
    public function count($path, array $params = [])
    {
        return $this->get($path, array_merge($params, ['mode' => 'count']));
    }

    /**
     * Executes an API request given the parameters
     *
     * @param string $method
     * @param string $path
     * @param array $params
     * @return \Trackops\Api\GuzzleResponse
     * @throws \Trackops\Exception\RequestException
     */
    protected function execute($method, $path, array $params = [])
    {
        try {
            $client = new GuzzleClient(['base_uri' => $this->url.'/']);
            $response = $client->request($method, $this->sanitizePath($path), [
                'headers' => ['Accept' => 'application/json'],
                'auth'    => [$this->username, $this->token],
                'query'   => $params,
            ]);
        } catch (TransferException $e) {
            throw new RequestException($e->getMessage());
        }
        return new GuzzleResponse($response);
    }

    /**
     * Make sure the path begins with a slash (/)
     *
     * @param string $path
     * @return string
     */
    private function sanitizePath($path)
    {
        if (0 === strpos('/', $path)) {
            return substr($path, 1);
        }

        return $path;
    }
}