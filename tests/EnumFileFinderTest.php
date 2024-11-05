<?php

use PHPUnit\Framework\TestCase;

class EnumFileFinderTest extends TestCase
{

    public function test() {
        $finder = new \YTsuzaki\PhpEnumSpy\EnumFileFinder();
        $files = $finder->findPhpFiles();

        $this->assertIsArray($files);
        $this->assertNotEmpty($files);
        $this->assertContains('tests/examples/MyEnumA.php', $files);
        $this->assertContains('tests/examples/MyEnumB.php', $files);
        $this->assertNotContains('tests/examples/MyClass.php', $files);
    }
}
