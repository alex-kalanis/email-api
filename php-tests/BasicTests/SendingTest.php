<?php

use EmailApi\Basics;
use EmailApi\Exceptions;
use EmailApi\Interfaces;
use EmailApi\LocalInfo;
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
    public function __construct(Interfaces\LocalInfo $info)
    {
        parent::__construct($info);
        $this->order[static::SERVICE_TESTING] = new DummyService();
    }
}

class SendingStopBeforeProcess extends LocalInfo\DefaultInfo
{
    public function beforeProcess(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null): void
    {
        parent::beforeProcess($content, $to, $from);
        throw new Exceptions\EmailException('Catch on before process');
    }
}

class SendingStopBeforeSend extends LocalInfo\DefaultInfo
{
    public function beforeSend(Interfaces\Sending $service, Interfaces\Content $content): void
    {
        parent::beforeSend($service, $content);
        throw new Exceptions\EmailException('Catch on before send');
    }
}

class SendingStopResultSuccess extends LocalInfo\DefaultInfo
{
    public function whenResultIsSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
        parent::whenResultIsSuccessful($service, $result);
        throw new Exceptions\EmailException('Catch on success send');
    }

    public function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        parent::whenSendFails($service, $ex);
        throw $ex; // pass it through catch
    }
}

class SendingTest extends CommonTestClass
{
    public function testCheck()
    {
        $lib = new Sending(new LocalInfo\DefaultInfo());
        $this->assertFalse($lib->canUseService(), 'There is no service by default');
        $this->assertEquals(0, $lib->systemServiceId());
        $lib = new SendingBase(new LocalInfo\DefaultInfo());
        $this->assertTrue($lib->canUseService(), 'There is services');
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testSimple()
    {
        $lib = new SendingBase(new LocalInfo\DefaultInfo());
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
        $lib = new SendingBase(new SendingStopBeforeProcess());
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on before send
     */
    public function testBeforeSend()
    {
        $lib = new SendingBase(new SendingStopBeforeSend());
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on success send
     */
    public function testProcessSuccess()
    {
        $lib = new SendingBase(new SendingStopResultSuccess());
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }
}