<?php

use EmailApi\Basics;
use EmailApi\Interfaces;
use EmailApi\Exceptions;

class DummyService implements Interfaces\Sending
{
    protected $canUseService = true;
    protected $getPassedResult = true;
    protected $getFailedResult = true;

    public function __construct(bool $canUseService = true, bool $getPassedResult = true, bool $getFailedResult = true)
    {
        $this->canUseService = $canUseService;
        $this->getPassedResult = $getPassedResult;
        $this->getFailedResult = $getFailedResult;
    }

    public function canUseService(): bool
    {
        return (bool)$this->canUseService;
    }

    public function systemServiceId(): int
    {
        return static::SERVICE_TESTING;
    }

    public function sendEmail(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null, ?Interfaces\EmailUser $replyTo = null, $toDisabled = false): Basics\Result
    {
        if ($this->getPassedResult) {
            return new Basics\Result(
                !empty($content->getHtmlBody())
                && !empty($to->getEmail()),
                'Dummy service with check'
            );
        }
        if ($this->getFailedResult) {
            return new Basics\Result(false, 'died');
        }
        throw new Exceptions\EmailException('die on send');

    }
}
