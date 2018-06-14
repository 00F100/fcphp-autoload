<?php

use FcPhp\Autoload\Autoload;
use PHPUnit\Framework\TestCase;
use FcPhp\Autoload\Interfaces\IAutoload;

class AutoloadUnitTest extends TestCase
{
	public function testPathLoadIsArray()
	{
		Autoload::path('tests/*/*/autoload', ['providers', 'routes'], ['php']);
		$this->assertTrue(is_array(Autoload::getAutoload()));
	}

	public function testPathLoadKey()
	{
		Autoload::path('tests/*/*/autoload', ['providers', 'routes'], ['php']);
		$this->assertTrue(is_array(Autoload::getAutoload('providers')));
	}

	public function testPathLoadKeyNonExists()
	{
		Autoload::path('tests/*/*/autoload', ['providers', 'routes'], ['php']);
		$this->assertTrue(is_array(Autoload::getAutoload('routes')));
	}
}