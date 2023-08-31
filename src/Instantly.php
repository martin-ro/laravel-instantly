<?php

namespace MartinRo\Instantly;

class Instantly
{
    /**
     * Return Instantly api client
     */
    public function client(): InstantlyClient
    {
        return new InstantlyClient();
    }
}
