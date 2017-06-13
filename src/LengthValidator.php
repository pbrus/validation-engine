<?php

namespace Brus;

class LengthValidator extends ValidationAbstractClass
{
    private $minLengthKey = "minLength";
    private $maxLengthKey = "maxLength";
    private $lengthMessageKey = "lengthMessage";
    private $minimumOfLength;
    private $maximumOfLength;

    public function __construct(array $inputParameters)
    {
        foreach ($inputParameters as $key => $value) {
            if (strcmp($key, $this->minLengthKey) == 0) {
                $this->minimumOfLength = $value;
            } else if (strcmp($key, $this->maxLengthKey) == 0) {
                $this->maximumOfLength = $value;
            } else if (strcmp($key, $this->lengthMessageKey) == 0) {
                $this->message = $value;
            } else {
                throw new IncorrectKeywordInValidatorException($key . " is not a keyword in the " . get_class($this) . " class");
            }
        }
    }

    public function isValidated($value)
    {
        $len = strlen($value);
        return ($len >= $this->minimumOfLength) && ($len <= $this->maximumOfLength) ? true : false;
    }
}
