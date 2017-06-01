<?php

namespace allejo\Wufoo\Exceptions;

use Throwable;

class SubmissionException extends \RuntimeException implements \JsonSerializable
{
    private $jsonResponse;

    public function __construct(array $jsonResponse, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->jsonResponse = $jsonResponse;

        if (isset($jsonResponse['ErrorText']))
        {
            $this->message = $jsonResponse['ErrorText'];
        }
    }

    public function getFieldErrors()
    {
        if (isset($this->jsonResponse['FieldErrors']))
        {
            return $this->jsonResponse['FieldErrors'];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->jsonResponse;
    }
}
