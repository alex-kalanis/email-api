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
    public string $subject = '';
    public string $body = '';
    public string $tag = '';
    public ?string $plain = null;
    public ?string $unsubEmail = null;
    public ?string $unsubLink = null;
    public bool $unsubByClick = false;
    /** @var Interfaces\IContentAttachment[] */
    protected array $attachments = [];

    public function setData(string $subject = '', string $body = '', string $tag = ''): self
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->tag = $tag;
        return $this;
    }

    public function sanitize(): self
    {
        $this->subject = strval($this->subject);
        $this->body = strval($this->body);
        $this->tag = strval($this->tag);
        $this->plain = is_null($this->plain) ? null : strval($this->plain);
        $this->unsubEmail = is_null($this->unsubEmail) ? null : strval($this->unsubEmail);
        $this->unsubLink = is_null($this->unsubLink) ? null : strval($this->unsubLink);
        $this->unsubByClick = boolval($this->unsubByClick);
        return $this;
    }

    public function getSubject(): string
    {
        return strval($this->subject);
    }

    public function getHtmlBody(): string
    {
        return strval($this->body);
    }

    public function getPlainBody(): ?string
    {
        return $this->plain;
    }

    public function getTag(): string
    {
        return strval($this->tag);
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
        return boolval($this->unsubByClick);
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
