<?php

use kalanis\EmailApi\Exceptions;
use kalanis\EmailApi\LocalInfo;
use kalanis\EmailApi\Services;


class RestTest extends CommonTestClass
{
    public function testExcept()
    {
        $ex = new Exceptions\EmailException('something');
        $this->assertEquals('something', $ex->getMessage());
    }

    public function testInternal()
    {
        $lib = new Services\Internal();
        $this->assertTrue($lib->canUseService());
        $this->assertEquals(1, $lib->systemServiceId());
        // more is not possible - here is direct system call for email
    }

    public function testInternalDies()
    {
        $data = $this->mockContent();
        $data->addAttachment($this->mockAttachment());
        $lib = new Services\Internal();
        $result = $lib->sendEmail($data, $this->mockUser());
        $this->assertFalse($result->getStatus());
    }

    public function testLocalProcessing()
    {
        $lib = new LocalInfo\LocalProcessing(); // necessary in subservices
        $lib->enableMailLocally($this->mockUser());
        $this->assertTrue(true); // because coverage sniffing
    }
}
