<?php

use kalanis\EmailApi\Basics\Content;


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

    public function testClear()
    {
        $data = new Content();
        $data->setData('testing content 2', 'qwertzuiopasdfghjklyxcvbnm123', 'on_testing_service456');
        $data->plain = 'qwertzuiopasdfghjklyxcvbnm987';
        $data->unsubEmail = 'qwertzuiopasdfghjklyxcvbnm654';
        $data->unsubLink = 'qwertzuiopasdfghjklyxcvbnm321';
        $data->unsubByClick = 0;
        $data->sanitize();
        $this->assertEquals('testing content 2', $data->getSubject());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm123', $data->getHtmlBody());
        $this->assertEquals('on_testing_service456', $data->getTag());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm987', $data->getPlainBody());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm654', $data->getUnsubscribeEmail());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm321', $data->getUnsubscribeLink());
        $this->assertFalse($data->canUnsubscribeOneClick());
    }

    public function testAttachments()
    {
        $data = $this->mockContent();
        $this->assertEmpty($data->getAttachments());
        $data->addAttachment($this->mockAttachment());
        $this->assertNotEmpty($data->getAttachments());
        $data->resetAttachments();
        $this->assertEmpty($data->getAttachments());
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
