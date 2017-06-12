<?php

namespace Brus;

interface ValidationInterface
{
    public function __construct(array $parameters);
    public function isValidated($value);    // return TRUE or FALSE
    public function getMessage();
}
