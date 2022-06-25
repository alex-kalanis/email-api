<?php

namespace kalanis\EmailApi\Basics;


use kalanis\EmailApi\Interfaces;


/**
 * Class Content
 * @package kalanis\EmaiApi\Basics
 * Smallest possible email content
 */
class Content implements Interfaces\IContent
{
    /** @var string */
    public $subject = '';

    /** @var string */
    public $body = '';

    /** @var string */
    public $tag = '';

    /** @var string|null */
    public $plain = null;

    /** @var string|null */
    public $unsubEmail = null;

    /** @var string|null */
    public $unsubLink = null;

    /** @var bool */
    public $unsubByClick = false;

    /** @var Interfaces\IContentAttachment[] */
    protected $attachments = [];

    public function setData(string $subject = '', string $body = '', string $tag = ''): self
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->tag = $tag;
        return $this;
    }

    public function sanitize(): self
    {
        $this->subject = (string) $this->subject;
        $this->body = (string) $this->body;
        $this->tag = (string) $this->tag;
        $this->plain = is_null($this->plain) ? null : (string) $this->plain;
        $this->unsubEmail = is_null($this->unsubEmail) ? null : (string) $this->unsubEmail ;
        $this->unsubLink = is_null($this->unsubLink) ? null : (string) $this->unsubLink ;
        $this->unsubByClick = (bool) $this->unsubByClick;
        return $this;
    }

    public function getSubject(): string
    {
        return (string) $this->subject;
    }

    public function getHtmlBody(): string
    {
        return (string) $this->body;
    }

    public function getPlainBody(): ?string
    {
        return $this->plain;
    }

    public function getTag(): string
    {
        return (string) $this->tag;
    }

    public function getUnsubscribeEmail(): ?string
    {
        return $this->unsubEmail;
    }

    public function getUnsubscribeLink(): ?string
    {
        return $this->unsubLink;
    }

    public function canUnsubscribeOneClick(): bool
    {
        return (bool) $this->unsubByClick;
    }

    public function addAttachment(Interfaces\IContentAttachment $attachment): self
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * @return array<Interfaces\IContentAttachment>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function resetAttachments(): self
    {
        $this->attachments = [];
        return $this;
    }
}
