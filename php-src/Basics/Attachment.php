<?php

namespace kalanis\EmailApi\Basics;


use kalanis\EmailApi\Interfaces\IContentAttachment;


/**
 * Class Attachment
 * @package kalanis\EmaiApi\Basics
 */
class Attachment implements IContentAttachment
{
    public string $name = '';
    public string $path = '';
    public string $content = '';
    public string $mime = '';
    public string $encoding = '';
    public int $type = self::TYPE_INLINE;

    public function setData(string $name, string $path = '', string $content = '', string $mime = '', string $encoding = '', int $type = self::TYPE_INLINE): self
    {
        $this->name = $name;
        $this->path = $path;
        $this->mime = $mime;
        $this->content = $content;
        $this->encoding = $encoding;
        $this->type = $type;
        return $this;
    }

    public function sanitize(): self
    {
        $this->name = (string) $this->name;
        $this->path = (string) $this->path;
        $this->mime = (string) $this->mime;
        $this->content = (string) $this->content;
        $this->encoding = (string) $this->encoding;
        $this->type = (int) $this->type;
        return $this;
    }

    public function getFileName(): string
    {
        return strval($this->name);
    }

    public function getFileContent(): string
    {
        return strval($this->content);
    }

    public function getLocalPath(): string
    {
        return strval($this->path);
    }

    public function getFileMime(): string
    {
        return strval($this->mime);
    }

    public function getEncoding(): string
    {
        return strval($this->encoding);
    }

    public function getType(): int
    {
        return intval($this->type);
    }
}
