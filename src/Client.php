<?php
namespace Trackops\Api;

use Trackops\Api\GuzzleRequest;

class Client
{
    protected $subdomain;

    protected $username;

    protected $token;

    public function __construct($subdomain, $username, $token)
    {
        $this->subdomain = $subdomain;
        $this->username = $username;
        $this->token = $token;
    }

    public function createRequest()
    {
        return new GuzzleRequest($this->subdomain, $this->username, $this->token);
    }
}
