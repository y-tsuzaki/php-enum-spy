<?php

use League\CLImate\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use YTsuzaki\PhpEnumSpy\Config;
use YTsuzaki\PhpEnumSpy\EnumCaseExtractor;

class EnumCaseExtractorTest extends TestCase
{
    private EnumCaseExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = new EnumCaseExtractor(
            new Config(),
            new Logger(LogLevel::WARNING)
        );
    }


    /**
     * @runInSeparateProcess
     */
    public function test() {
        $metaData = $this->extractor->extractCases('tests/examples/dir1/MyEnumA.php');

        $this->assertStringEndsWith('tests/examples/dir1/MyEnumA.php' ,$metaData->filepath);
        $this->assertEquals('examples\Dir1\MyEnumA' ,$metaData->className);

        $caseNames = $metaData->getCaseNames();

        // test case names
        $this->assertIsArray($caseNames);
        $this->assertNotEmpty($caseNames);
        $this->assertContains('MY_CASE_A', $caseNames);
        $this->assertContains('MY_CASE_B', $caseNames);
        $this->assertContains('MY_CASE_C', $caseNames);

        // test case values
        $this->assertEquals('my_case_a', $metaData->getCaseValue('MY_CASE_A'));
        $this->assertEquals('my_case_b', $metaData->getCaseValue('MY_CASE_B'));
        $this->assertEquals('my_case_c', $metaData->getCaseValue('MY_CASE_C'));


        // test custom converter results
        $this->assertEquals('converted_a', $metaData->getConvertedValue('MY_CASE_A', 'custom_convert_1'));
        $this->assertEquals('converted_b', $metaData->getConvertedValue('MY_CASE_B', 'custom_convert_1'));
        $this->assertEquals('converted_c', $metaData->getConvertedValue('MY_CASE_C', 'custom_convert_1'));


        $this->assertEquals('日本語a', $metaData->getConvertedValue('MY_CASE_A', 'custom_convert_2'));
        $this->assertEquals('日本語b', $metaData->getConvertedValue('MY_CASE_B', 'custom_convert_2'));
        $this->assertEquals('日本語c', $metaData->getConvertedValue('MY_CASE_C', 'custom_convert_2'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testIntegerEnum() {
        $metaData = $this->extractor->extractCases('tests/examples/dir1/MyIntegerEnum.php');

        $caseNames = $metaData->getCaseNames();
        $this->assertIsArray($caseNames);
        $this->assertNotEmpty($caseNames);

        $this->assertContains('MY_CASE_A', $caseNames);
        $this->assertContains('MY_CASE_B', $caseNames);
        $this->assertContains('MY_CASE_C', $caseNames);
        $this->assertEquals('1', $metaData->getCaseValue('MY_CASE_A'));
        $this->assertEquals('2', $metaData->getCaseValue('MY_CASE_B'));
        $this->assertEquals('1234567890', $metaData->getCaseValue('MY_CASE_C'));
    }
}
