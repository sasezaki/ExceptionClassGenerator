<?php
$zf2 = $_SERVER['HOME']. '/dev/zendframework_zf2/library';
require_once $zf2. '/Zend/Loader/StandardAutoloader.php';
use Zend\Loader\StandardAutoloader;

$autoloader = new StandardAutoloader;
$autoloader->register();
require 'exceptionclassgenerator.php';

ExceptionClassGenerator::generateAll($argv[1]);

//$gen = new ExceptionClassGenerator;
//$ret = $gen->generateFile('InvalidArgumentException', $argv[1]);
