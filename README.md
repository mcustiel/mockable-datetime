Mockable DateTime
================

What is it
----------

Mockable DateTime is a library written in PHP that allows developers to mock the dates for unit tests.
There are sometimes in which you need to verify that an action was executed with certain parameters, and one of them is a date or a time (generally obtained with date() or time() built-in functions) and is very difficult to ensure it will have a certain value at the time of the verification. 
Mockable DateTime solves this problem by giving the developer a way to obtain PHP's built-in DateTime class in a way, that the value it returns can be mocked from unit tests without the need of injecting DateTime as a dependency.


[![Build Status](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mcustiel/mockable-datetime/?branch=master)

Installation
------------

#### Composer:

```json  
{
    "require": {
        "mcustiel/mockable-datetime": "^2.0"
    }
}
```

How to use it?
--------------

### In your code

Everytime you need to get system's date just use Mockable DateTime:

```php

use Mcustiel\Mockable\DateTime;

// ...
function savePersonInfoInDatabase(Person $person)
{
    /** @var PersonDbEntity $person */
    $person = $this->converter->convert($person, PersonDbEntity::class);
    $person->setCreatedAt(DateTime::newPhpDateTime()); // DateTime::newImmutablePhpDateTime() can also be used
    $this->dbClient->insert($person);
}
// ...
```

Also arguments can be passed to create the DateTime object:

```php

use Mcustiel\Mockable\DateTime;

// ...
function savePersonInfoInDatabase(Person $person)
{
    /** @var PersonDbEntity $person */
    $person = $this->converter->convert($person, PersonDbEntity::class);
    $person->setCreatedAt(DateTime::newPhpDateTime(
        '-4 months', 
        new \DateTimeZone('America/New_York')
    )); // DateTime::newImmutablePhpDateTime() can also be used
    $this->dbClient->insert($person);
}
// ...
```

As you can see in the example, I'm not using PHP's \DateTime directly. Instead I use Mockable DateTime to create instances of PHP's \DateTime.

Then you have to test this, and assert that insert method was called with some specific date as an argument. For the example I'll use PHPUnit.

```php
use Mcustiel\Mockable\DateTime;

// ...

/**
 * @test
 */
function shouldCallInsertPersonWithCorrectData()
{
    DateTime::setFixed(new \DateTime('2000-01-01 00:00:01'));
    // Now every call to MockableDateTime::newPhpDateTime() will always return "2000-01-01 00:00:01"
    /** @var Person $person */
    $person = new Person('John', 'Doe');
    /** @var PersonDbEntity $expected */
    $expected = new PersonDbEntity('John', 'Doe');
    $expected->setCreatedAt(new \DateTime('2000-01-01 00:00:01'));    
    
    $this->dbClientMock->expects($this->once())
        ->method('insert')
        ->with($this->equalTo($expected));
    // ...and other needed mocks
    $this->unitUnderTest->savePersonInfoInDatabase($person);
}
// ...
```

That's it. For it to work the code and tests should be executed in the same environment (it won't work if you execute tests again a running instance of your webservice), but it should be enough for unit and some low level functional tests.

### DateTime methods:

##### void setFixed(\DateTime $dateTime)

This method makes all instances of Mockable DateTime to always return the date and time specified by the $dateTime parameter.

##### void setOffset(\DateTime $dateTime)

This method makes all instances of Mockable DateTime to always return a date and time with offset equal to the specified by the $dateTime parameter. The time starts to run from the moment of the call to this method. So if you set an offset equal to '2005-05-05 01:00:00' and sleep for 5 seconds, a new call to create a \DateTime will return one with '2005-05-05 01:00:05' set.

##### void setSystem()

This method makes all instances of Mockable DateTime to always return current system time (default behaviour).

##### \DateTime newPhpDateTime($time = 'now', \DateTimeZone $timeZone = null)

Creates a new instance of PHP's \DateTime class based in the config of Mockable DateTime.

##### \DateTimeImmutable newImmutablePhpDateTime($time = 'now', \DateTimeZone $timeZone = null)

Creates a new instance of PHP's \DateTimeImmutable class based in the config of Mockable DateTime.
