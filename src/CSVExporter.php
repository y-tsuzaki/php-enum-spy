<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use http\Exception;

class CSVExporter
{
    /**
     * @param string $outputDir
     * @param array<EnumMetadata> $enumMetadatas
     */
    public function __construct(
        private array $enumMetadatas,
        private ?string $outputDir = null
    )
    {
        if ($outputDir === null) {
            $this->outputDir = getcwd() . '/output';
        }
    }

    public function getSavedFilePath(): string
    {
        return $this->outputDir . '/enum_metadata.csv';
    }

    public function export(): void
    {
        if (count($this->enumMetadatas) === 0) {
            throw new \Exception('No enum metadata found');
        }

        mkdir( $this->outputDir);
        $csv = fopen( $this->outputDir . '/enum_metadata.csv', 'w');
        $convertors = array_keys($this->enumMetadatas[0]->convertedValues);
        fputcsv($csv, ['enumClass', 'filepath', 'case', 'value', ...$convertors]);

        foreach ($this->enumMetadatas as $enumMetadata) {
            foreach ($enumMetadata->keyValues as $case => $value) {
                $convertedResults = [];
                foreach ($enumMetadata->convertedValues as $funcName => $convertedValues) {
                    $convertedResults[] = $convertedValues[$case];
                }

                fputcsv($csv, [
                    $enumMetadata->filepath,
                    $enumMetadata->enumClass,
                    $case,
                    $value,
                    ...$convertedResults
                ]);

            }
        }
        fclose($csv);
    }

}