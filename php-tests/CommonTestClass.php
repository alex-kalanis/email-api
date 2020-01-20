<?php

use EmailApi\Basics;
use PHPUnit\Framework\TestCase;

class CommonTestClass extends TestCase
{
//    public function providerBasic()
//    {
//    }

    protected function mockContent(): Basics\Content
    {
        return (new Basics\Content())->setData(
            'testing content',
            'qwertzuiopasdfghjklyxcvbnm',
            'on_testing_service'
        );
    }

    protected function mockAttachment(): Basics\Attachment
    {
        return (new Basics\Attachment())->setData(
            'testing_file',
            '',
            'qwertzuiopasdfghjklyxcvbnm',
            'text/plain',
            'utf8'
        );
    }

    protected function mockUser(): Basics\User
    {
        return (new Basics\User())->setData(
            'bob@test.example',
            'Bob'
        );
    }

    protected function mockResult(bool $status): Basics\Result
    {
        return new Basics\Result($status, 'Testing response');
    }
}