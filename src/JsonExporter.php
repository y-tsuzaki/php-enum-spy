<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use http\Exception;
use YTsuzaki\PhpEnumSpy\Metadata\EnumMetadata;

class JsonExporter
{
    private string $outputDir;

    /**
     * @param  Config  $config
     * @param  array<EnumMetadata>  $enumMetadatas
     */
    public function __construct(
        private Config $config,
        private array $enumMetadatas,
    )
    {
        $this->outputDir = getcwd() . '/output';
    }

    public function getOutputFilePath(): string
    {
        return $this->outputDir . '/enum_metadata.json';
    }

    public function export(): void
    {
        if (count($this->enumMetadatas) === 0) {
            throw new \Exception('No enum metadata found');
        }

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }
        $filePath = $this->getOutputFilePath();

        $json = json_encode($this->toArrayForJson(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($filePath, $json);
    }

    public function toArrayForJson(): array
    {
        $result = [
            "enumCount" => count($this->enumMetadatas),
            "enums" => [],
        ];
        foreach ($this->enumMetadatas as $enumMetadata) {
            $result["enums"][$enumMetadata->className] = $enumMetadata->toArrayForJson();
        }
        return $result;
    }

}