<?php

namespace EmailApi\Basics;

use EmailApi\Interfaces\ContentAttachment;

/**
 * Class Attachment
 * @package EmaiApi\Basics
 */
class Attachment implements ContentAttachment
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $path = '';
    /** @var string */
    public $content = '';
    /** @var string */
    public $mime = '';
    /** @var string */
    public $encoding = '';
    /** @var int */
    public $type = self::TYPE_INLINE;

    public function setData(string $name, string $path = '', string $content = '', string $mime = '', string $encoding = '', int $type = self::TYPE_INLINE)
    {
        $this->name = $name;
        $this->path = $path;
        $this->mime = $mime;
        $this->content = $content;
        $this->encoding = $encoding;
        $this->type = $type;
        return $this;
    }

    public function sanitize()
    {
        $this->name = (string)$this->name;
        $this->path = (string)$this->path;
        $this->mime = (string)$this->mime;
        $this->content = (string)$this->content;
        $this->encoding = (string)$this->encoding;
        $this->type = (int)$this->type;
        return $this;
    }

    public function getFileName(): string
    {
        return (string)$this->name;
    }

    public function getFileContent(): string
    {
        return (string)$this->content;
    }

    public function getLocalPath(): string
    {
        return (string)$this->path;
    }

    public function getFileMime(): string
    {
        return (string)$this->mime;
    }

    public function getEncoding(): string
    {
        return (string)$this->encoding;
    }

    public function getType(): int
    {
        return (int)$this->type;
    }
}