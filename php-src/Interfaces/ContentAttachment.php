<?php

namespace EmailApi\Interfaces;

/**
 * Class ContentAttachment
 * @package EmailApi\Interfaces
 * Interface for adding attachments into mail
 * Concrete implementation in on implementing class.
 */
interface ContentAttachment
{
    const TYPE_INLINE = 1;
    const TYPE_FILE = 2;
    const TYPE_IMAGE = 3;

    /**
     * Name of attached file
     * Can be empty string
     * @return string
     */
    public function getFileName(): string;

    /**
     * Attachment content
     * Can be empty when passed as file from local drive
     * @return string
     */
    public function getFileContent(): string;

    /**
     * Path to file on local system
     * Can be empty when sent as inline record
     * @return string
     */
    public function getLocalPath(): string;

    /**
     * File Mime Type
     * Can be empty, then it's on sending library
     * @return string
     */
    public function getFileMime(): string;

    /**
     * Mailer encodes results in...
     * usually base64
     * @return string
     */
    public function getEncoding(): string;

    /**
     * Content type - depends on constants above
     * @return int
     */
    public function getType(): int;
}