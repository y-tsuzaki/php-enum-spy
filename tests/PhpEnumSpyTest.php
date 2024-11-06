<?php

class PhpEnumSpyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRun() {
        $app = new \YTsuzaki\PhpEnumSpy\PhpEnumSpy();
        $app->run();

        $this->assertFileExists(getcwd() . '/output/enum_metadata.csv');
        $this->assertFileExists(getcwd() . '/output/enum_metadata.json');
    }

}
