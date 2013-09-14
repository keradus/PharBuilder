<?php

namespace PharBuilder;

abstract class ABuilder
{
    protected $alias;
    protected $file;
    protected $filePath;
    protected $meta;
    protected $name;
    protected $phar;

    abstract protected function generateStubContent();

    public function __construct($_name, $_file, $_path, $_meta = array())
    {
        $this->name = $_name;
        $this->alias = ($this->name . ".phar");
        $this->file = $_file;
        $this->filePath = ($_path . DIRECTORY_SEPARATOR . $this->file . ".phar");
        $this->meta = $_meta;

        if (!isset($this->meta["buildDate"])) {
            $this->meta["buildDate"] = date("Y-m-d H:i:s");
        }

        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        $this->phar = new \Phar($this->filePath, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME, $this->alias);

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
