<?php

namespace EmailApi\Interfaces;

use EmailApi\Exceptions;
use EmailApi\Basics\Result;

/**
 * Class LocalInfo
 * @package EmailApi\Interfaces
 * Interface for setting info about local environment
 * Implementing class process local-system-dependent calls
 */
interface LocalInfo
{
    /**
     * For log whole action of sending a mail
     * @param Content $content
     * @param EmailUser $to
     * @param EmailUser|null $from
     * @throws Exceptions\EmailException
     */
    public function beforeProcess(Content $content, EmailUser $to, ?EmailUser $from = null): void;

    /**
     * For log which service did it
     * @param Sending $service
     * @param Content $content
     * @throws Exceptions\EmailException
     */
    public function beforeSend(Sending $service, Content $content): void;

    /**
     * We have got an exception from sending service - something weird happend
     * Position to log it
     * @param Sending $service
     * @param Exceptions\EmailException $ex
     * @throws Exceptions\EmailException
     * @see \EmailApi\Sending::CALL_EXCEPTION
     */
    public function whenSendFails(Sending $service, Exceptions\EmailException $ex): void;

    /**
     * Log it when there is successful result from service
     * @param Sending $service
     * @param Result $result
     * @throws Exceptions\EmailException
     */
    public function whenResultIsSuccessful(Sending $service, Result $result): void;

    /**
     * When sending returns fail for any reason
     * @param Sending $service
     * @param Result $result
     * @throws Exceptions\EmailException
     * @see \EmailApi\Sending::CALL_RUN_DIED
     */
    public function whenResultIsNotSuccessful(Sending $service, Result $result): void;

    /**
     * When there is nothing to do because there is no available definition
     * Log somewhere that we have unknown sending services
     * @throws Exceptions\EmailException
     * @see \EmailApi\Sending::CALL_UNKNOWN
     */
    public function whenNoDefinitionIsUsable(): void;

    /**
     * Translation for totally dead result
     * @return string
     */
    public function getLangSendingFailed(): string;
}