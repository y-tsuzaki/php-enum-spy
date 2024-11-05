<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use http\Exception\InvalidArgumentException;

class EnumFileFinder
{
    /**
     * @var array|mixed
     */
    private array $targetDirs;

    public function __construct()
    {
        // 現在のディレクトリの設定ファイルを読み込む
        $configFile = getcwd() . "/php-enum-spy.config.php";

        if (file_exists($configFile)) {
            // 設定ファイルをインクルードして、$config変数を取得
            $config = include $configFile;
        } else {
            // エラーハンドリング
            throw new InvalidArgumentException("Config file not found!");
        }

        $this->targetDirs = $config['dirs'] ?? [];
    }

    function findPhpFiles(): array
    {
        $targetDir = $this->targetDirs[0]; // fixme 一つ目だけ対応
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($targetDir));
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (preg_match('/\.php$/', $file->getFilename())) {
                if (!$this->isEnumFile($file->getPathname())) {
                    continue;
                }
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }

    function isEnumFile(string $file): bool
    {
        $tokens = token_get_all(file_get_contents($file));

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($token[0] === T_ENUM) {
                    return true;
                }
            }
        }
        return false;
    }

}