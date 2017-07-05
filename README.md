# PhpWufoo

Another wrapper for the Wufoo API that takes a slightly different approach from the original and [its forks](https://github.com/adamlc/wufoo-php-api-wrapper).

## Usage

Use Composer to add the `allejo/php-wufoo` package to your dependencies.

```bash
composer require allejo/php-wufoo
```

## Examples

This wrapper takes a different approach and it's core functionality is available in the `WufooForm` class.

This wrapper assumes that you'll be working with only one Wufoo account. For that reason, you configure API access at a global level by including the following before you start using the API.

```php
use allejo\Wufoo\WufooForm;

WufooForm::configureApi('fishbowl', 'AOI6-LFKL-VM1Q-IEX9');
```

### Getting Form Details

```php
use allejo\Wufoo\WufooForm;

// Get details for all your Wufoo forms
$forms = WufooForm::getForms();

// Get details about an individual Wufoo form
$form = new WufooForm('wufoo-api-example');
$form->getDetails();
```

### Getting Form Entries

Creating filters and queries for retrieving entries for forms no longer requires you to remember or lookup the specific keywords and capitalization that the API expects. Just rely on your IDE's intellisense and you're set.

```php
use allejo\Wufoo\EntryFilter;
use allejo\Wufoo\EntryQuery;
use allejo\Wufoo\WufooForm;

$form = new WufooForm('wufoo-api-example');
$eq = EntryQuery::create()
    ->where([
        EntryFilter::create('EntryId')->lessThan(15)
    ])
    ->limit(10)
    ->getSystemFields()
;

$entries = $form->getEntries($eq);
```

## License

[MIT](https://github.com/allejo/PhpWufoo/blob/master/LICENSE.md)
