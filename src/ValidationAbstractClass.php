<?php

namespace Brus;

abstract class ValidationAbstractClass implements ValidationInterface
{
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }
}
