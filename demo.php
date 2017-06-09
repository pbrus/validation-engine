<?php

// ---------- Mandatory components for validation classes ----------

interface ValidationInterface
{
    public function __construct(array $parameters);
    public function isValidated($value);   // return TRUE or FALSE
    public function getMessage();
}

abstract class ValidationAbstractClass implements ValidationInterface
{
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }
}

// ----------------------- Engine -----------------------

class ValidatonEngine
{
    private $fields;
    private $constraintsForFields = array();
    private $errorMessage;

    public function setLabels($fieldsToSet)
    {
        $this->fields = $fieldsToSet;
    }

    public function setConstraints($fieldName, $constraintsForField)
    {
        $this->isValidationAbstractClassInherited($constraintsForField);
        $this->constraintsForFields[$fieldName] = $constraintsForField;
    }

    private function isValidationAbstractClassInherited($constraintClasses)
    {
        foreach ($constraintClasses as $className) {
            if ($className instanceof ValidationAbstractClass) {
                continue;
            } else {
                throw new ValidationAbstractClassNotInheritedException(get_class($className) . " must inherit ValidationAbstractClass");
            }
        }
    }

    public function setFields($fieldsValuesPairs)
    {
        $setFieldStatus = TRUE;

            foreach ($fieldsValuesPairs as $field => $value) {
                if (!$this->setField($field, $value)) {
                    $setFieldStatus = FALSE;
                    break;
                }
            }

        return $setFieldStatus;
    }

    public function setField($fieldName, $valueOfField)
    {
        $validFieldNameStatus = TRUE;
        $validSetFieldStatus = FALSE;

            foreach ($this->fields as $fields) {
                if ($fields == $fieldName) {
                    if ($this->validate($fieldName, $valueOfField)) {
                        $validSetFieldStatus = TRUE;
                    }
                    $validFieldNameStatus = FALSE;
                    break;
                }
            }

            if ($validFieldNameStatus) {
                throw new NoFieldInValidatorException("Field " . $fieldName . " wasn't define");
            }

        return $validSetFieldStatus;
    }

    private function validate($fieldName, $valueOfField)
    {
        $validFieldStatus = TRUE;

        foreach ($this->constraintsForFields as $field => $constraints) {
            if ($fieldName == $field) {
                foreach ($constraints as $constraint) {
                    $validFieldStatus = $constraint->isValidated($valueOfField);
                    if (!$validFieldStatus) {
                        $this->errorMessage = $constraint->getMessage();
                        $validFieldStatus = FALSE;
                        break 2;
                    }
                }
            }
        }

        return $validFieldStatus;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}

// ----------------------- Validators -----------------------

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

// ----------------------- Exceptions -----------------------

class IncorrectKeywordInValidatorException extends \Exception
{
}

class ValidationAbstractClassNotInheritedException extends \Exception
{
}

class NoFieldInValidatorException extends \Exception
{
}

// ----------------------- Controller -----------------------

$validator = new ValidatonEngine();

$validator->setLabels(array(
    'username',
    'id'
));

try {
    $validator->setConstraints('username', array(
        new NotEmptyValidator(array(
            'notEmptyMessage' => 'Field username must be filled out'
        )),
        new LengthValidator(array(
            'minLength' => 3,
            'maxLength' => 25,
            'lengthMessage' => "Field username must contain 3-25 letters"
        ))
    ));

    $validator->setConstraints('id', array(
        new NotEmptyValidator(array(
            'notEmptyMessage' => "You must type your ID"
        )),
        new LengthValidator(array(
            'minLength' => 10,
            'maxLength' => 10,
            'lengthMessage' => "Field ID must consist of 10 integers"
        ))
    ));
} catch (IncorrectKeywordInValidatorException $err) {
    echo $err->getMessage();
} catch (ValidationAbstractClassNotInheritedException $err) {
    echo $err->getMessage();
}

try {
    if (!$validator->setFields(array(
        'username' => 'John',   // not validated: 'Jo'
        'id' => '9876543210'    // not validated: '123'
    ))
    ) {
        echo $validator->getErrorMessage();
    }
} catch (NoFieldInValidatorException $err) {
    echo $err->getMessage();
}
