<?php

namespace Gildsmith\CoreApi\Exceptions;

use Exception;

class DefaultLanguageDetachException extends Exception
{
    protected $message = 'Detaching the default language from the channel is not allowed.';
}