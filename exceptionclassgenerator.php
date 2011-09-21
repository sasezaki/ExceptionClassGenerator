<?php

use Zend\CodeGenerator\Php\PhpClass,
    Zend\CodeGenerator\Php\PhpFile,
    Zend\Reflection\ReflectionFile;


class ExceptionClassGenerator
{
    private $docblock;

    public function setDocblock($docblock)
    {
        $this->docblock = $docblock;
    }

    /**
     *
     * 
     */ 
    public static function generateAll($baseInterfaceFile)
    {
        foreach(static::getExceptionClasses() as $className) {
            $gen = new static;
            $gen->generateFile($className, $baseInterfaceFile);
        }
        
    }

    public function generateFile($className, $baseInterfaceFile)
    {
        include_once $baseInterfaceFile;
        $refFile = new ReflectionFile($baseInterfaceFile);

        $interfaceName = $refFile->getClass()->getName();

        $class = $this->generateClass($className, "$interfaceName", $interfaceName);
        $dir = substr($refFile->getFileName(), 0, strlen($refFile->getFileName()) - 4);
        $file = $dir . DIRECTORY_SEPARATOR . $className . '.php';

        if (file_exists($file)) {
            return false;
        }

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $phpFile = new PhpFile;
        $phpFile->setClass($class);
        $phpFile->setFileName($file);

        $phpFile->write();
    }

    public function generateClass($className, $namespaceName, $interfaceName)
    {
        $codeClass = new PhpClass;
        if ($this->docblock) {
            $codeClass->setDocblock($this->docblock);
        }
        $codeClass->setName($className);
        $codeClass->setNamespaceName($namespaceName);
        $codeClass->setExtendedClass("\\$className");
        $codeClass->setImplementedInterfaces(array("\\$interfaceName"));
        
        return $codeClass;
    }

    public static function getExceptionClasses()
    {
        $exceptionClasses = array();
        foreach (spl_classes() as $class) {
            if (!in_array('Exception', class_parents($class))) {
                continue;
            }
            $exceptionClasses[] = $class;
        }

        return $exceptionClasses;
    }
}

