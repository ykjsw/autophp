<?php

class HomeController extends BaseController{
	
	public function __construct(){
		
	}
	
	
	public function index($req){
		
		echo 'AutoPHP.net';
	}
	
	public function admin($req){
		
		$result = array();
		
		$menu = '';
		
		$rows = wei()->db('ap_auto_menu')->where('is_valid = 1')->orderBy('display_order', 'ASC')->fetchAll();
		if($rows){
			
			$menu = html_ordered_menu($rows);
		}
		
		$result['menu'] = $menu;
		
		
		$this->render($result);
	}
}