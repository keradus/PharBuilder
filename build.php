<?php

require "source/PharBuilder/ABuilder.php";
require "source/PharBuilder/LibBuilder.php";

$phar = new PharBuilder\LibBuilder(
    "PharBuilder",
    "PharBuilder",
    "build",
    array(
        "authors" => array(
            array(
                "name" => "Dariusz Ruminski",
                "email" => "dariusz.ruminski@gmail.com",
            ),
        ),
        "descr" => "Build entire application or just views files into single phar file.",
        "license" => "MIT",
        "name" => "PharBuilder",
    )
);

$files = $phar->addDirFiles("source/PharBuilder");

echo "Added files: $files";

$phar->useSplClassLoader = true;
$phar->cliStub = 'require "phar://" . __FILE__ . "/PharBuilder/CLI.php"; new \PharBuilder\CLI();';

$phar->build();
