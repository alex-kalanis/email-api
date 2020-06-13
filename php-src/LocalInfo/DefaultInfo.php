<?php

namespace EmailApi\LocalInfo;

use EmailApi\Basics;
use EmailApi\Exceptions;
use EmailApi\Interfaces;

/**
 * Class DefaultInfo
 * Default information with specifics of local machine
 * By using class implementing same interface you can log everything during sending the content
 */
class DefaultInfo implements Interfaces\LocalInfo
{
    public function beforeProcess(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null): void
    {
    }

    public function beforeSend(Interfaces\Sending $service, Interfaces\Content $content): void
    {
    }

    public function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
    }

    public function whenResultIsSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
    }

    public function whenResultIsNotSuccessful(Interfaces\Sending $service, Basics\Result $result): void
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
