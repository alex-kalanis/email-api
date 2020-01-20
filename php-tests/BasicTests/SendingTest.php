<?php

use EmailApi\Basics;
use EmailApi\Exceptions;
use EmailApi\Interfaces;
use EmailApi\Sending;

class DummyService implements Interfaces\Sending
{
    public function canUseService(): bool
    {
        return true;
    }

    public function systemServiceId(): int
    {
        return static::SERVICE_TESTING;
    }

    public function sendEmail(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null, ?Interfaces\EmailUser $replyTo = null, $toDisabled = false): Basics\Result
    {
        return new Basics\Result(
            !empty($content->getHtmlBody())
            && !empty($to->getEmail()),
            'Dummy service with check'
        );
    }
}

class SendingBase extends Sending
{
    public function __construct()
    {
        parent::__construct();
        $this->order[static::SERVICE_TESTING] = new DummyService();
    }
}

class SendingStopBeforeProcess extends SendingBase
{
    public function beforeProcess(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null): void
    {
        throw new Exceptions\EmailException('Catch on before process');
    }
}

class SendingStopBeforeSend extends SendingBase
{
    public function beforeSend(Interfaces\Sending $service, Interfaces\Content $content): void
    {
        throw new Exceptions\EmailException('Catch on before send');
    }
}

class SendingStopResultSuccess extends SendingBase
{
    public function whenResultIsSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
        throw new Exceptions\EmailException('Catch on success send');
    }

    protected function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        throw $ex; // pass it through catch
    }
}

class SendingTest extends CommonTestClass
{
    public function testSimple()
    {
        try {
            $lib = new SendingBase();
            $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
            $this->assertTrue($data->getStatus());
            $this->assertEquals('Dummy service with check', $data->getData());
            $this->assertNull($data->getRemoteId());
        } catch (Exceptions\EmailException $ex) {
            $this->assertFalse(true,'cannot be here');
        }
    }

    public function testProcessBefore()
    {
        try {
            $lib = new SendingStopBeforeProcess();
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('Catch on before process', $ex->getMessage());
        }
    }

    public function testBeforeSend()
    {
        try {
            $lib = new SendingStopBeforeSend();
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('Catch on before send', $ex->getMessage());
        }
    }

    public function testProcessSuccess()
    {
        try {
            $lib = new SendingStopResultSuccess();
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('Catch on success send', $ex->getMessage());
        }
    }
}