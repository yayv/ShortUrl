<?php

$CONFIG['site'] = "http://s.x0atop";
$CONFIG['sitebase'] = 'http://s.x0a.top/';
$CONFIG['sitename'] = '摩托短地址';

$CONFIG['database'] = array(
	'host' 		=> '127.0.0.1',
	'port' 		=> '3306',
	'username' 	=> 'office',
	'password' 	=> 'officedb',
	'database' 	=> 'shorturl',
	);

//获取短信验证码间隔时间，60秒
$CONFIG['smsExpires']=60;


// 以下 HTTP 头的输出，方便 web 端开发的调试
ini_set("error_log","/var/log/php_errors.log");
ini_set("log_errors","on");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-PINGOTHER, Content-Type');
header('Access-Control-Max-Age: 86400');

