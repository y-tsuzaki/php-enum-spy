<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

class EnumFileFinder
{
    /**
     * @var array
     */
    private array $targetDirs;

    public function __construct(
        private Config $config,
    )
    {
        $this->targetDirs = $this->config->targetDirs;
    }

    function findPhpFiles(): array
    {
        $files = [];
        foreach ($this->targetDirs as $targetDir) {
            $filesInSingleDir = $this->findPhpFilesInDir($targetDir);
            $files = array_merge($files, $filesInSingleDir);
        }
        return $files;
    }

    private function findPhpFilesInDir(string $targetDir): array {
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