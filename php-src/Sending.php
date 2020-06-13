<?php

namespace EmailApi;

/**
 * Class Sending
 * Send mails by correct service
 * Select the only one - first successful one
 */
class Sending implements Interfaces\Sending
{
    const CALL_UNKNOWN = 590;
    const CALL_RUN_DIED = 591;
    const CALL_EXCEPTION = 592;

    /** @var LocalInfo\ServicesOrdering */
    protected $servicesIterator = null;
    /** @var Interfaces\LocalInfo */
    protected $info = null;

    public function __construct(Interfaces\LocalInfo $info, LocalInfo\ServicesOrdering $ordering)
    {
        $this->info = $info;
        $this->servicesIterator = $ordering;
    }

    public function canUseService(): bool
    {
        return $this->servicesIterator->canUseService();
    }

    public function systemServiceId(): int
    {
        return static::SERVICE_SYSTEM;
    }

    /**
     * @param Interfaces\Content $content
     * @param Interfaces\EmailUser $to
     * @param Interfaces\EmailUser|null $from
     * @param Interfaces\EmailUser|null $replyTo
     * @param bool $toDisabled
     * @return Basics\Result
     * @throws Exceptions\EmailException
     */
    public function sendEmail(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null, ?Interfaces\EmailUser $replyTo = null, $toDisabled = false): Basics\Result
    {
        $this->info->beforeProcess($content, $to, $from);

        if ($this->canUseService()) {
            foreach ($this->servicesIterator as $index => $lib) {
                if (!$this->isAllowed($lib)) {
                    continue;
                }
                $result = null;
                $this->info->beforeSend($lib, $content);
                try {
                    $result = $lib->sendEmail($content, $to, $from, $replyTo, $toDisabled);
                    if ($result->getStatus()) {
                        $this->info->whenResultIsSuccessful($lib, $result);
                        return $result;
                    } else {
                        $this->info->whenResultIsNotSuccessful($lib, $result);
                        $this->servicesIterator->removeService($lib); // throw it out, if we send more mail, then it won't be bothered anymore on this run
                        if ($this->servicesIterator->isReturningAfterFirstUnsuccessful()) {
                            return $result;
                        }
                    }
                } catch (Exceptions\EmailException $ex) {
                    $this->info->whenSendFails($lib, $ex);
                }
            }
        }
        $this->info->whenNoDefinitionIsUsable();
        return new Basics\Result(false, $this->info->getLangSendingFailed());
    }

    /**
     * @param mixed $lib
     * @return bool
     */
    protected function isAllowed($lib): bool
    {
        return (($lib instanceof Interfaces\Sending) && $lib->canUseService());
    }
}
