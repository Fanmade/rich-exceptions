[![Pipeline](https://github.com/Fanmade/rich-exceptions/actions/workflows/php.yml/badge.svg)](https://github.com/Fanmade/rich-exceptions/actions)
[![Test Coverage](https://gist.githubusercontent.com/Fanmade/b0eb72a8454c6346f36d99df7cd643d0/raw/Rich-Exceptions-Coverage.svg)](https://packagist.com)

# Rich Exceptions
## Work in progress. Not ready for production use!

----

### Increase your developer-experience by adding context and some magic to your exceptions

The default PHP exception is very basic.  
It only contains a message and a code.   
This package aims to make exceptions more useful by allowing to add context.

### (Planned) Features
- [x] Add context to exceptions
- [x] Add a stacktrace to exceptions
- [x] Allow basic configuration
    - Context
      - [ ] format
      - [ ] depth
      - [ ] verbosity
      - [ ] keys to hide/ignore
      - [ ] default context
      - [ ] custom serializers
    - Stacktrace
      - [ ] format
      - [ ] depth
      - [ ] verbosity
    - Logs
      - [ ] format
      - [ ] depth
      - [ ] verbosity
      - [ ] logger class
    - Exceptions
        - [ ] decorators
- [ ] Custom error handler
- [ ] HTTP error extensions ("NotFound" errors that should result automatically in 404 responses)
- [ ] Switch to https://github.com/captainhookphp/captainhook for git hooks


----

## Installation
```bash
composer require fanmade/rich-exceptions
```

## Usage

```php
<?php
    
use Fanmade\RichExceptions\RichException;

class YourException extends RichException
{
    // ...
}
try {
    throw YourException::createWithContext(
        message: 'Something went wrong',
        code: 420,
        context: [ 'foo' => 'bar', 'bar' => 'baz']
    );
}
catch (YourException $exception) {
    Monlog::error($exception->getMessage(), $exception->getContextArray());
    throw $exception;
}

```

