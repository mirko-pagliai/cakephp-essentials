# cakephp-essentials

[![codecov](https://codecov.io/gh/mirko-pagliai/cakephp-essentials/branch/master/graph/badge.svg?token=EG4qYNZrgi)](https://codecov.io/gh/mirko-pagliai/cakephp-essentials)

Various classes and utilities useful for various CakePHP projects.

## Creates links to assets
In your `composer.json`, add the `post-update-cmd` command event:
```
  "scripts": {
    "post-update-cmd": [
        "rm -rf webroot/vendor/ && mkdir webroot/vendor/ -p",
        "ln -s ../../vendor/twbs/bootstrap/dist/ webroot/vendor/bootstrap -f",
        "ln -s ../../vendor/twbs/bootstrap-icons/font/ webroot/vendor/bootstrap-icons -f",
        "ln -s ../../vendor/axllent/jquery/ webroot/vendor/jquery -f",
        "ln -s ../../vendor/moment/moment/min/ webroot/vendor/moment -f"
    ]
  },
```

See [Composer documentation](https://getcomposer.org/doc/articles/scripts.md#command-events).

Or run the commands directly in the shell:
```bash
rm -rf webroot/vendor/ && mkdir webroot/vendor/ -p
ln -s ../../vendor/twbs/bootstrap/dist/ webroot/vendor/bootstrap -f
ln -s ../../vendor/twbs/bootstrap-icons/font/ webroot/vendor/bootstrap-icons -f
ln -s ../../vendor/axllent/jquery/ webroot/vendor/jquery -f
ln -s ../../vendor/moment/moment/min/ webroot/vendor/moment -f
```
It can also be useful to launch the command:
```bash
bin/cake plugin assets symlink -q --overwrite
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

## Add some basic assets to the layout
You can consider adding some key assets to your layout, depending on your needs:

Css:
```php
echo $this->fetch('css');
echo $this->Html->css('/vendor/bootstrap/css/bootstrap.min.css');
echo $this->Html->css('/vendor/bootstrap-icons/bootstrap-icons.min.css');
```

Scripts:
```php
echo $this->fetch('script');
echo $this->Html->script('/vendor/jquery/jquery.min.js');
echo $this->Html->script('/vendor/bootstrap/js/bootstrap.min.js');
echo $this->Html->script('/cake/essentials/js/collapsible-toggle-icon.min.js');
```

## Using Tooltips and Popovers

Several helper methods support tooltips and popovers and can generate them automatically.

Please refer to the Bootstrap documentation before using them ([here](https://getbootstrap.com/docs/5.3/components/popovers) and [here](https://getbootstrap.com/docs/5.3/components/tooltips)).

Keep in mind that:
1) both depend on the third-party library _Popper_, which you need to include, or you can use `bootstrap.bundle.min.js` which contains _Popper_;
2) you will need to initialize both, as indicated in the documentation.  
You can include `webroot/js/enable-popovers.min.js` and `webroot/js/enable-tooltips.min.js` files in yourt layout, which will do it automatically:
```php
echo $this->Html->script('/cake/essentials/js/enable-popovers.min.js');
echo $this->Html->script('/cake/essentials/js/enable-tooltips.min.js');
```

## `Validator` class: custom validation methods

This class extends the CakePHP `Validator`, providing additional rules for advanced data validation. Main methods include:

- **containsCapitalLetter($field, $message = null, $when = null)**  
  Ensures the field contains at least one uppercase (capital) letter.

- **containsDigit($field, $message = null, $when = null)**  
  Ensures the field contains at least one numeric digit.

- **containsLowercaseLetter($field, $message = null, $when = null)**  
  Ensures the field contains at least one lowercase letter.

- **firstLetterCapitalized($field, $message = null, $when = null)**  
  Ensures the value starts with an uppercase letter.

- **greaterThanOrEqualsDateTime($field, DateTime $comparisonValue, $message = null, $when = null)**  
  Ensures the date/time value is greater than or equal to the given `DateTime`.

- **notContainsReservedWords($field, $message = null, $when = null)**  
  Ensures the field does *not* contain any reserved words (e.g. `admin`, `root`, `password`, etc.).

- **personName($field, $message = null, $when = null)**  
  Ensures the field contains a valid person's name (starts with uppercase, meets formatting and length requirements).

All methods return the current `Validator` instance so they can be **chained** when configuring entity validation.


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

## Sets the default locale date and time format
For example, in your `bootstrap.php`:
```php
/**
 * Sets the default locale date and time format.
 *
 * @see https://book.cakephp.org/5/en/core-libraries/time.html#setting-the-default-locale-and-format-string
 * @see https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
 */
Date::setToStringFormat('dd/MM/yyyy');
DateTime::setToStringFormat('dd/MM/yyyy HH:mm');
```
