<?php

namespace kalanis\EmailApi\Basics;


use kalanis\EmailApi\Interfaces;


/**
 * Class User
 * @package kalanis\EmailApi\Interfaces
 * Simple implementation of user which sends the emails
 */
class User implements Interfaces\IEmailUser
{
    /** @var string */
    public $email = '';
    /** @var string */
    public $name = '';

    public function setData($email, $name = '')
    {
        $this->name = $name;
        $this->email = $email;
        return $this;
    }

    public function sanitize()
    {
        $this->name = (string)$this->name;
        $this->email = (string)$this->email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailName(): string
    {
        return $this->name;
    }
}
