<?php

use PHPUnit\Framework\TestCase;
use YTsuzaki\PhpEnumSpy\Config;
use YTsuzaki\PhpEnumSpy\EnumFileFinder;

class EnumFileFinderTest extends TestCase
{

    public function test() {
        $finder = new EnumFileFinder(
            new Config()
        );
        $files = $finder->findPhpFiles();

        $this->assertIsArray($files);
        $this->assertNotEmpty($files);
        $this->assertContains('tests/examples/dir1/MyEnumA.php', $files);
        $this->assertContains('tests/examples/dir1/MyEnumB.php', $files);
        $this->assertContains('tests/examples/dir1/NestedDir/MyNestedDirEnum.php', $files);
        $this->assertContains('tests/examples/dir1/MyIntegerEnum.php', $files);
        $this->assertContains('tests/examples/dir2/MyEnumC.php', $files);
        $this->assertNotContains('tests/examples/dir1/MyClass.php', $files);
    }
}
