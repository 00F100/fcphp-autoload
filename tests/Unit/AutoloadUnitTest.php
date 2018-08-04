<?php

use FcPhp\Autoload\Autoload;
use PHPUnit\Framework\TestCase;
use FcPhp\Autoload\Interfaces\IAutoload;

class AutoloadUnitTest extends TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = Autoload::getInstance();
    }

    public function testBeforeMatch()
    {
        $phpunit = $this;
        $this->instance
            ->beforeMatch(function(string $pathExpression, array $fileNameMatch, array $extensionMatch) use ($phpunit) {
                $phpunit->assertTrue(!empty($pathExpression));
                $phpunit->assertTrue(count($fileNameMatch) > 0);
                $phpunit->assertTrue(count($extensionMatch) > 0);
            })
            ->beforeMatchAgain(function(array $paths, array $files, array $extensions, string $path, string $now) use ($phpunit) {
                $phpunit->assertTrue(count($paths) > 0);
                $phpunit->assertTrue(count($files) > 0);
                $phpunit->assertTrue(count($extensions) > 0);
                $phpunit->assertTrue(!empty($path));
                $phpunit->assertTrue(!empty($now));
            })
            ->beforeStorage(function(string $file, string $filePath) use ($phpunit) {
                $phpunit->assertTrue(!empty($file));
                $phpunit->assertTrue(!empty($filePath));
            });
        $this->instance->path('tests/*/*/autoload', ['providers', 'routes'], ['php']);
    }

    public function testPathLoadIsArray()
    {
        $this->assertTrue(is_array($this->instance->path('tests/*/*/autoload', ['providers', 'routes'], ['php'])->get()));
    }

    public function testPathLoadKey()
    {
        $this->assertTrue(is_array($this->instance->path('tests/*/*/autoload', ['providers', 'routes'], ['php'])->get('providers')));
    }

    public function testPathLoadKeyNonExists()
    {
        $this->assertTrue(is_array($this->instance->path('tests/*/*/autoload', ['providers', 'routes'], ['php'])->get('routes')));
    }
}
