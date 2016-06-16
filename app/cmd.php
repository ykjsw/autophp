<?php

define('BASE_PATH',	dirname(__FILE__).'/../');

include(BASE_PATH . 'vendor/autoload.php');
include(BASE_PATH . 'app/bootstrap.php');

if(count($argv) < 2){
	exit("parameter error\r\n");
}

if(strstr($argv[1], '::')){
	list($cmd, $method) = explode('::', $argv[1], 2);
}else{
	$cmd = $argv[1];
	$method = null;
}

$cmdClass = $cmd. 'CMD';

if(!class_exists($cmdClass)){
	exit('class ' . $cmdClass . " no exist\r\n");
}

$obj = new $cmdClass();

if($method){
	call_user_func(array($obj, $method));
}
