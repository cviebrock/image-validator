# Image-Validator

Extra validation rules for dealing with images in Laravel 5.

[![Build Status](https://travis-ci.org/cviebrock/image-validator.svg?branch=master&format=flat)](https://travis-ci.org/cviebrock/image-validator)
[![Total Downloads](https://poser.pugx.org/cviebrock/image-validator/downloads?format=flat)](https://packagist.org/packages/cviebrock/image-validator)
[![Latest Stable Version](https://poser.pugx.org/cviebrock/image-validator/v/stable?format=flat)](https://packagist.org/packages/cviebrock/image-validator)
[![Latest Unstable Version](https://poser.pugx.org/cviebrock/image-validator/v/unstable?format=flat)](https://packagist.org/packages/cviebrock/image-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cviebrock/image-validator/badges/quality-score.png?format=flat)](https://scrutinizer-ci.com/g/cviebrock/image-validator)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bc2c9e90-2edf-4047-9b3c-a5aa15da165b/mini.png)](https://insight.sensiolabs.com/projects/bc2c9e90-2edf-4047-9b3c-a5aa15da165b)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](https://opensource.org/licenses/MIT)


* [Installation](#installation)
* [Usage](#usage)
  * [image_size](#image_size)
  * [image_aspect](#image_aspect)
* [Examples](#examples)
* [Bugs, Suggestions and Contributions](#bugs-suggestions-and-contributions)
* [Copyright and License](#copyright-and-license)

---

## Installation

> **NOTE**: Depending on your version of Laravel, you should install a different
> version of the package:
> 
> | Laravel Version | Package Version |
> |:---------------:|:---------------:|
> |       4.*       |       1.x       |
> |     5.0–5.3     |       2.1       |
> |       5.4       |       2.2       |

Install the package through [Composer](http://getcomposer.org).

```shell
composer require "cviebrock/image-validator"
```

Add the following to your `providers` array in `app/config/app.php`:

```php
'providers' => [
    ...
    \Cviebrock\ImageValidator\ImageValidatorServiceProvider::class,
],
```



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
- `200-300` means the dimension must be between 200 and 300 pixels (inclusive)
- `*` means the dimension can be any value

If you only pass one value, it's assumed to apply to both dimensions 
(i.e. a square image with the given dimensions).

### image_aspect

```php
$rules = [
    'my_image_field' => 'image_aspect:<ratio>',
];
```

The value for _ratio_ represents _width ÷ height_ and be either a decimal or 
two values (width, height; both integers):

- `0.75`
- `3,4`

The value (or first value, if providing height and width) can also be prefixed 
with a tilde `~` character, in which case the orientation does not matter:

- `~3,4` means the image can have an aspect ratio of either 3:4 or 4:3.

Note that you may run into issues with floating point rounding.


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

Lots of thanks to https://bitbucket.org/hampel/validate-laravel for the 
structure of creating a package to add validator rules to Laravel, 
and setting up useful unit tests.
