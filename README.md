PharBuilder
===========

> This library is obsolete! Use [Box](https://github.com/box-project/box2) instead!

Build entire application or just views files into single phar file.

Usage
-----

### Code usage

    <?php

    $phar = new PharBuilder\LibBuilder("PharBuilder", "PharBuilder", "build");
    $phar->addDirFiles("source/PharBuilder");
    $phar->build();

See example dir for more examples.

### Command line

The `update` command tries to update phar itself:

    php PharBuilder.phar update