<?php

namespace kalanis\EmailApi\Interfaces;


/**
 * Class IContent
 * @package kalanis\EmailApi\Interfaces
 * Interface which describes email content which will be sent.
 * Concrete implementation in on implementing class.
 */
interface IContent
{
    /**
     * Mail subject
     * @return string
     */
    public function getSubject(): string;

    /**
     * Mail content - usual HTML body
     * @return string
     */
    public function getHtmlBody(): string;

    /**
     * Mail content - plaintext
     * @return string|null
     */
    public function getPlainBody(): ?string;

    /**
     * Mailu for sorting on external services
     * @return string
     */
    public function getTag(): string;

    /**
     * Email for unsubscribe
     * @return null|string
     */
    public function getUnsubscribeEmail(): ?string;

    /**
     * Link for unsubscribe
     * @return null|string
     */
    public function getUnsubscribeLink(): ?string;

    /**
     * Can usubscribe with one click?
     * @return bool
     */
    public function canUnsubscribeOneClick(): bool;

    /**
     * Attachments
     * @return IContentAttachment[]
     */
    public function getAttachments(): array;
}
