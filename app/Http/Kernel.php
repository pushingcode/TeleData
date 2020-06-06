<?php

declare(strict_types = 1);

namespace App\Http;

use Symfony\Component\HttpFoundation\Request;

class Kernel
{
    private $request;

    public function __construct()
    {
        $this->request = new Request(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }
}