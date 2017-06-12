<?php

use Brus\ValidationEngine;
use Brus\NotEmptyValidator;
use Brus\LengthValidator;
require_once __DIR__.'/vendor/autoload.php';

$validator = new ValidationEngine();

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
