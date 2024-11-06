<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use League\CLImate\CLImate;
use League\CLImate\Logger;
use Psr\Log\LogLevel;

class PhpEnumSpy
{

    public function __construct()
    {
    }

    public function run() {
        $climate = new CLImate();

        // Find php files
        $enumFileFinder = new EnumFileFinder();
        $extractor = new CaseExtractor(new Logger(LogLevel::WARNING));
        $enumFiles = $enumFileFinder->findPhpFiles();

        $climate->info('detected Enum PHP file!');

        $climate->info( implode(",\n", $enumFiles));

        // extract enum cases
        $enumMetadataList = [];
        foreach ($enumFiles as $enumFile) {
            $enumMetadata = $extractor->extractCases($enumFile);
            $enumMetadataList[] = $enumMetadata;
        }

        // export to csv
        $exporter = new CSVExporter($enumMetadataList);
        $exporter->export();

        $climate->info('Exported to :' . $exporter->getSavedFilePath() );
        $climate->info('Finished!');
    }
}