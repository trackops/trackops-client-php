<?php
namespace Trackops\Api;

interface RequestInterface
{
    public function get($path, array $params = []);
}