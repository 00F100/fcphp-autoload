<?php

namespace FcPhp\Autoload\Interfaces
{
    use FcPhp\Autoload\Interfaces\IAutoload;
    
    interface IAutoload
    {
        /**
         * Method to return instance of Autoload
         *
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public static function getInstance() :IAutoload;

        /**
         * Method to load path and find match's
         *
         * @param string $pathExpression Directory to find file(s)
         * @param array $fileNameMatch List of filename
         * @param array $extensionMatch List of enable extensions
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function path(string $pathExpression, array $fileNameMatch, array $extensionMatch) :IAutoload;

        /**
         * Method to return autoloaded files
         *
         * @param string $key Filename
         * @return array
         */
        public function get(string $key = null) :array;

        /**
         * Method to configure trigger before match
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeMatch($clousure) :IAutoload;

        /**
         * Method to configure trigger before match again
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeMatchAgain($clousure) :IAutoload;

        /**
         * Method to configure trigger before storage
         *
         * @param object $clousure Trigger to log
         * @return FcPhp\Autoload\Interfaces\IAutoload
         */
        public function beforeStorage($clousure) :IAutoload;
    }
}
