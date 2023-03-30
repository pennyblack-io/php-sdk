<?php

namespace PennyBlack\Exception;

class ServerErrorException extends PennyBlackException
{
    public function __construct(string $message)
    {
        parent::__construct('Penny Black API service gave a 500 error: ' . $message);
    }
}
