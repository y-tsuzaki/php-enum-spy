<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use Closure;
use Exception;
use InvalidArgumentException;
use League\CLImate\Logger;

class CaseExtractor
{

    /**
     * @var array<\Closure>
     */
    private array $convertFunctions;
    public function __construct(
        private Logger $logger,
    )
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

        $this->convertFunctions = $config['convert_functions'] ?? [];
    }

    public function extractCases(string $file): EnumMetadata
    {
        $filePath = getcwd() . '/' . $file;
        $this->logger->debug("Loaded file: $filePath");

        /**
         * Use require_once to retrieve the class name from the file path.
         * NOTE: If the same file is loaded twice within the same process, the class name cannot be retrieved because it is already loaded.
         *       To avoid this issue in tests or similar scenarios, ensure that the test runs in a separate process when loading the same file multiple times.
         */

        $beforeClassCount = count(get_declared_classes());
        require_once $filePath;
        $declaredClasses = get_declared_classes();
        if ($beforeClassCount === count($declaredClasses)) {
            throw new Exception("Unable to retrieve the class name from the file. If the same file is loaded twice in the same process, the class name will not be accessible.");
        }
        $className = end($declaredClasses);

        $this->logger->debug("Detected class: $className");

        if (!enum_exists($className)) {
            throw new Exception("No class found in the file: $filePath");
        }

        // CASEのKeyValue取得
        $enumData = [];
        $reflectionEnum = new \ReflectionEnum($className);
        foreach ($reflectionEnum->getCases() as $case) {
            $caseValue = $case->getValue()->value;
            $enumData[$case->getName()] = $caseValue;
        }

        $this->logger->debug("Detected cases: " . implode(", ", array_keys($enumData)));

        //　カスタム関数での変換
        $convertedResults = [];
        $convertFunctions = $this->convertFunctions;
        foreach ($convertFunctions as $funcName => $closure) {
            $this->logger->debug("Call a custom convert function: $funcName");
            $convertedResults[$funcName] = [];
            foreach ($reflectionEnum->getCases() as $case) {
                $convertedResults[$funcName][$case->getName()] = $closure($case->getValue());
            }
        }

        $metaData = new EnumMetadata(
            $className,
            $filePath,
            $enumData,
            $convertedResults
        );

        $this->logger->info("Extracted enum metadata: $className");

        return $metaData;
    }
}