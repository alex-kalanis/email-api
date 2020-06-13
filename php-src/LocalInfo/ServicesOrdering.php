<?php

namespace EmailApi\LocalInfo;

use EmailApi\Interfaces;

/**
 * Class ServicesOrdering
 * Default services available on local installation
 * Contains typical PHP object-to-array boilerplate
 * You can extend this class to specify order of available services
 */
class ServicesOrdering implements \Iterator
{
    /** @var Interfaces\Sending[] */
    protected $services = [];
    /** @var bool */
    protected $returnOnUnsuccessful = false;

    public function addService(Interfaces\Sending $service): self
    {
        $this->services[$service->systemServiceId()] = $service;
        return $this;
    }

    public function removeService(Interfaces\Sending $service): self
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
        return current($this->services);
    }

    public function next()
    {
        return next($this->services);
    }

    public function key()
    {
        return key($this->services);
    }

    public function valid()
    {
        $key = $this->key();
        return (!is_null($key) && false !== $key);
    }

    public function rewind()
    {
        reset($this->services);
    }
}
