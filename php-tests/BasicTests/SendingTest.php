<?php

use kalanis\EmailApi\Basics;
use kalanis\EmailApi\Exceptions;
use kalanis\EmailApi\Interfaces;
use kalanis\EmailApi\LocalInfo;
use kalanis\EmailApi\Sending;


class SendingStopBeforeProcess extends LocalInfo\DefaultInfo
{
    public function beforeProcess(Interfaces\IContent $content, Interfaces\IEmailUser $to, ?Interfaces\IEmailUser $from = null): void
    {
        parent::beforeProcess($content, $to, $from);
        throw new Exceptions\EmailException('Catch on before process');
    }
}


class SendingStopBeforeSend extends LocalInfo\DefaultInfo
{
    public function beforeSend(Interfaces\ISending $service, Interfaces\IContent $content): void
    {
        parent::beforeSend($service, $content);
        throw new Exceptions\EmailException('Catch on before send');
    }
}


class SendingStopResultSuccess extends LocalInfo\DefaultInfo
{
    public function whenResultIsSuccessful(Interfaces\ISending $service, Basics\Result $result): void
    {
        parent::whenResultIsSuccessful($service, $result);
        throw new Exceptions\EmailException('Catch on success send');
    }

    public function whenSendFails(Interfaces\ISending $service, Exceptions\EmailException $ex): void
    {
        parent::whenSendFails($service, $ex);
        throw $ex; // pass it through catch
    }
}


class SendingTest extends CommonTestClass
{
    public function testCheck()
    {
        $lib = new Sending(new LocalInfo\DefaultInfo(), $this->mockServices(false));
        $this->assertFalse($lib->canUseService(), 'There is no service by default');
        $this->assertEquals(0, $lib->systemServiceId());
        $lib = new Sending(new LocalInfo\DefaultInfo(), $this->mockServices());
        $this->assertTrue($lib->canUseService(), 'There is services');
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testSimple()
    {
        $lib = new Sending(new LocalInfo\DefaultInfo(), $this->mockServices());
        $data = $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->assertTrue($data->getStatus());
        $this->assertEquals('Dummy service with check', $data->getData());
        $this->assertNull($data->getRemoteId());
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testProcessBefore()
    {
        $lib = new Sending(new SendingStopBeforeProcess(), $this->mockServices());
        $this->expectException(Exceptions\EmailException::class);
        $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->expectExceptionMessageMatches('Catch on before process');
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testBeforeSend()
    {
        $lib = new Sending(new SendingStopBeforeSend(), $this->mockServices());
        $this->expectException(Exceptions\EmailException::class);
        $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->expectExceptionMessageMatches('Catch on before send');
    }

    /**
     * @throws Exceptions\EmailException
     */
    public function testProcessSuccess()
    {
        $lib = new Sending(new SendingStopResultSuccess(), $this->mockServices());
        $this->expectException(Exceptions\EmailException::class);
        $lib->sendEmail($this->mockContent(), $this->mockUser());
        $this->expectExceptionMessageMatches('Catch on success send');
    }

    protected function mockServices(bool $withDummyService = true): LocalInfo\ServicesOrdering
    {
        $ordering = new LocalInfo\ServicesOrdering();
        if ($withDummyService) {
            $ordering->addService(new DummyService());
        }
        return $ordering;
    }
}
