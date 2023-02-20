<?php

namespace PennyBlack\Exception;

class AuthenticationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Authorization failed. Please check your API key is entered correctly', 403);
    }
}
