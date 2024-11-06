<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use InvalidArgumentException;

class Config
{

    public readonly array $targetDirs;
    public readonly array $customConverters;

    public function __construct()
    {
        $config = $this->loadConfigFromFile();

        $this->validate($config);

        $this->targetDirs = $config['dirs'];
        $this->customConverters = $config['convert_functions'] ?? [];
    }

    private function loadConfigFromFile(): array {
        // FIXME en: I want to change the way the configuration file is read nicely
        $searchDir = getcwd();
        if (str_ends_with($searchDir, '/tests')) {
            // note: phpStorm上でテストを実行するときは、testsディレクトリがカレントディレクトリになるため、その場合は一つ上のディレクトリを検索対象にする
            // note-en: When running tests on phpStorm, the current directory is the tests directory, so in that case, search one directory up
            $searchDir = dirname($searchDir);
        }
        $configFile = $searchDir . "/php-enum-spy.config.php";

        if (file_exists($configFile)) {
            $config = include $configFile;
        } else {
            throw new InvalidArgumentException("Config file not found!");
        }
        return $config;
    }

    private function validate(array $config): void {
        if( !isset($config['dirs'])) {
            throw new InvalidArgumentException("dirs key not found in config file!");
        }
        if( !is_array($config['dirs'])) {
            throw new InvalidArgumentException("dirs key must be array in config file!");
        }
        if( isset($config['customConverters']) && !is_array($config['customConverters'])) {
            throw new InvalidArgumentException("customConverters key must be array in config file!");
        }

        return;
    }

    public function getCustomConverterNames(): array
    {
        return array_keys($this->customConverters);
    }
}