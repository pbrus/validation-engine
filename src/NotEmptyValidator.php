<?php

namespace Brus;

class NotEmptyValidator extends ValidationAbstractClass
{
    public function __construct(array $inputParameters)
    {
        foreach ($inputParameters as $key => $value) {
            if ($key == "notEmptyMessage") {
                $this->message = $value;
            } else {
                throw new IncorrectKeywordInValidatorException($key . " is not a keyword in the " . get_class($this) . " class");
            }
        }
    }

    public function isValidated($value)
    {
        return !empty($value);
    }
}
