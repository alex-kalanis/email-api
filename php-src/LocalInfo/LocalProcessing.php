<?php

namespace kalanis\EmailApi\LocalInfo;


use kalanis\EmailApi\Interfaces;


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
