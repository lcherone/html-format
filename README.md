## HTML formatter class.

[![Build Status](https://travis-ci.org/lcherone/html-format.svg?branch=master)](https://travis-ci.org/lcherone/html-format)
[![StyleCI](https://styleci.io/repos/119305734/shield?branch=master)](https://styleci.io/repos/119305734)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lcherone/html-format/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lcherone/html-format/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/lcherone/html-format/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lcherone/html-format/code-structure/master/code-coverage)
[![Packagist Version](https://img.shields.io/packagist/v/roesch/html-format.svg?style=flat-square)](https://github.com/roesch/html-format/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/roesch/html-format.svg?style=flat-square)](https://packagist.org/packages/roesch/html-format)


A simple to use HTML prettifier class written in PHP. Added as a composer library in memory of Bennett Roesch (spyrosoft).

**Original code from: https://github.com/spyrosoft/php-format-html-output**

## Install

Require this package with composer using the following command:

``` bash
$ composer require roesch/html-format
```

### Example usage:

    <?php
    require 'vendor/autoload.php';

    $html = 'Unformatted HTML string';

    // initialize class
    $format = new \Roesch\Format();

    // use spaces at 4 length
    echo $format->html($html);

    // use spaces at 2 length
    echo $format->html($html, true, 2);

    // use tabs
    echo $format->html($html, false);

### Static method example usage:

    <?php
    include_once('format.php');

    $html = 'Unformatted HTML string';

    // use spaces at 4 length
    echo \Roesch\Format::HTML($html);

    // use spaces at 2 length
    echo \Roesch\Format::HTML($html, true, 2);

    // use tabs
    echo \Roesch\Format::HTML($html, false);


## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING) for details.


## Credits

- [Bennett Roesch](https://github.com/spyrosoft)
- [Lawrence Cherone](https://github.com/lcherone)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.