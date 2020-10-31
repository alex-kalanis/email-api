<?php

namespace EmailApi\Interfaces;

/**
 * Interface IEmailUser
 * @package EmailApi\Interfaces
 *
 * Interface for targeting mail
 */
interface IEmailUser
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
