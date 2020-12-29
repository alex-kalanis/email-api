<?php

use kalanis\EmailApi\Basics\Attachment;


class AttachmentTest extends CommonTestClass
{
    public function testSimple()
    {
        $data = $this->mockAttachment();
        $this->assertEquals('testing_file', $data->name);
        $this->assertEquals('', $data->path);
        $this->assertEquals('text/plain', $data->mime);
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm', $data->content);
        $this->assertEquals('utf8', $data->encoding);
        $this->assertEquals(Attachment::TYPE_INLINE, $data->type);
    }

    public function testOutputs()
    {
        $data = $this->mockAttachment();
        $this->assertEquals('testing_file', $data->getFileName());
        $this->assertEquals('', $data->getLocalPath());
        $this->assertEquals('text/plain', $data->getFileMime());
        $this->assertEquals('qwertzuiopasdfghjklyxcvbnm', $data->getFileContent());
        $this->assertEquals('utf8', $data->getEncoding());
        $this->assertEquals(Attachment::TYPE_INLINE, $data->getType());
    }

    public function testSanitize()
    {
        $data = $this->mockAttachment();
        $data->type = '3';
        $data->path = null;
        $data->sanitize();
        $this->assertEquals('', $data->path);
        $this->assertEquals(Attachment::TYPE_IMAGE, $data->type);
    }
}
