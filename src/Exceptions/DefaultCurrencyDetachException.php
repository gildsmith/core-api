<?php

namespace Gildsmith\CoreApi\Exceptions;

use Exception;

class DefaultCurrencyDetachException extends Exception
{
    protected $message = 'Detaching the default currency from the channel is not allowed.';
}