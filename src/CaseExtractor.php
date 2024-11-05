<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use Exception;
use InvalidArgumentException;

class CaseExtractor
{

    /**
     * @var array<\Closure>
     */
    private array $convertFunctions;

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

        $this->convertFunctions = $config['convert_functions'] ?? [];
    }

    public function extractCases(string $file): EnumMetadata
    {

        require_once $filePath = getcwd() . '/' . $file;

        $declaredClasses = get_declared_classes();
        $className = end($declaredClasses); // 最後に読み込まれたクラスが対象

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

        //　カスタム関数での変換
        $convertedResults = [];
        $convertFunctions = $this->convertFunctions;
        foreach ($convertFunctions as $funcName => $closure) {
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

        return $metaData;
    }
}