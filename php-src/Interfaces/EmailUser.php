<?php

namespace EmailApi\Interfaces;

/**
 * Interface EmailUser
 * @package EmailApi\Interfaces
 *
 * Interface for targeting mail
 */
interface EmailUser
{
    /**
     * Returns email of user
     * @return string
     */
    public function getEmail(): string;

    /**
     * Returns name of user
     * @return string
     */
    public function getEmailName(): string;
}