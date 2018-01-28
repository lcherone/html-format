## HTML formatter class.

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