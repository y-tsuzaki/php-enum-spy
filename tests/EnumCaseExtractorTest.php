<?php

use PHPUnit\Framework\TestCase;

class EnumCaseExtractorTest extends TestCase
{

    public function test() {
        $extractor = new \YTsuzaki\PhpEnumSpy\CaseExtractor();

        $metaData = $extractor->extractCases('tests/examples/MyEnumA.php');

        $keyValue = $metaData->keyValues;

        $this->assertIsArray($keyValue);
        $this->assertNotEmpty($keyValue);

        $this->assertArrayHasKey('MY_CASE_A', $keyValue);
        $this->assertArrayHasKey('MY_CASE_B', $keyValue);
        $this->assertArrayHasKey('MY_CASE_C', $keyValue);
        $this->assertArrayNotHasKey('X', $keyValue);

        $this->assertContains('my_case_a', $keyValue);
        $this->assertContains('my_case_b', $keyValue);
        $this->assertContains('my_case_c', $keyValue);
        $this->assertNotContains('X', $keyValue);

        $convertedValues = $metaData->convertedValues;
        $myConvertResult = $convertedValues['myConvertFunction'];

        $this->assertIsArray($myConvertResult);
        $this->assertNotEmpty($myConvertResult);
        $this->assertArrayHasKey('MY_CASE_A', $myConvertResult);
        $this->assertArrayHasKey('MY_CASE_B', $myConvertResult);
        $this->assertArrayHasKey('MY_CASE_C', $myConvertResult);
        $this->assertArrayNotHasKey('X', $myConvertResult);

        $this->assertContains('converted_a', $myConvertResult);
        $this->assertContains('converted_b', $myConvertResult);
        $this->assertContains('converted_c', $myConvertResult);
        $this->assertNotContains('X', $myConvertResult);
    }
}
