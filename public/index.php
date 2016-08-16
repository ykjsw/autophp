<?php

define('PHP_START_TIME', microtime(true));

define('BASE_PATH',	dirname(__FILE__).'/../');
define('STATIC_PATH',	BASE_PATH.'public/static/');

include(BASE_PATH . 'vendor/autoload.php');
include(BASE_PATH . 'app/bootstrap.php');


if($_SERVER['HTTP_HOST'] === 'autophp.net'){
	Header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://www.autophp.net' . $_SERVER['REQUEST_URI']);
	return;
}

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use($config) {
	
	foreach($config['routing'] AS $routeRule){
		$r->addRoute($routeRule['method'], $routeRule['path'], $routeRule['handler']);
	}
	
	$ruleFile = BASE_PATH . 'cache/routing_rule.php';
	
	if(file_exists($ruleFile)){
		$routeRules = include($ruleFile);
		foreach($routeRules as $routeRule){
			$r->addRoute($routeRule['method'], $routeRule['path'], $routeRule['handler']);
		}
	}
});

// Fetch method and URI from somewhere
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_HOST'].$uri);


switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        header('HTTP/1.0 404 Not Found');
		echo '文件不存在';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        header('HTTP/1.0 405 Method Not Allowed');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
		
		if(is_string($handler)){
			list($class, $method) = explode('::', $handler, 2);
		
			if(class_exists($class)){
				$obj = new $class;
				
				if(method_exists($obj, $method)){
					call_user_func_array(array($obj, $method), array($_REQUEST + $vars));
				}else{
					echo 'method no exist';
				}
				
			}else{
				echo 'class no exist';
			}
		}else{
			call_user_func_array($handler, array($_REQUEST + $vars));
		}
        
        break;
}


