<?php

namespace EmailApi\LocalInfo;

use EmailApi\Interfaces;

/**
 * Class LocalProcessing
 * Default implementation
 */
class LocalProcessing implements Interfaces\ILocalProcessing
{
    public function enableMailLocally(Interfaces\IEmailUser $to): void
    {
    }
}
