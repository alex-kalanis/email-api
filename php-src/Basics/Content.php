<?php

namespace EmailApi\Basics;

use EmailApi\Interfaces as I;

/**
 * Class Content
 * @package EmaiApi\Basics
 * Smallest possible email content
 */
class Content implements I\IContent
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

    /** @var I\IContentAttachment[] */
    protected $attachments = [];

    public function setData(string $subject = '', string $body = '', string $tag = '')
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->tag = $tag;
        return $this;
    }

    public function sanitize()
    {
        $this->subject = (string)$this->subject;
        $this->body = (string)$this->body;
        $this->tag = (string)$this->tag;
        $this->plain = is_null($this->plain) ? null : (string)$this->plain;
        $this->unsubEmail = is_null($this->unsubEmail) ? null : (string)$this->unsubEmail ;
        $this->unsubLink = is_null($this->unsubLink) ? null : (string)$this->unsubLink ;
        $this->unsubByClick = (bool)$this->unsubByClick;
        return $this;
    }

    public function getSubject(): string
    {
        return (string)$this->subject;
    }

    public function getHtmlBody(): string
    {
        return (string)$this->body;
    }

    public function getPlainBody(): ?string
    {
        return $this->plain;
    }

    public function getTag(): string
    {
        return (string)$this->tag;
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
        return (bool)$this->unsubByClick;
    }

    public function addAttachment(I\IContentAttachment $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function resetAttachments()
    {
        $this->attachments = [];
        return $this;
    }
}
