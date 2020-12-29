<?php

namespace kalanis\EmailApi\Basics;


/**
 * Class Result
 * @package kalanis\EmailApi\Basics
 * Result from sending service
 */
class Result
{
    /** @var bool */
    public $status;
    /** @var string */
    public $data = '';
    /** @var string|null */
    public $remoteId = null;

    /**
     * @param bool $status
     * @param string|null $data
     * @param string|null $remoteId
     */
    public function __construct($status = false, $data = null, $remoteId = null)
    {
        $this->status = $status;
        $this->data = $data;
        $this->remoteId = $remoteId;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getRemoteId(): ?string
    {
        return $this->remoteId;
    }
}
