<?php

require "3rd/SplClassLoader.php";
require "../build/PharBuilder.phar";

$phar = new PharBuilder\ViewsBuilder("KT_views", "KT_views", "build");
$files = $phar->addDirFiles("source/views");

echo "Added files: $files";

$phar->build();
