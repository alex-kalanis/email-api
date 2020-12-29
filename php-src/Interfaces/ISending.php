<?php

namespace kalanis\EmailApi\Interfaces;


use kalanis\EmailApi\Exceptions\EmailException;
use kalanis\EmailApi\Basics\Result;


/**
 * Class ISending
 * @package EmailApi\Interfaces
 * Main interface for sending an email
 * Implementing class process the sending itself
 * Calling class has a choice which service will be used
 */
interface ISending
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
     * @param IContent $content Message with attachments
     * @param IEmailUser $to Target user
     * @param IEmailUser|null $from Who sends the message
     * @param IEmailUser|null $replyTo reply to this user - for larger services
     * @param bool $toDisabled When user bounced mail then it's necessary to pass info for skip check
     * @return Result
     * @throws EmailException
     */
    public function sendEmail(IContent $content, IEmailUser $to, ?IEmailUser $from = null, ?IEmailUser $replyTo = null, $toDisabled = false): Result;

}
