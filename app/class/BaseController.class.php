<?php

class BaseController{
	
	public function __construct(){
		
	}
	
	public function startSession(){
		$mysession = new MysqlSession();
		
		session_set_save_handler(
		    array($mysession, 'open'),
		    array($mysession, 'close'),
		    array($mysession, 'read'),
		    array($mysession, 'write'),
		    array($mysession, 'destroy'),
		    array($mysession, 'gc')
		);
		
		register_shutdown_function('session_write_close');
		
		session_start();
	}
	
	public function render($data){
		$klass = get_class($this);
		$method = debug_backtrace()[1]['function'];
		
		$tplname = strtolower(str_replace('Controller', '', $klass)).'_' .strtolower($method);
		
		include template($tplname);
	}
	
	public function renderFile($file, $data){
		include template($file);
	}
	
	public function isAjax(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}
		return false;
	}
	
	public function setCors(){
		
		if(isset($_SERVER['HTTP_REFERER'])){
			$arr = parse_url($_SERVER['HTTP_REFERER']);
		
			if(strstr($arr['host'], COOKIE_DOMAIN)){
				header('Access-Control-Allow-Origin: http://'.$arr['host']);
				header('Access-Control-Allow-Credentials: true');
				header('Access-Control-Allow-Methods: GET, POST');
			}
		}
	}
	
	public function json($data){
		header('Content-type: text/json');
		
		$data['costtime'] = 1000 * number_format(microtime(true) - PHP_START_TIME, 6);
		
		echo json_encode($data);
	}
	
	public function jsonp($callback, $data){
		$data['costtime'] = 1000 * number_format(microtime(true) - PHP_START_TIME, 6);
		echo '<script type="text/javascript">document.domain = "'.COOKIE_DOMAIN.'";'.$callback.'('.json_encode($data).');</script>';
	}
	
	public function error($data){
		include template('tips/error');
	}
	
	public function header($str){
		header($str);
	}
	
	public function h404($msg){
		header('HTTP/1.0 404 Not Found');
		exit($msg);
	}
	
	public function redirect($url){
		header('Location: '. $url);
		exit;
	}
}
