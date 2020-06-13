<?php

use EmailApi\Basics;
use EmailApi\Exceptions;
use EmailApi\Interfaces;
use EmailApi\LocalInfo;
use EmailApi\Sending;

class HaltedNothingLeft extends LocalInfo\DefaultInfo
{
    public function whenNoDefinitionIsUsable(): void
    {
        parent::whenNoDefinitionIsUsable();
        throw new Exceptions\EmailException('No service left');
    }
}

class HaltedSendFail extends LocalInfo\DefaultInfo
{
    public function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        parent::whenSendFails($service, $ex);
        throw new Exceptions\EmailException('Catch on failed service', null, $ex);
    }
}

class HaltedResultFail extends LocalInfo\DefaultInfo
{
    public function whenResultIsNotSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
        parent::whenResultIsNotSuccessful($service, $result);
        throw new Exceptions\EmailException('Catch on failed result');
    }

    public function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
        parent::whenSendFails($service, $ex);
        throw $ex; // pass it through catch
    }
}

class SendingFailTest extends CommonTestClass
{
    /**
     * @throws Exceptions\EmailException
     */
    public function testNoServiceSet()
    {
        $lib = new Sending(new LocalInfo\DefaultInfo(), $this->mockServices(false));
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('Sending failed.', $data->getData());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage No service left
     */
    public function testNoServiceExcept()
    {
        $lib = new Sending(new HaltedNothingLeft(), $this->mockServices(false));
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testNoServiceLeft()
    {
        $lib = new Sending(new LocalInfo\DefaultInfo(), $this->mockServices(true, true, false));
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('Sending failed.', $data->getData());
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testSendingDied()
    {
        $lib = new Sending(new HaltedSendFail(), $this->mockServices()->mayReturnFirstUnsuccessful(true));
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertFalse($data->getStatus());
        $this->assertEquals('died', $data->getData());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage Catch on failed result
     */
    public function testSendingDiedResult()
    {
        $lib = new Sending(new HaltedResultFail(), $this->mockServices());
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    /**
     * @expectedException \EmailApi\Exceptions\EmailException
     * @expectedExceptionMessage die on send
     */
    public function testSendingDiedExcept()
    {
        $lib = new Sending(new HaltedResultFail(), $this->mockServices(true, false));
        $lib->sendEmail($this->mockContent(), $this->mockUser());
    }

    protected function mockServices(bool $withDummyService = true, bool $getResult = true, bool $canUseService = true): LocalInfo\ServicesOrdering
    {
        $ordering = new LocalInfo\ServicesOrdering();
        if ($withDummyService) {
            $service = new DummyService($canUseService, false, $getResult);
            $ordering->addService($service);
        }
        return $ordering;
    }
}