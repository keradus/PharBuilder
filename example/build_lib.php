<?php

require "3rd/SplClassLoader.php";
require "../build/PharBuilder.phar";

$phar = new PharBuilder\LibBuilder(
    "Ker",
    "Ker",
    "build",
    array(
        "authors" => array(
            array(
                "name" => "Dariusz Ruminski",
                "email" => "dariusz.ruminski@gmail.com",
            ),
        ),
    )
);
$files = $phar->addDirFiles("source/Ker");

echo ("Added files: $files");

$phar->build();
