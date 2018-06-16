<?php

namespace FcPhp\Autoload\Interfaces
{
	use FcPhp\Autoload\Interfaces\IAutoload;
	
	interface IAutoload
	{
		public static function getInstance() :IAutoload;

		public function path(string $pathExpression, array $fileNameMatch, array $extensionMatch) :IAutoload;

		public function get(string $key = null) :array;

		public function beforeMatch($clousure) :IAutoload;

		public function beforeMatchAgain($clousure) :IAutoload;

		public function beforeStorage($clousure) :IAutoload;
	}
}