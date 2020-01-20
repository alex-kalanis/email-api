<?php

class ContentTest extends CommonTestClass
{
    public function testSimple()
    {
        $data = $this->mockContent();
        $this->assertEquals('testing content', $data->subject);
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm', $data->body);
        $this->assertEquals('on_testing_service', $data->tag);
    }

    public function testOutputs()
    {
        $data = $this->mockContent();
        $this->assertEquals('testing content', $data->getSubject());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm', $data->getHtmlBody());
        $this->assertEquals('on_testing_service', $data->getTag());
    }

    public function testSanitize()
    {
        $data = $this->mockContent();
        $data->subject = 123456789;
        $data->tag = null;
        $data->sanitize();
        $this->assertEquals('', $data->tag);
        $this->assertEquals('123456789', $data->subject);
    }
}