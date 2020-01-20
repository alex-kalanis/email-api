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
    public function testSimple()
    {
        try {
            $lib = new HaltedBase();
            $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
            $this->assertFalse($data->getStatus());
            $this->assertEquals('Sending failed.', $data->getData());
        } catch (Exceptions\EmailException $ex) {
            $this->assertFalse(true,'cannot be here');
        }
    }

    public function testWithExcept()
    {
        try {
            $lib = new HaltedNothingLeft();
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('No service left', $ex->getMessage());
        }
    }

    public function testDeadSend()
    {
        try {
            $lib = new HaltedSendFail();
            $lib->mayReturnFirstUnsuccessful(true);
            $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
            $lib->order[Sending::SERVICE_TESTING]->getResult = true;
            $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
            $this->assertFalse($data->getStatus());
            $this->assertEquals('died', $data->getData());
        } catch (Exceptions\EmailException $ex) {
            $this->assertFalse(true,'cannot be here');
        }
    }

    public function testDeadSendResult()
    {
        try {
            $lib = new HaltedResultFail();
            $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
            $lib->order[Sending::SERVICE_TESTING]->getResult = true;
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('Catch on failed result', $ex->getMessage());
        }
    }

    public function testDeadSendExcept()
    {
        try {
            $lib = new HaltedResultFail();
            $lib->order[Sending::SERVICE_TESTING]->canUseService = true;
            $lib->order[Sending::SERVICE_TESTING]->getResult = false;
            $lib->sendEmail($this->mockContent(), $this->mockUser());
            throw new Exceptions\EmailException('cannot be here');
        } catch (Exceptions\EmailException $ex) {
            $this->assertEquals('die on send', $ex->getMessage());
        }
    }
}