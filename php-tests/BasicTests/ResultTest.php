<?php

use EmailApi\Basics\Result;


class ResultTest extends CommonTestClass
{
    public function testSimple()
    {
        $data = $this->mockResult(true);
        $this->assertTrue($data->status);
        $this->assertEquals('Testing response', $data->data);
    }

    public function testClear()
    {
        $data = new Result(true, 'none', 12);
        $this->assertTrue($data->status);
        $this->assertEquals('none', $data->data);
        $this->assertEquals(12, $data->remoteId);
        $this->assertEquals(12, $data->getRemoteId());
    }

    public function testOutputs()
    {
        $data = $this->mockResult(false);
        $this->assertFalse($data->getStatus());
        $this->assertEquals('Testing response', $data->getData());
    }
}
