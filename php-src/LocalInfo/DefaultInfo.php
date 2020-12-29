<?php

namespace kalanis\EmailApi\LocalInfo;


use kalanis\EmailApi\Basics;
use kalanis\EmailApi\Exceptions;
use kalanis\EmailApi\Interfaces;


/**
 * Class DefaultInfo
 * Default information with specifics of local machine
 * By using class implementing same interface you can log everything during sending the content
 */
class DefaultInfo implements Interfaces\ILocalInfo
{
    public function beforeProcess(Interfaces\IContent $content, Interfaces\IEmailUser $to, ?Interfaces\IEmailUser $from = null): void
    {
    }

    public function beforeSend(Interfaces\ISending $service, Interfaces\IContent $content): void
    {
    }

    public function whenSendFails(Interfaces\ISending $service, Exceptions\EmailException $ex): void
    {
    }

    public function whenResultIsSuccessful(Interfaces\ISending $service, Basics\Result $result): void
    {
    }

    public function whenResultIsNotSuccessful(Interfaces\ISending $service, Basics\Result $result): void
    {
    }

    public function whenNoDefinitionIsUsable(): void
    {
    }

    public function getLangSendingFailed(): string
    {
        return 'Sending failed.';
    }
}
