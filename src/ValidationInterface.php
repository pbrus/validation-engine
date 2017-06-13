<?php

namespace Brus;

interface ValidationInterface
{
    public function __construct(array $parameters);
    public function isValidated($value);    // return true or false
    public function getMessage();
}
