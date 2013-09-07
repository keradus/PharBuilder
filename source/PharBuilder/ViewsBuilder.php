<?php

namespace PharBuilder;

class ViewsBuilder extends ABuilder
{
    protected function generateStubContent()
    {
        return '<?php
Phar::mapPhar("' . $this->name . '.phar");
__HALT_COMPILER();';
    }

    public function addFile($_srcPath, $_destPath)
    {
        $this->phar[$_destPath] = php_strip_whitespace($_srcPath);
    }
}
