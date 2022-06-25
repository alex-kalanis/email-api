<?php

namespace kalanis\EmailApi\LocalInfo;


use Iterator;
use kalanis\EmailApi\Interfaces;


/**
 * Class ServicesOrdering
 * @package kalanis\EmailApi\LocalInfo
 * Default services available on local installation
 * Contains typical PHP object-to-array boilerplate
 * You can extend this class to specify order of available services
 * @implements Iterator<int|null, Interfaces\ISending>
 */
class ServicesOrdering implements Iterator
{
    /** @var array<int, Interfaces\ISending> */
    protected $services = [];
    /** @var bool */
    protected $returnOnUnsuccessful = false;

    public function addService(Interfaces\ISending $service): self
    {
        $this->services[$service->systemServiceId()] = $service;
        return $this;
    }

    public function removeService(Interfaces\ISending $service): self
    {
        unset($this->services[$service->systemServiceId()]);
        return $this;
    }

    public function mayReturnFirstUnsuccessful(bool $set = false): self
    {
        $this->returnOnUnsuccessful = $set;
        return $this;
    }

    public function isReturningAfterFirstUnsuccessful(): bool
    {
        return $this->returnOnUnsuccessful;
    }

    public function canUseService(): bool
    {
        return (!empty($this->services));
    }

    public function current()
    {
        return (false !== ($service = current($this->services))) ? $service : null;
    }

    public function next(): void
    {
        next($this->services);
    }

    public function key()
    {
        return key($this->services);
    }

    public function valid(): bool
    {
        $key = $this->key();
        return !is_null($key);
    }

    public function rewind(): void
    {
        reset($this->services);
    }
}
