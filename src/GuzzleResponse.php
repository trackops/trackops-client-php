<?php
namespace Trackops\Api;

use Trackops\Api\ResponseInterface;

class GuzzleResponse implements ResponseInterface
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Returns a Guzzle Response
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the raw body of the response.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->getResponse()->getBody();
    }

    /**
     * Returns an array of values based on the response body.
     *
     * @return string
     */
    public function toArray()
    {
        return json_decode($this->getBody(), true);
    }
}