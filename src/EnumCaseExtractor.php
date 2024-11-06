<?php

declare(strict_types=1);

namespace YTsuzaki\PhpEnumSpy;

use Closure;
use Exception;
use InvalidArgumentException;
use League\CLImate\Logger;
use YTsuzaki\PhpEnumSpy\Metadata\CaseMetadata;
use YTsuzaki\PhpEnumSpy\Metadata\EnumMetadata;

class EnumCaseExtractor
{

    public function __construct(
        private Config $config,
        private Logger $logger,
    )
    {
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

        $reflectionEnum = new \ReflectionEnum($className);
        $caseMetadataList = [];
        foreach ($reflectionEnum->getCases() as $case) {
            $caseName = $case->getName();
            $caseValue = $case->getValue()->value;

            $convertedResults = [];
            $convertFunctions = $this->config->customConverters;
            foreach ($convertFunctions as $funcName => $closure) {
                try {
                    $convertedResults[$funcName] = $closure($case->getValue());
                } catch (Exception $e) {
                    $convertedResults[$funcName] = "ERROR";

                    $this->logger->error("Failed to convert the value of the case: $caseName");
                    $this->logger->error($e->getMessage());
                }
            }

            $caseMetadataList[] = new CaseMetadata(
                $caseName,
                $caseValue,
                $convertedResults
            );
        }
        $enumMetaData = new EnumMetadata(
            $className,
            $filePath,
            $caseMetadataList
        );

        $this->logger->info("Detected cases: " . implode(", ", $enumMetaData->getCaseNames()));

        return $enumMetaData;
    }
}