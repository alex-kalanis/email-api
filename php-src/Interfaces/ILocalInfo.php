<?php

namespace EmailApi\Interfaces;

use EmailApi\Exceptions;
use EmailApi\Basics\Result;

/**
 * Class ILocalInfo
 * @package EmailApi\Interfaces
 * Interface for setting info about local environment
 * Implementing class process local-system-dependent calls
 */
interface ILocalInfo
{
    /**
     * For log whole action of sending a mail
     * @param IContent $content
     * @param IEmailUser $to
     * @param IEmailUser|null $from
     * @throws Exceptions\EmailException
     */
    public function beforeProcess(IContent $content, IEmailUser $to, ?IEmailUser $from = null): void;

    /**
     * For log which service did it
     * @param ISending $service
     * @param IContent $content
     * @throws Exceptions\EmailException
     */
    public function beforeSend(ISending $service, IContent $content): void;

    /**
     * We have got an exception from sending service - something weird happend
     * Position to log it
     * @param ISending $service
     * @param Exceptions\EmailException $ex
     * @throws Exceptions\EmailException
     * @see \EmailApi\Sending::CALL_EXCEPTION
     */
    public function whenSendFails(ISending $service, Exceptions\EmailException $ex): void;

    /**
     * Log it when there is successful result from service
     * @param ISending $service
     * @param Result $result
     * @throws Exceptions\EmailException
     */
    public function whenResultIsSuccessful(ISending $service, Result $result): void;

    /**
     * When sending returns fail for any reason
     * @param ISending $service
     * @param Result $result
     * @throws Exceptions\EmailException
     * @see \EmailApi\Sending::CALL_RUN_DIED
     */
    public function whenResultIsNotSuccessful(ISending $service, Result $result): void;

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
