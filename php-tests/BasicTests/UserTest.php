<?php

class UserTest extends CommonTestClass
{
    public function testSimple()
    {
        $data = $this->mockUser();
        $this->assertEquals('bob@test.example', $data->email);
        $this->assertEquals('Bob', $data->name);
    }

    public function testOutputs()
    {
        $data = $this->mockUser();
        $this->assertEquals('bob@test.example', $data->getEmail());
        $this->assertEquals('Bob', $data->getEmailName());
    }

    public function testSanitize()
    {
        $data = $this->mockUser();
        $data->name = null;
        $data->sanitize();
        $this->assertEquals('', $data->name);
    }
}