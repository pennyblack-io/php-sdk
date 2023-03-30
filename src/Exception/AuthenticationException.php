<?php

namespace PennyBlack\Exception;

class AuthenticationException extends PennyBlackException
{
    public function __construct($statusCode)
    {
        parent::__construct(
            $statusCode . ': Authorization failed. Please check your API key is entered correctly',
            $statusCode
        );
    }
}
