# cakephp-essentials

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![CI](https://github.com/mirko-pagliai/cakephp-essentials/actions/workflows/ci.yml/badge.svg)](https://github.com/mirko-pagliai/cakephp-essentials/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-essentials/branch/master/graph/badge.svg?token=EG4qYNZrgi)](https://codecov.io/gh/mirko-pagliai/cakephp-essentials)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5e537ce1da06450885c841799fb43c6a)](https://app.codacy.com/gh/mirko-pagliai/cakephp-essentials/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

Various classes and useful utilities for various CakePHP projects.

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

## Extends the `View`
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
