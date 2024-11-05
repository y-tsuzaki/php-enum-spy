<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use League\CLImate\CLImate;

class PhpEnumSpy
{

    public function __construct()
    {
    }

    public function run() {
        $climate = new CLImate();

        $enumFileFinder = new EnumFileFinder();
        $extractor = new CaseExtractor();
        $enumFiles = $enumFileFinder->findPhpFiles();

        $climate->info('detected Enum PHP file!');

        $climate->info( implode(",\n", $enumFiles));

        $climate->info('extract enum definitions!');
        $enumMetadataList = [];
        foreach ($enumFiles as $enumFile) {
            $enumMetadata = $extractor->extractCases($enumFile);
            $enumMetadataList[] = $enumMetadata;
        }

        $exporter = new CSVExporter($enumMetadataList);
        $exporter->export();

        $climate->info('Exported to :' . $exporter->getSavedFilePath() );

        $climate->info('Finished!');
    }
}