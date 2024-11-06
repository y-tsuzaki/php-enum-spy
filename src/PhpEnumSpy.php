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

        // initialize
        $config = new Config();
        $logger = new Logger(LogLevel::WARNING);

        // Find php files
        $enumFileFinder = new EnumFileFinder(
            $config
        );
        $enumFiles = $enumFileFinder->findPhpFiles();

        $climate->info('detected Enum PHP file!');

        $climate->info( implode(",\n", $enumFiles));

        // Extract enum cases
        $extractor = new EnumCaseExtractor(
            $config,
            $logger
        );
        $enumMetadataList = [];
        foreach ($enumFiles as $enumFile) {
            $enumMetadata = $extractor->extractCases($enumFile);
            $enumMetadataList[] = $enumMetadata;
        }

        // export to csv
        $exporter = new CSVExporter(
            $config,
            $enumMetadataList
        );
        $exporter->export();

        // export to json
        $exporter = new JsonExporter(
            $config,
            $enumMetadataList
        );
        $exporter->export();

        $climate->info('Exported to :' . $exporter->getOutputFilePath() );
        $climate->info('Finished!');
    }
}