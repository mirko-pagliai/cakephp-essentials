# cakephp-essentials

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![CI](https://github.com/mirko-pagliai/cakephp-essentials/actions/workflows/ci.yml/badge.svg)](https://github.com/mirko-pagliai/cakephp-essentials/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-essentials/branch/master/graph/badge.svg?token=EG4qYNZrgi)](https://codecov.io/gh/mirko-pagliai/cakephp-essentials)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5e537ce1da06450885c841799fb43c6a)](https://app.codacy.com/gh/mirko-pagliai/cakephp-essentials/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

Various classes and useful utilities for various CakePHP projects.

* [Global functions](#global-functions)
    + [rtr()](#rtr--)
    + [toDate() and toDateTime()](#todate---and-todatetime--)
* [Request detectors](#request-detectors)
    + [is('action') detector](#is--action---detector)
    + [Other "action detectors": is('add'), is('edit'), is('view'), is('index'), is('delete')](#other--action-detectors---is--add----is--edit----is--view----is--index----is--delete--)
    + [is('ip') detector](#is--ip---detector)
    + [is('localhost') detector](#is--localhost---detector)
    + [is('trustedClient') detector](#is--trustedclient---detector)
* [Extends the View](#extends-the-view)
* [Using Tooltips and Popovers](#using-tooltips-and-popovers)
* [How to use Bake templates](#how-to-use-bake-templates)


## Global functions
### rtr()
`rtr()` is an acronym for "relative to root."

Returns a path relative to `ROOT`, useful for some output (e.g., commands).

For example:
```php
rtr(ROOT . 'webroot/assets')
```
returns `webroot/assets`.

The function preserves any trailing slashes and throws exceptions for invalid paths (e.g. not relative to `ROOT` or not
absolute).

### toDate() and toDateTime()
These two functions ensure they always return a valid `Date` or `DateTime` instance.

They are useful when a variable may already be a valid instance or an argument to create an instance and allow you to
avoid this:
```php
if (!$dateTime instanceof DateTime) {
    $dateTime = new DateTime($dateTime);
}

//or

$date = $date instanceof Date ? $date : new Date($date);
```
simply by doing this:
```php
$dateTime = toDateTime($dateTime);

//or

$date = toDate($date);
```

## Request detectors
This plugin provides several very useful 
[request detectors](https://book.cakephp.org/5.x/controllers/request-response.html#checking-request-conditions).

### is('action') detector
Checks if `$action` matches the current action.

The `$action` argument can be a string or an array of strings. In the second case, it is enough that the action matches
one of those.

Example:
```php
$this->getRequest()->is('action', 'delete')
```
returns `true` if the current action is `delete`, otherwise `false`.

Example:
```php
$this->getRequest()->isAction('action', 'edit', 'delete')
````
returns `true` if the current action is `edit` or `delete`, otherwise `false`.

### Other "action detectors": is('add'), is('edit'), is('view'), is('index'), is('delete')

These are quick aliases for `is('action')` detectors.

Example:
```php
$this->getRequest()->is('delete')
```
returns `true` if the current action is `delete`, otherwise `false`.

### is('ip') detector
Checks whether the current client IP matches the IP or one of the IPs passed as an argument.

Example:
```php
$this->getRequest()->is('ip', '99.99.99.99')
```
returns `true` if the current client IP is `99.99.99.99`, otherwise `false`.

Example:
```php
$this->getRequest()->isAction('ip', ['99.99.99.99', '11.11.11.11']);
````
returns `true` if the current client IP is `99.99.99.99` or `11.11.11.11`, otherwise `false`.

### is('localhost') detector
This is a quick alias for `is('ip')` detector.

Returns `true` if the current client IP matches localhost.

### is('trustedClient') detector
This is a quick alias for `is('ip')` detector.

Returns `true` if it is a trusted client.

Before using this detector, you should write trusted clients into the configuration.

For example, in your `bootstrap.php` file,
```php
Configure::write('trustedIpAddress', ['45.46.47.48', '192.168.0.100']);
```

At this point,
```php
$this->getRequest()->isAction('trustedClient')
```
returns `true` if the current client IP matches one of these.

## Extends the View
```php
use Cake\Essentials\View\View;

class AppView extends View
{
}
```

If necessary, you can rewrite the default helpers by implementing the `initialize()` method and calling
    `parent::initialize()` **before** adding your own helpers.

```php
class AppView extends View
    public function initialize(): void
    {
        parent::initialize();
    
        /**
         * These override any helpers defined by the parent.
         */
        $this->addHelper('Html');
        $this->addHelper('Form');
    }
}
```

## Using Tooltips and Popovers
Several helper methods support tooltips and popovers and can generate them automatically.

Please refer to the Bootstrap documentation before using them ([here](https://getbootstrap.com/docs/5.3/components/popovers) and [here](https://getbootstrap.com/docs/5.3/components/tooltips)).

Keep in mind that:
1) both depend on the third-party library _Popper_, which you need to include, or you can use `bootstrap.bundle.min.js`
which contains _Popper_;
2) you will need to initialize both, as indicated in the documentation.  
You can include `webroot/js/enable-popovers.min.js` and `webroot/js/enable-tooltips.min.js` files in yourt layout, which
will do it automatically:
```php
echo $this->Html->script('/cake/essentials/js/enable-popovers.min.js');
echo $this->Html->script('/cake/essentials/js/enable-tooltips.min.js');
```

## How to use Bake templates
In your `config/bootstrap.php` file:
```php
Configure::write('Bake.theme', 'Cake/Essentials');
```

Or you can use the `--theme` option (or `--t`) with `Cake/Essentials` value.  
Example:
```bash
bin/cake bake template ServiceStops -t Cake/Essentials -f
```

See also [CakePHP Bake 2.x Cookbook](https://book.cakephp.org/bake/2/en/development.html#creating-a-bake-theme).
