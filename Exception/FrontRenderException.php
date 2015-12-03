<?php

namespace Chris\Bundle\FrontRenderBundle\Exception;

class FrontRenderException extends \RuntimeException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
