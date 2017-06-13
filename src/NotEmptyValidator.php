<?php

namespace Brus;

class NotEmptyValidator extends ValidationAbstractClass
{
    private $notEmptyMessageKey = "notEmptyMessage";

    public function __construct(array $inputParameters)
    {
        foreach ($inputParameters as $key => $value) {
            if (strcmp($key, $this->notEmptyMessageKey) == 0) {
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
