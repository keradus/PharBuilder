<?php

namespace PharBuilder;

class LibBuilder extends ABuilder
{
    public $cliStub = null;
    public $useSplClassLoader = false;

    protected function generateStubContent()
    {
        $code = '<?php
';

        if ($this->cliStub) {
            $code .= 'if (php_sapi_name() === "cli" && $argv[0] === basename(__FILE__)) {
    ' . $this->cliStub . '
    return;
}
';
        }

        if ($this->useSplClassLoader) {
            $code .= '
$l = new SplClassLoader("' . $this->name . '", "phar://' . $this->alias . '");
$l->register();
';
        }

        $code .= '__HALT_COMPILER();';

        return $code;
    }

    public function addFile($_srcPath, $_destPath)
    {
        $this->phar[$this->name . DIRECTORY_SEPARATOR . $_destPath] = php_strip_whitespace($_srcPath);
    }
}
