<?php

namespace FcPhp\Autoload
{
    use FcPhp\Autoload\Interfaces\IAutoload;

    class Autoload implements IAutoload
    {
        /**
         * @var array $storage
         */
        private $storage = [];

        /**
         * @var object $beforeMatch
         */
        private $beforeMatch;

        /**
         * @var object $beforeMatchAgain
         */
        private $beforeMatchAgain;

        /**
         * @var object $beforeStorage
         */

        private $beforeStorage;

        /**
         * @var FcPhp\Autoload\Interfaces\IAutoload $instance
         */
        private static $instance;

        /**
         * Method to return instance of Autoload
         *
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public static function getInstance() :IAutoload
        {
            if(!self::$instance instanceof IAutoload) {
                self::$instance = new Autoload();
            }
            return self::$instance;
        }

        /**
         * Method to load path and find match's
         *
         * @param string $pathExpression Directory to find file(s)
         * @param array $fileNameMatch List of filename
         * @param array $extensionMatch List of enable extensions
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function path(string $pathExpression, array $fileNameMatch, array $extensionMatch) :IAutoload
        {
            $this->storage = [];
            $pathExplode = explode('*', $pathExpression);
            $total = count($pathExplode);
            
            $this->doBeforeMatch($pathExpression, $fileNameMatch, $extensionMatch);
            if($total > 0) {
                $this->process($pathExplode, $fileNameMatch, $extensionMatch);
            }
            return $this;
        }

        /**
         * Method to return autoloaded files
         *
         * @param string $key Filename
         * @return array
         */
        public function get(string $key = null) :array
        {
            if(!empty($key)) {
                if(isset($this->storage[$key])) {
                    return $this->storage[$key];
                }
            }else{
                return $this->storage;
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
        private function process(array $paths, array $files, array $extensions, string $path = '', string $now = '', bool $return = true) :void
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
                                $this->doBeforeMatchAgain($paths, $files, $extensions, $path, $content);
                                $this->process($paths, $files, $extensions, $path, $content, false);
                            }else{
                                if(is_dir($path . $content . $next)) {
                                    foreach($files as $file) {
                                        foreach($extensions as $extension) {
                                            $filePath = $path . $content . $next . '/' . $file . '.' . $extension;
                                            if(is_file($filePath)) {
                                                $this->doBeforeStorage($file, $filePath);
                                                $this->storage($file, require($filePath));
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
         * Method to execute trigger before match
         *
         * @param string $pathExpression Directory to find file(s)
         * @param array $fileNameMatch List of filename
         * @param array $extensionMatch List of enable extensions
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        private function doBeforeMatch(string $pathExpression, array $fileNameMatch, array $extensionMatch) :IAutoload
        {
            if(gettype($this->beforeMatch) == 'object') {
                $beforeMatch = $this->beforeMatch;
                $beforeMatch($pathExpression, $fileNameMatch, $extensionMatch);
            }
            return $this;
        }

        /**
         * Method to configure trigger before match
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeMatch($clousure) :IAutoload
        {
            $this->beforeMatch = $clousure;
            return $this;
        }

        /**
         * Method to execute trigger before match again
         *
         * @param array $paths List of paths to load
         * @param array $files List of file name to find
         * @param array $extensions List of enable extensions
         * @param string $path All path now
         * @param string $now Next part of path
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        private function doBeforeMatchAgain(array $paths, array $files, array $extensions, string $path, string $now) :IAutoload
        {
            if(gettype($this->beforeMatchAgain) == 'object') {
                $beforeMatchAgain = $this->beforeMatchAgain;
                $beforeMatchAgain($paths, $files, $extensions, $path, $now);
            }
            return $this;
        }

        /**
         * Method to configure trigger before match again
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeMatchAgain($clousure) :IAutoload
        {
            $this->beforeMatchAgain = $clousure;
            return $this;
        }

        /**
         * Method to execute trigger before storage
         *
         * @param string $file File name
         * @param string $filePath Path of file
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        private function doBeforeStorage(string $file, string $filePath) :IAutoload
        {
            if(gettype($this->beforeStorage) == 'object') {
                $beforeStorage = $this->beforeStorage;
                $beforeStorage($file, $filePath);
            }
            return $this;
        }

        /**
         * Method to configure trigger before storage
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeStorage($clousure) :IAutoload
        {
            $this->beforeStorage = $clousure;
            return $this;
        }

        /**
         * Method to storage content of files
         *
         * @param string $key Filename
         * @param string $value Content of file
         * @return void
         */
        private function storage(string $key, array $value) :void
        {
            if(!isset($this->storage[$key])) {
                $this->storage[$key] = [];
            }
            $this->storage[$key] = array_merge($this->storage[$key], $value);
        }
    }
}
