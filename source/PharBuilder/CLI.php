<?php

namespace PharBuilder;

class CLI
{
    private $phar;
    private $pharFile;

    private $remoteAddresses = array(
        "https://github.com/keradus/PharBuilder/raw/master/build/PharBuilder.phar",
    );

    private $commands = array(
        "about" => array(
            "descr" => "show file info",
            "method" => "about",
        ),
        "help" => array(
            "descr" => "show help",
            "method" => "help",
        ),
        "self-update" => array(
            "descr" => "alias for `update` command",
            "method" => "update",
        ),
        "update" => array(
            "descr" => "update phar file",
            "method" => "update",
        ),
    );

    public function __construct()
    {
        $command = (isset($_SERVER["argv"][1]) ? $_SERVER["argv"][1] : "about");

        if (!isset($this->commands[$command])) {
            $this->unknownAction();

            return;
        }

        $this->pharFile = basename(\Phar::running());
        $this->phar = new \Phar($this->pharFile);

        $this->{$this->commands[$command]["method"]}();
    }

    private function checkInternetConnection($_url = "www.google.com", $_port = 80)
    {
        $socket = @fsockopen($_url, $_port);

        if ($socket) {
            fclose($socket);

            return true;
        }

        return false;
    }

    public function unknownAction()
    {
        echo "Unknown action, run `help` command.\n";
    }

    public function about()
    {
        $meta = $this->phar->getMetadata();

        echo "{$meta["name"]} (build {$meta["buildDate"]}) on {$meta["license"]} license.\n";
        echo "{$meta["descr"]}\n";

        echo "\n";

        echo "Authors: \n";
        foreach ($meta["authors"] AS $author) {
            echo "    * {$author["name"]} <{$author["email"]}>\n";
        }

        echo "\n";

        echo "See `help` for available commands.\n";
    }

    public function help()
    {
        echo "Available commands:\n";

        ksort($this->commands);
        foreach ($this->commands AS $commandName => $commandData) {
            echo "    * $commandName - {$commandData["descr"]}\n";
        }
    }

    public function update()
    {
        $selfFilename = $_SERVER["argv"][0];
        $tempFilename = basename($selfFilename, ".phar") . "-temp.phar";

        try {
            $copied = false;

            echo "Start downloading file...\n";

            for ($i = 0, $iLimit = count($this->remoteAddresses); $i < $iLimit; ++$i) {
                echo "    loading file from source #" . ($i + 1) . "... ";

                $copied = @copy($this->remoteAddresses[$i], $tempFilename);

                if ($copied) {
                    echo "[done]\n";
                    break;
                }

                echo "[fail]\n";
            }

            if (!$copied) {
                if (!$this->checkInternetConnection()) {
                    throw new \RuntimeException("Error while connecting the Internet!");
                }

                throw new \RuntimeException("Error while loading file from external server!");
            }

            chmod($tempFilename, 0777);

            rename($tempFilename, $selfFilename);

            echo "Update complete.\n";
        } catch (\Exception $e) {
            if (file_exists($tempFilename)) {
                unlink($tempFilename);
            }

            echo $e->getMessage() . "\n";
        }
    }
}
