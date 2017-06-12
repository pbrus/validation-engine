<?php

namespace Brus;

class LengthValidator extends ValidationAbstractClass
{
    private $minimumOfLength;
    private $maximumOfLength;

    public function __construct(array $inputParameters)
    {
        foreach ($inputParameters as $key => $value) {
            if ($key == "minLength") {
                $this->minimumOfLength = $value;
            } else if ($key == "maxLength") {
                $this->maximumOfLength = $value;
            } else if ($key == "lengthMessage") {
                $this->message = $value;
            } else {
                throw new IncorrectKeywordInValidatorException($key . " is not a keyword in the " . get_class($this) . " class");
            }
        }
    }

    public function isValidated($value)
    {
        $len = strlen($value);
        return ($len >= $this->minimumOfLength) && ($len <= $this->maximumOfLength) ? TRUE : FALSE;
    }
}
