<?php

namespace Brus;

class ValidationEngine
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
        $setFieldStatus = true;

            foreach ($fieldsValuesPairs as $field => $value) {
                if (!$this->setField($field, $value)) {
                    $setFieldStatus = false;
                    break;
                }
            }

        return $setFieldStatus;
    }

    public function setField($fieldName, $valueOfField)
    {
        $validFieldNameStatus = true;
        $validSetFieldStatus = false;

            foreach ($this->fields as $fields) {
                if ($fields == $fieldName) {
                    if ($this->validate($fieldName, $valueOfField)) {
                        $validSetFieldStatus = true;
                    }
                    $validFieldNameStatus = false;
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
        $validFieldStatus = true;

        foreach ($this->constraintsForFields as $field => $constraints) {
            if ($fieldName == $field) {
                foreach ($constraints as $constraint) {
                    $validFieldStatus = $constraint->isValidated($valueOfField);
                    if (!$validFieldStatus) {
                        $this->errorMessage = $constraint->getMessage();
                        $validFieldStatus = false;
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
