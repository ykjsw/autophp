<?php

date_default_timezone_set('Asia/Chongqing');

define('IS_MOBILE', is_mobile_client());

$config = array();

include(BASE_PATH . 'config/setting.php');
include(BASE_PATH . 'config/routing.php');

$wei = wei(array(
		'db' => $config['mysql']['db']
));

wei(array(
    'wei' => array(
        'aliases' => array(
        	'user'			=> 'User',
        	'autodata'		=> 'AutoData',
        	'autovalidate'	=> 'AutoValidate',
        	'autofield'		=> 'AutoField',
        	'qqwry'			=> 'QQWry'
        )
    )
));