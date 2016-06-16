<?php

$config['route'] = array(
		
		array(
			'method' 	=> 'GET',
			'path'		=> 'www.autophp.net/',
			'handler'	=> 'HomeController::index'
		),
		
		array(
			'method' 	=> 'GET',
			'path'		=> 'www.autophp.net/auto[/]',
			'handler'	=> 'AutoController::autoAdmin'
		),

		array(
			'method' 	=> 'GET',
			'path'		=> 'www.autophp.net/auto/out',
			'handler'	=> 'AutoController::autoDataOut'
		),
		
		array(
			'method' 	=> 'POST',
			'path'		=> 'www.autophp.net/auto/create',
			'handler'	=> 'AutoController::create'
		),

		array(
			'method' 	=> 'POST',
			'path'		=> 'www.autophp.net/auto/update',
			'handler'	=> 'AutoController::update'
		),
		
		array(
			'method' 	=> 'POST',
			'path'		=> 'www.autophp.net/auto/delete',
			'handler'	=> 'AutoController::delete'
		),

		array(
			'method' 	=> 'POST',
			'path'		=> 'www.autophp.net/auto/upload',
			'handler'	=> 'AutoController::fileUpload'
		),

);
