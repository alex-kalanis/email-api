<?php

namespace EmailApi\Interfaces;

use EmailApi\Exceptions\EmailException;
use EmailApi\Basics\Result;

/**
 * Class Sending
 * @package EmailApi\Interfaces
 * Main interface for sending an email
 * Implementing class process the sending itself
 * Calling class has a choice which service will be used
 */
interface Sending
{
    const SERVICE_SYSTEM = 0;
    const SERVICE_TESTING = 1;

    /**
     * Can use for sending this E-mail?
     * Is there correct all dependencies?
     * @return bool
     */
    public function canUseService(): bool;

    /**
     * Which ID is for this service?
     * @return int
     *
     * 0 -> system calls
     * 1 -> for testing
     * 2 -> mail() inside the PHP
     */
    public function systemServiceId(): int;

    /**
     * @param Content $content Message with attachments
     * @param EmailUser $to Target user
     * @param EmailUser|null $from Who sends the message
     * @param EmailUser|null $replyTo reply to this user - for larger services
     * @param bool $toDisabled When user bounced mail then it's necessary to pass info for skip check
     * @return Result
     * @throws EmailException
     */
    public function sendEmail(Content $content, EmailUser $to, ?EmailUser $from = null, ?EmailUser $replyTo = null, $toDisabled = false): Result;

}