# FcPhp Autoload

Package to autoload providers and routes into another composer packages

[![Build Status](https://travis-ci.org/00F100/fcphp-autoload.svg?branch=master)](https://travis-ci.org/00F100/fcphp-autoload) [![codecov](https://codecov.io/gh/00F100/fcphp-autoload/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-autoload)

## How to install

Composer:
```sh
$ composer require 00f100/fcphp-autoload
```

or add in composer.json
```json
{
	"require": {
		"00f100/fcphp-autoload": "*"
	}
}
```

## How to use

#### Create my `providers.php`

```php
<?php

return [
	\path\to\SomeClass::class,
	\path\to\package\Cool::class
];
```

#### Create my `routes.php`

```php
<?php

return [
	'path/to/route' => [
		'post' => 'SiteController@method'
	]
];
```

#### Find content in directory

```php
<?php

use FcPhp\Autoload\Autoload;

/**
 * Method to load path and find match's
 *
 * @param string $pathExpression Directory to find file(s)
 * @param array $fileNameMatch List of filename
 * @param array $extensionMatch List of enable extensions
 * @return void
 */
Autoload::path(string $pathExpression, array $fileNameMatch, array $extensionMatch);

/*
	Example to find inside composer directory

	============================================
	Example directory:
	============================================

	vendor/
		00f100/
			fcphp-di/
				autoload/
					providers.php
					prividers.txt
			fcphp-i18n/
			fcphp-provider/
				autoload/
					routes.php
		doctrine/
			doctrine/
			instructor/
		cake/
			bin/
			cake/
				autoload/
					providers.php

*/
Autoload::path('vendor/*/*/autoload', ['providers', 'routes'], ['php']);
/*
	============================================
	Below example match this files:
	============================================

	vendor/00f100/fcphp-di/autoload/providers.php
	vendor/00f100/fcphp-provider/autoload/routes.php
	vendor/cake/cake/autoload/providers.php
*/
```

#### Get content of files
```php
/*
	============================================
	Get the content using getAutoload method
	============================================
	[
		'path/to/route' => [
			'post' => 'SiteController@method'
		]
	]
	$arrayProviders = Autoload::getAutoload('providers');

	[
		\path\to\SomeClass,
		\path\to\package\Cool
	]
	$arrayRoutes = Autoload::getAutoload('routes');
*/
/**
 * Method to return autoloaded files
 *
 * @param string $key Filename
 * @return array
 */
Autoload::getAutoload(string $fileName);

```