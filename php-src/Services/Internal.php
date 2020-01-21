<?php

namespace EmailApi\Services;

use EmailApi\Interfaces;
use EmailApi\Basics;

/**
 * Class Internal
 * Make and send each mail
 */
class Internal implements Interfaces\Sending
{
    public function canUseService(): bool
    {
        return true;
    }

    public function systemServiceId(): int
    {
        return 1;
    }

    /**
     * Send mail directly via php - no hurdles anywhere, no security too
     *
     * @param Interfaces\Content $content
     * @param Interfaces\EmailUser $to
     * @param Interfaces\EmailUser $from
     * @param Interfaces\EmailUser $replyTo
     * @param bool $toDisabled
     * @return Basics\Result
     */
    public function sendEmail(Interfaces\Content $content, Interfaces\EmailUser $to, ?Interfaces\EmailUser $from = null, ?Interfaces\EmailUser $replyTo = null, $toDisabled = false): Basics\Result
    {
        if (!empty($content->getAttachments())) {
            return new Basics\Result(false, 'No attachments available for simple mailing');
        }
        // @codeCoverageIgnoreStart
        $result = mail($to->getEmail(), $content->getSubject(), $content->getHtmlBody());
        return new Basics\Result((bool)$result, $result);
        // @codeCoverageIgnoreEnd
    }
}
