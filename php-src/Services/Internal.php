<?php

namespace EmailApi\Services;

use EmailApi\Interfaces;
use EmailApi\Basics;

/**
 * Class Internal
 * Make and send each mail
 */
class Internal implements Interfaces\ISending
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
     * @param Interfaces\IContent $content
     * @param Interfaces\IEmailUser $to
     * @param Interfaces\IEmailUser $from
     * @param Interfaces\IEmailUser $replyTo
     * @param bool $toDisabled
     * @return Basics\Result
     */
    public function sendEmail(Interfaces\IContent $content, Interfaces\IEmailUser $to, ?Interfaces\IEmailUser $from = null, ?Interfaces\IEmailUser $replyTo = null, $toDisabled = false): Basics\Result
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
