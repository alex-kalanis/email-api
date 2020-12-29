<?php

namespace kalanis\EmailApi\Interfaces;


/**
 * Class ILocalProcessing
 * What to do with mail when it's something need locally
 */
interface ILocalProcessing
{
    /**
     * Remove blocks made on local machine by callbacks
     * @param IEmailUser $to Who will be enabled locally
     */
    public function enableMailLocally(IEmailUser $to): void;
}
