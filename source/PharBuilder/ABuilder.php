<?php

namespace PharBuilder;

abstract class ABuilder
{
    protected $meta;
    protected $name;
    protected $file;
    protected $phar;

    abstract protected function generateStubContent();

    public function __construct($_name, $_file, $_path, $_meta = array())
    {
        $this->name = $_name;
        $this->file = $_file;
        $this->meta = $_meta;

        if (!isset($this->meta["buildDate"])) {
            $this->meta["buildDate"] = date("Y-m-d H:i:s");
        }

        $this->phar = new \Phar($_path . DIRECTORY_SEPARATOR . $_file . ".phar", \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME, "$_name.phar");

        $this->phar->startBuffering();
    }

    public function addDirFiles($_dir)
    {
        $files = 0;

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($_dir)) AS $fileinfo) {
            if ($fileinfo->isFile()) {
                ++$files;
                $this->addFile($fileinfo->getPathname(), str_replace($_dir . DIRECTORY_SEPARATOR, "", $fileinfo->getPathname()));
            }
        }

        return $files;
    }

    abstract public function addFile($_srcPath, $_destPath);

    public function build()
    {
        $this->phar->setMetadata($this->meta);
        $this->phar->setStub($this->generateStubContent());
        $this->phar->stopBuffering();
    }
}
