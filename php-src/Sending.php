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

    /** @var Interfaces\Sending[] */
    protected $order = [];
    /** @var bool */
    protected $returnOnUnsuccessful = false;

    public function __construct()
    {
        // there is an area for definition of used services
        // set them into the array with a bit sane keys - you will debug that
    }

    public function canUseService(): bool
    {
        return !empty($this->order);
    }

    public function mayReturnFirstUnsuccessful(bool $set = false)
    {
        $this->returnOnUnsuccessful = $set;
        return $this;
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
        $this->beforeProcess($content, $to, $from);

        if ($this->canUseService()) {
            foreach ($this->order as $index => $lib) {
                if (!$this->isAllowed($lib)) {
                    continue;
                }
                $result = null;
                $this->beforeSend($lib, $content);
                try {
                    $result = $lib->sendEmail($content, $to, $from, $replyTo, $toDisabled);
                    if ($result->getStatus()) {
                        $this->whenResultIsSuccessful($lib, $result);
                        return $result;
                    } else {
                        $this->whenResultIsNotSuccessful($lib, $result);
                        unset($this->order[$index]); // throw it out, if we send more mail, then it won't be bothered anymore on this run
                        if ($this->returnOnUnsuccessful) {
                            return $result;
                        }
                    }
                } catch (Exceptions\EmailException $ex) {
                    $this->whenSendFails($lib, $ex);
                }
            }
        }
        $this->whenNoDefinitionIsUsable();
        return new Basics\Result(false, $this->getLangSendingFailed());
    }

    /**
     * @param mixed $lib
     * @return bool
     */
    protected function isAllowed($lib): bool
    {
        return (($lib instanceof Interfaces\Sending) && $lib->canUseService());
    }

    /**
     * For log whole action of sending a mail
     * @param Interfaces\Content $content
     * @param Interfaces\EmailUser $to
     * @param Interfaces\EmailUser|null $from
     * @throws Exceptions\EmailException
     */
    protected function beforeProcess(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null): void
    {
    }

    /**
     * For log which service did it
     * @param Interfaces\Sending $service
     * @param Interfaces\Content $content
     * @throws Exceptions\EmailException
     */
    protected function beforeSend(Interfaces\Sending $service, Interfaces\Content $content): void
    {
    }

    /**
     * We have got an exception from sending service - something weird happend
     * Position to log it
     * @param Interfaces\Sending $service
     * @param Exceptions\EmailException $ex
     * @throws Exceptions\EmailException
     * @see static::CALL_EXCEPTION
     */
    protected function whenSendFails(Interfaces\Sending $service, Exceptions\EmailException $ex): void
    {
    }

    /**
     * Log it when there is successful result from service
     * @param Interfaces\Sending $service
     * @param Basics\Result $result
     * @throws Exceptions\EmailException
     */
    protected function whenResultIsSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
    }

    /**
     * When sending returns fail for any reason
     * @param Interfaces\Sending $service
     * @param Basics\Result $result
     * @throws Exceptions\EmailException
     * @see static::CALL_RUN_DIED
     */
    protected function whenResultIsNotSuccessful(Interfaces\Sending $service, Basics\Result $result): void
    {
    }

    /**
     * When there is nothing to do because there is no available definition
     * Log somewhere that we have unknown sending services
     * @throws Exceptions\EmailException
     * @see static::CALL_UNKNOWN
     */
    protected function whenNoDefinitionIsUsable(): void
    {
    }

    /**
     * Translation for totally dead result
     * @return string
     */
    protected function getLangSendingFailed(): string
    {
        return 'Sending failed.';
    }
}
