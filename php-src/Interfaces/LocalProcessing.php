<?php

namespace EmailApi\Interfaces;

/**
 * Class LocalProcessing
 * What to do with mail when it's something need locally
 */
interface LocalProcessing
{
    /**
     * Remove blocks made on local machine by callbacks
     * @param EmailUser $to Who will be enabled locally
     */
    public function enableMailLocally(EmailUser $to): void;
}
