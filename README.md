# Validation-engine [![GitHub release](http://www.astro.uni.wroc.pl/ludzie/brus/img/github/ver20170613.svg "download")](https://github.com/pbrus/validation-engine/) ![Written in PHP](http://www.astro.uni.wroc.pl/ludzie/brus/img/github/php.svg "language")

This is a simple validation engine written in PHP OOP for fun. If you need a robust PHP validator I recommend [this one](https://github.com/Respect/Validation).

## Installation

Install the package using *Composer*:
```bash
$ composer require pbrus/validation-engine=dev-master
```
Then go to the `validation-engine/` directory and make `dump-autoload`:
```bash
$ cd vendor/pbrus/validation-engine/
$ composer dump-autoload
```
If don't know what *Composer* is, see the [simplified PHP installation](https://github.com/pbrus/astro-quiz#installation).

## Usage

Define a validator and set its labels (here `username` and `id`):
```php
$validator = new ValidationEngine();

$validator->setLabels(array(
    'username',
    'id'
));
```
In the next step set constraints for each label:
```php
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
```
Then we can validate data:
```php
if (!$validator->setFields(array(
    'username' => 'John',   // not validated: 'Jo'
    'id' => '9876543210'    // not validated: '123'
))
) {
    echo $validator->getErrorMessage();
}
```

If you encounter any problems, please see the demo file `index.php`.

## Classes

The following examples of classes are defined:

  * NotEmptyValidator
  * LengthValidator

Feel free to add own classes.

## License

**Validation-engine** is licensed under the [MIT license](http://opensource.org/licenses/MIT).
