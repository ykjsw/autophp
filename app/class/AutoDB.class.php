<?php

class AutoDB{
	
	public function genDbCacheFile($id){
		
		
		return true;
	}
	
	public function genAllDbCacheFile($a = array()){
		
		
		return true;
	}
	
	public function getDbConfig($id){
		$cacheFile = BASE_PATH . 'cache/dbconfig/'.$id .'.php';
		
		if(!file_exists($cacheFile)){
			$ret = $this->genDbCacheFile($id);
			
			if(!$ret){
				return false;
			}
		}
		
		return include($cacheFile);
	}
}
