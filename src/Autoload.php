<?php

namespace FcPhp\Autoload
{
	use FcPhp\Autoload\Interfaces\IAutoload;

	class Autoload implements IAutoload
	{
		/**
		 * @var array $storage
		 */
		private static $storage = [];

		/**
		 * Method to load path and find match's
		 *
		 * @param string $pathExpression Directory to find file(s)
		 * @param array $fileNameMatch List of filename
		 * @param array $extensionMatch List of enable extensions
		 * @return void
		 */
		public static function path(string $pathExpression, array $fileNameMatch, array $extensionMatch) :void
		{
			$pathExplode = explode('*', $pathExpression);
			$total = count($pathExplode);
			if($total > 0) {
				self::process($pathExplode, $fileNameMatch, $extensionMatch);
			}
		}

		/**
		 * Method to return autoloaded files
		 *
		 * @param string $key Filename
		 * @return array
		 */
		public static function getAutoload(string $key = null) :array
		{
			if(!empty($key)) {
				if(isset(self::$storage[$key])) {
					return self::$storage[$key];
				}
			}else{
				return self::$storage;
			}
			return [];
		}

		/**
		 * Method to process directory and find filename
		 *
		 * @param array $paths
		 * @param array $files
		 * @param array $extensions
		 * @param string $path
		 * @param string $now
		 * @param bool $return
		 * @return void
		 */
		private static function process(array $paths, array $files, array $extensions, string $path = '', string $now = '', bool $return = true) :void
		{
			$currentPath = current($paths);
			$path = (!empty($path) ? $path : '') . (!empty($now) ? $now . '/' : '');
			if($currentPath != '/') {
				$path .= $currentPath;
			}
			if(is_dir($path)) {
				$contents = scandir($path);
				if(count($contents) > 0) {
					if($next = next($paths)) {
						foreach(array_diff($contents, array('.','..')) as $content) {
							if($next == '/') {
								self::process($paths, $files, $extensions, $path, $content, false);
							}else{
								if(is_dir($path . $content . $next)) {
									foreach($files as $file) {
										foreach($extensions as $extension) {
											$filePath = $path . $content . $next . '/' . $file . '.' . $extension;
											if(is_file($filePath)) {
												self::storage($file, require($filePath));
											}	
										}
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Method to storage content of files
		 *
		 * @param string $key Filename
		 * @param string $value Content of file
		 * @return void
		 */
		private static function storage(string $key, array $value) :void
		{
			if(!isset(self::$storage[$key])) {
				self::$storage[$key] = [];
			}
			self::$storage[$key] = array_merge(self::$storage[$key], $value);
		}
	}
}