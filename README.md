# Ustream Option monad [![Build Status](https://travis-ci.org/ustream/option.png?branch=master)](http://travis-ci.org/ustream/option)

This is a PHP interpretation of the [Option type](http://en.wikipedia.org/wiki/Option_type) with [monadic](http://en.wikipedia.org/wiki/Monads_in_functional_programming) structure.
We beleive that the tests document the behavior and even though the implementation is similar to [php-option](https://github.com/schmittjoh/php-option) there are some important differences we need to highlight.

### There is _apply_ instead of _map_ and _flatMap_

We found it useful, that the monadic computation may decide to return with an arbitrary type, that will be converted to an _Option_ if necessary.

The rules for `Some::apply` is as follows:

* If the return type is not an _Option_ it will be wrapped in _Some_
* If there is no return value (actually the return value is null) it will be translated to _None_
* If the return type is an Option it will be passed through (the same as for _flatMap_)

### Examples

Wrapping a non _Option_ type:
```php
$result = someMethodReturningAnOptionalString($params)
	->apply(
		function ($result) {
			return $result . ' is a string!';
		})
	->getOrElse('default');
```

No need to explicitly return with _None_:
```php
$module = $dataMaybe
    ->apply(
		function ($data) {
			if (isset($data['moduleConfig'])) {
				if (count($data['moduleConfig']) == 1) {
					return key($data['moduleConfig']);
				} elseif (count($data['moduleConfig']) > 1) {
					return 'full';
				}
			}
		}
	)
	->getOrElse('unknown');
```

### There is _otherwise_ instead of _orElse_ with _LazyOption_

We use `None::otherwise` as the mirror of `Some::apply`. 

### Examples

Combining apply and otherwise:
```php
header(
    locate($_SERVER["DOCUMENT_URI"])
		->apply(
			function ($location) {
				statsdIncrement('302');
				return 'Location: ' . $location;
			}
		)
		->otherwise(
			function () {
				statsdIncrement('404');
			}
		)
		->getOrElse("HTTP/1.0 404 Not Found")
);
```

Creating a chain of fallback functions:

```php
return Ustream\Option\None::create()
    ->otherwise($this->ustreamPicture($userId, $size))
	->otherwise($this->facebookPictureFromSession($facebookNetwork, $isSecureAccess))
	->getOrElse($this->naPicture($size));
```