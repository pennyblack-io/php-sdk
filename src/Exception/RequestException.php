<?php

namespace PennyBlack\Exception;

use Throwable;

class RequestException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Transmission error: %s', $message), $code, $previous);
    }
}
