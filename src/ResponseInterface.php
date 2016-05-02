<?php

namespace Trackops\Api;

interface ResponseInterface
{
    public function getResponse();

    public function getBody();

    public function toArray();
}