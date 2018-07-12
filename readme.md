## <p align="left">Viscle</p>

Viscle (Visual Lifecycle) provides a visual lifecycle for PHP request.


## Installation

```sh
composer require meneguetti/viscle
```

## Usage
1. Include the following code where you want to start capturing the request lifecycle, it has to be somewhere after requiring 'vendor/autoload.php'.
```php
\Viscle\Viscle::capture(); 
```
2. Include the following code where you want to stop capturing and render the visual lifecycle.
```php
echo \Viscle\Viscle::render();
```

## Examples
* Example 1 - Simple usage of a set of classes within example folder:

```php
\Viscle\Viscle::capture();

$a = new \Viscle\Example\A;
$a->perform();

echo \Viscle\Viscle::render();
```
It will render like following:

![Example 1](/example/image/1.png)


* Example 2 - Usage in a framework like Laravel:

```php
//inside public/index.php
require __DIR__.'/../vendor/autoload.php';

$filter = new \Viscle\Filter\NamespaceWhitelist();
$filter->classes = ['App']; //We don't want our graph too long, right?! ;)

\Viscle\Viscle::capture($filter); 

...

//inside your controller/action or route closure
echo \Viscle\Viscle::render();
```
It will render like following:

![Example 2](/example/image/2.png)

## Requirements

PHP >= 7.1

Xdebug >= php_xdebug-2.7.0alpha1-7.1

## License

Viscle is released under the MIT Licence. Check out LICENSE file for more details.