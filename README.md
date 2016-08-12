# Image-Validator

Extra validation rules for dealing with images in Laravel 5.

[![Latest Version](https://img.shields.io/packagist/v/cviebrock/image-validator.svg?style=flat-square)](https://github.com/cviebrock/eloquent-taggable/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/cviebrock/image-validator.svg?style=flat-square)](https://packagist.org/packages/cviebrock/eloquent-taggable)
[![Build Status](https://img.shields.io/travis/cviebrock/image-validator/master.svg?style=flat-square)](https://travis-ci.org/cviebrock/eloquent-taggable)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/9e1bb86e-2659-4123-9b6f-89370ef1483d.svg?style=flat-square)](https://insight.sensiolabs.com/projects/9e1bb86e-2659-4123-9b6f-89370ef1483d)
[![Quality Score](https://img.shields.io/scrutinizer/g/cviebrock/image-validator.svg?style=flat-square)](https://scrutinizer-ci.com/g/cviebrock/eloquent-taggable)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


* [Installation](#installation)
* [Usage](#usage)
* [Examples](#examples)
* [Bugs, Suggestions and Contributions](#bugs-suggestions-and-contributions)
* [Copyright and License](#copyright-and-license)

> *NOTE:* Version 2.x of this package is designed to work with Laravel 5.  If you are using Laravel 4, then checkout the `1.x` branch and use the latest version there.

---

<a name="installation"></a>
## Installation

Install the package through [Composer](http://getcomposer.org).

```shell
composer require "cviebrock/image-validator:^2.1"
```

Add the following to your `providers` array in `app/config/app.php`:

```php
'providers' => [
    ...
    \Cviebrock\ImageValidator\ImageValidatorServiceProvider::class,
],
```



<a name="usage"></a>
## Usage

Use it like any `Validator` rule.  The package offers two rules for image validation:

### image_size

```php
$rules = [
    'my_image_field' => 'image_size:<width>[,<height>]',
];
```

The values for _width_ and _height can be integers, or integers with a modifier prefix:

- `300` or `=300` means the dimension must be exactly 300 pixels.
- `<300` means the dimension must be less than 300 pixels
- `<=300` means the dimension must be less than or equal to 300 pixels
- `>300` means the dimension must be greater than 300 pixels
- `>=300` means the dimension must be greater than or equal to 300 pixels
- `200-300` means the dimension must be betweem 200 and 300 pixels (inclusive)
- `*` means the dimension can be any value

If you only pass one value, it's assumed to apply to both dimensions (i.e. a square image with the given dimensions).

### image_aspect

```php
$rules = [
    'my_image_field' => 'image_aspect:<ratio>',
];
```

The value for _ratio_ represents _width ÷ height_ and be either a decimal or two values (width, height; both integers):

- `0.75`
- `3,4`

The value (or first value, if providing height and width) can also be prefixed with a tilde `~` character,
in which case the orientation does not matter:

- `~3,4` means the image can have an aspect ratio of either 3:4 or 4:3.

Note that you may run into issues with floating point rounding.


<a name="examples"></a>
## Examples

```php
// logo must be 300px wide by 400px tall
$rules = [
    'logo' => 'required|image|image_size:300,400',
];

// logo must be less than or equal to 300x300px.
$rules = [
    'logo' => 'required|image|image_size:<=300',
];

// logo must be 300px wide but can be any height
$rules = [
    'logo' => 'required|image|image_size:300,*',
];

// logo must be at least 100px tall and 200-300 pixels wide (inclusive)
$rules = [
    'logo' => 'required|image|image_size:>=100,200-300',
];

// logo must be square
$rules = [
    'logo' => 'required|image|image_aspect:1',
];

// logo must be ready for the big screen TV :)
$rules = [
    'logo' => 'required|image|image_aspect:16,9',
];
```



## Bugs, Suggestions and Contributions

Thanks to [everyone](https://github.com/cviebrock/image-validator/graphs/contributors)
who has contributed to this project!

Please use [Github](https://github.com/cviebrock/image-validator) for reporting bugs, 
and making comments or suggestions.
 
See [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute changes.



## Copyright and License

[image-validator](https://github.com/cviebrock/image-validator)
was written by [Colin Viebrock](http://viebrock.ca) and is released under the 
[MIT License](LICENSE.md).

Copyright 2013 Colin Viebrock



## Thanks

Lots of thanks to https://bitbucket.org/hampel/validate-laravel for the structure of creating a package to add validator rules to Laravel, and setting up useful unit tests.
