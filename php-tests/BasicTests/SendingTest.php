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
    public function testCheck()
    {
        $lib = new Sending();
        $this->assertFalse($lib->canUseService(), 'There is no service by default');
        $this->assertEquals(0, $lib->systemServiceId());
        $lib = new SendingBase();
        $this->assertTrue($lib->canUseService(), 'There is services');
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testSimple()
    {
        $lib = new SendingBase();
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertTrue($data->getStatus());
        $this->assertEquals('Dummy service with check', $data->getData());
        $this->assertNull($data->getRemoteId());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on before process
     */
    public function testProcessBefore()
    {
        $lib = new SendingStopBeforeProcess();
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on before send
     */
    public function testBeforeSend()
    {
        $lib = new SendingStopBeforeSend();
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on success send
     */
    public function testProcessSuccess()
    {
        $lib = new SendingStopResultSuccess();
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }
}