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
    public string $email = '';
    public string $name = '';

    public function setData(string $email, string $name = ''): self
    {
        $this->name = $name;
        $this->email = $email;
        return $this;
    }

    public function sanitize(): self
    {
        $this->name = strval($this->name);
        $this->email = strval($this->email);
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
