<?php

use EmailApi\Basics;
use EmailApi\Exceptions;
use EmailApi\Interfaces;
use EmailApi\Sending;

class HaltedService implements Interfaces\Sending
{
    public $canUseService = false;
    public $getResult = true;

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
        if (!$this->getResult) {
            throw new Exceptions\EmailException('die on send');
        }
        return new Basics\Result(false, 'died');
    }
}

class HaltedBase extends Sending
{
    public $order = [];

    public function __construct()
    {
        parent::__construct();
        $this->order[static::SERVICE_TESTING] = new HaltedService();
    }
}

class HaltedNothingLeft extends HaltedBase
{
    public function whenNoDefinitionIsUsable(): void
    {
        throw new Exceptions\EmailException('No service left');
    }
}

class HaltedSendFail extends HaltedBase
{
    public function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        throw new Exceptions\EmailException('Catch on failed service', null, $ex);
    }
}

class HaltedResultFail extends HaltedBase
{
    protected function whenResultIsNotSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
        throw new Exceptions\EmailException('Catch on failed result');
    }

    protected function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        throw $ex; // pass it through catch
    }
}

class SendingFailTest extends CommonTestClass
{
    /**
     * @throws Exceptions\EmailException
     */
    public function testSimple()
    {
        $lib = new HaltedBase();
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('Sending failed.', $data->getData());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage No service left
     */
    public function testWithExcept()
    {
        $lib = new HaltedNothingLeft();
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testWithExceptToFinal()
    {
        $lib = new HaltedBase();
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('Sending failed.', $data->getData());
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testDeadSend()
    {
        $lib = new HaltedSendFail();
        $lib->mayReturnFirstUnsuccessful(true);
        $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
        $lib->order[Sending::SERVICE_TESTING]->getResult = true;
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('died', $data->getData());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on failed result
     */
    public function testDeadSendResult()
    {
        $lib = new HaltedResultFail();
        $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
        $lib->order[Sending::SERVICE_TESTING]->getResult = true;
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage die on send
     */
    public function testDeadSendExcept()
    {
        $lib = new HaltedResultFail();
        $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
        $lib->order[Sending::SERVICE_TESTING]->getResult = false;
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }
}