<?php

define('SITE_HOST', 'www.autophp.net');

define('COOKIE_DOMAIN', 'autophp.net');

define('ENCODE_HASH_KEY', 'df312wo');

define('DEBUG_ON', false);

define('AUTOPHP_VERSION', 'v0.1');

//autodata 读取的类型
define('AUTODATA_DATESOURCE_PAINTEXT',		1);
define('AUTODATA_DATESOURCE_JSONTEXT',		2);
define('AUTODATA_DATESOURCE_DBQUERY',		3);
define('AUTODATA_DATESOURCE_CLASSMETHOD',	4);


//字段类型
define('AUTO_FIELD_TEXT',				1);
define('AUTO_FIELD_RICHTEXT',			2);
define('AUTO_FIELD_SELECT',				3);
define('AUTO_FIELD_GROUP',				4);
define('AUTO_FIELD_DEFAULT',			5);
define('AUTO_FIELD_TIMESTAMP',			6);
define('AUTO_FIELD_DATETIME',			8);
define('AUTO_FIELD_IMAGE',				9);
define('AUTO_FIELD_FILE',				10);
define('AUTO_FIELD_IP',					11);
define('AUTO_FIELD_READ_ONLY',			13);
define('AUTO_FIELD_TEXTAREA',			14);
define('AUTO_FIELD_PASSWORD',			15);
define('AUTO_FIELD_TEXTAREA_WITH_UPLOAD',	16);
define('AUTO_FIELD_DATE',				17);
define('AUTO_FIELD_VIRTUAL',			18);
define('AUTO_FIELD_MULTI_SELECT',		19);
define('AUTO_FIELD_QQ',					20);
define('AUTO_FIELD_FILESIZE',			21);
define('AUTO_FIELD_JSON',				22);


define('AUTO_ROUTING_CLASSMETHOD',		1);
define('AUTO_ROUTING_FILE', 			2);
define('AUTO_ROUTING_TEXT',				3);
define('AUTO_ROUTING_REDIRECT_301', 	4);
define('AUTO_ROUTING_REDIRECT_302', 	5);


define('AUTO_PRIVILEGE_VIEW',					1);
define('AUTO_PRIVILEGE_VIEW_UPDATE', 			2);
define('AUTO_PRIVILEGE_VIEW_UPDATE_DELETE',		3);


$config['mysql'] = array();

$config['mysql']['db'] = array(
		'host'		=> 'localhost',
		'dbname'	=> 'autophp_web',
		'user'		=> 'root',
		'password'	=> 'cv1999',
		'charset'	=> 'utf8',
		'driver'	=> 'mysql'
);

$config['elasticsearch'] = array(
	'host'	=> '127.0.0.1',
	'port'	=> 9200
);