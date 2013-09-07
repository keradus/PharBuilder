<?php

namespace PharBuilder;

class LibBuilder extends ABuilder
{
    protected function generateStubContent()
    {
        return '<?php
$l = new SplClassLoader("' . $this->name . '", "phar://" . __DIR__ . "/' . $this->file . '.phar");
$l->register();
__HALT_COMPILER();';
    }

    public function addFile($_srcPath, $_destPath)
    {
        $this->phar[$this->name . DIRECTORY_SEPARATOR . $_destPath] = php_strip_whitespace($_srcPath);
    }
}
