<?php

namespace PennyBlack\Exception;

class ApiException extends PennyBlackException
{
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct(sprintf($code . ': Penny Black API service error: %s', $message), $code);
    }
}
