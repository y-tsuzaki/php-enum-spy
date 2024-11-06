<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use http\Exception;
use YTsuzaki\PhpEnumSpy\Metadata\EnumMetadata;

class CSVExporter
{
    /**
     * @param  Config  $config
     * @param  array<EnumMetadata>  $enumMetadatas
     * @param  string|null  $outputDir
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
        return $this->outputDir . '/enum_metadata.csv';
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
        $csv = fopen( $filePath, 'w');
        $convertors = $this->config->getCustomConverterNames();

        fputcsv($csv, ['class_name', 'file_path', 'case', 'value', ...$convertors]);

        foreach ($this->enumMetadatas as $enumMetadata) {
            foreach ($enumMetadata->cases as $caseMetadata) {

                $convertedValues = array_map(fn($converterName) => $caseMetadata->convertedValues[$converterName], $convertors);

                fputcsv($csv, [
                    $enumMetadata->className,
                    $enumMetadata->filepath,
                    $caseMetadata->name,
                    $caseMetadata->value,
                    ...$convertedValues
                ]);
            }
        }
        fclose($csv);
    }

}