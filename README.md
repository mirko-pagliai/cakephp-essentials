## Creates links to assets
```bash
mkdir webroot/vendor/ -p
ln -s ../../vendor/twbs/bootstrap/dist/ webroot/vendor/bootstrap -f
ln -s ../../vendor/fortawesome/font-awesome/ webroot/vendor/font-awesome -f
ln -s ../../vendor/axllent/jquery/ webroot/vendor/jquery -f
ln -s ../../vendor/moment/moment/min/ webroot/vendor/moment -f
```

## Extends the `View`
```php
use Cake\Essentials\View\View;

class AppView extends View
{
}
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
