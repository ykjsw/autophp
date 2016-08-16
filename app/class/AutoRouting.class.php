<?php

class AutoRouting extends \Wei\Base{

	public function callGenCache($act, $row){
		$this->genCacheFile();
	}

	public function genCacheFile(){
		$rows = wei()->db('ap_auto_routing')->fetchAll();
		
		if($rows){
			
			$ruleFile = BASE_PATH . 'cache/routing_rule.php';
			
			$code = '<?php ' . "\r\n\r\n";
			$code .= 'return array(';
			
			foreach($rows as $row){
				
				if($row['method'] === ''){
					$row['method'] = 'GET';
				}
				
				if($row['host'] === ''){
					$row['host'] = SITE_HOST;
				}
				
				if($row['path'] === '' || $row['path']{0} !== '/'){
					continue;
				}
				
				if($row['handler_param'] === ''){
					continue;
				}
				
				$code .= 'array(';
				if($row['method'] === 'ALL'){
					$code .= '\'method\' => array(\'GET\', \'POST\'),';
				}else{
					$code .= '\'method\' => \'' . $row['method'] .'\',';
				}
				$code .= '\'path\' => \'' . $row['host'].$row['path'] .'\',';
				
				$code .= '\'handler\' => function($query){';
				
				if($row['handler_type'] == AUTO_ROUTING_CLASSMETHOD){
					if($row['handler_param']){
						if(strstr($row['handler_param'], '::')){
							list($class, $method) = explode('::', $row['handler_param']);
							
							if(class_exists($class)){
								$obj = new $class;
								
								if(method_exists($obj, $method)){
									$code .= '(new '.$class.')->'.$method.'($query);';
								}								
							}
						}else{
							if(function_exists($row['handler_param'])){
								$code .= $row['handler_param'] .'($query);';
							}
						}
					}
				}elseif($row['handler_type'] == AUTO_ROUTING_FILE){
					if($row['handler_param'] && strstr($row['handler_param'], ':')){
						$tmps = explode(':', $row['handler_param'], 2);
						$filename = BASE_PATH . 'public/static/upload/' . $tmps[1];
						if($tmps[1] && file_exists($filename)){
							$code .= 'header(\'Content-Type: '.$tmps[0].'\');';
							$code .= 'readfile(\''.$filename.'\');';
						}else{
							$code .= 'echo \'file error\';';
						}
					}
				}elseif($row['handler_type'] == AUTO_ROUTING_TEXT){
					$code .= 'echo \'' . addslashes($row['handler_param']).'\';';
				}elseif($row['handler_type'] == AUTO_ROUTING_REDIRECT_301){
					if(!strstr($row['handler_param'], 'http://') && !strstr($row['handler_param'], 'https://')){
						$code .= 'echo \'redirect url error\';';
					}else{
						$code .= 'header(\'Location: '.$row['handler_param'].'\', TRUE, 301);';
					}
				}elseif($row['handler_type'] == AUTO_ROUTING_REDIRECT_302){
					if(!strstr($row['handler_param'], 'http://') && !strstr($row['handler_param'], 'https://')){
						$code .= 'echo \'redirect url error\';';
					}else{
						$code .= 'header(\'Location: '.$row['handler_param'].'\', TRUE, 302);';
					}
				}
				
				$code .= '}';
				
				$code .= '),';
			}
			
			$code .= '); ?>';
			
			file_put_contents($ruleFile, $code);
		}
	}
}
	