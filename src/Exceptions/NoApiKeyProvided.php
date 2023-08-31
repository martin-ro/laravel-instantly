<?php

namespace MartinRo\Instantly\Exceptions;

use Exception;

class NoApiKeyProvided extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'No API key provided. Please add INSTANTLY_API_KEY to your .env. To get a key visit: https://app.instantly.ai/app/settings/integrations'
        );
    }
}
