<?php

namespace EmailApi\LocalInfo;

use EmailApi\Interfaces;

/**
 * Class LocalProcessing
 * Default implementation
 */
class LocalProcessing implements Interfaces\LocalProcessing
{
    public function enableMailLocally(Interfaces\EmailUser $to): void
    {
    }
}
