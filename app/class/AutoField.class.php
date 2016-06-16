<?php

class AutoField extends \Wei\Base{
	
	private $notShowClass = array('QQWry', 'Wei\\Base', 'BaseController');
	private $phpFunctions = array();
	
	public function getFieldHtmlRows($tablefields){
		$rows = array();
		
		foreach($tablefields as $tablefield){
			if($tablefield['auto_type'] != AUTO_FIELD_VIRTUAL && $tablefield['auto_type'] != AUTO_FIELD_READ_ONLY){
				$value = $tablefield['auto_type'] == AUTO_FIELD_DEFAULT ? $tablefield['auto_param'] : '';
				$rows[] = array(
					$tablefield['name'],
					$this->getFieldHtml($tablefield['auto_type'], $tablefield['auto_param'], $tablefield['field'], $value)
				);
			}
		}
		
		return $rows;
	}
	
	public function getFieldHtml($type, $param, $field, $value, $row = array(), $row_id = 0){
		$code = '';
		switch ($type) {
			case AUTO_FIELD_RICHTEXT:
				$code = '<textarea data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="auto-field-value auto-richtext" rows="3" style="width:400px;height:200px;">'.htmlspecialchars($value).'</textarea>';
				break;
			case AUTO_FIELD_TEXTAREA:
				$code = '<textarea data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control auto-field-value" cols="40" rows="3">'.htmlspecialchars($value).'</textarea>';
				break;
			case AUTO_FIELD_TEXTAREA_WITH_UPLOAD:
				$code = '<textarea data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control auto-field-value auto-file-upload" rows="3">'.htmlspecialchars($value).'</textarea>';
				$code .= '<form action="/auto/upload" method="post" enctype="multipart/form-data" target="target-iframe">';
				$code .= '<input type="hidden" name="callback" value="parent.auto.uploaded" />';
				$code .= '<input type="hidden" name="field" value="'.$field.'" />';
				$code .= '<input type="hidden" name="row_id" value="'.$row_id.'" />';
				$code .= '<input type="hidden" name="act_id" value="2" />';
				$code .= '<input type="file" name="upfile" style="margin:5px 0;display: inline-block;" /> ';
				$code .= '<input type="submit" value="上传" class="btn btn-info btn-xs" />';
				$code .= '</form>';
				break;
			case AUTO_FIELD_JSON:
				$code = '<textarea data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control auto-field-value auto-json" rows="3">'.htmlspecialchars($value).'</textarea>';
				$code .= '<div class="json-struct" data-field="'.$field.'" data-row-id="'.$row_id.'">';
				if($value){
					$arr = json_decode($value, true);
					if(!$arr){
						$code .= '<span class="red">错误的JSON字符串</span>';
					}
				}
				$code .= '</div>';
				break;
			case AUTO_FIELD_SELECT:
				$arr = $this->getSelectArray($param, $row);
				$code .= '<select data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value">';
				foreach($arr as $val){
					$slt = $value == $val[0] ? 'selected' : '';
					$code .= '<option '.$slt.' value="'.addslashes($val[0]).'">'.$val[1].'</option>';
				}
				$code .= '</select>';
				break;
			case AUTO_FIELD_READ_ONLY:
				$code = '<span data-field="'.$field.'">'.$value.'</span>';
				break;
			case AUTO_FIELD_IMAGE:
				$code .= '<img src="'.$value.'" data-field="'.$field.'" data-row-id="'.$row_id.'" class="auto-field-virtual-img" />';
				$code .= '<input data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="text" value="'.addslashes($value).'" />';
				$code .= '<form action="/auto/upload" method="post" enctype="multipart/form-data" target="target-iframe">';
				$code .= '<input type="hidden" name="callback" value="parent.auto.uploaded" />';
				$code .= '<input type="hidden" name="field" value="'.$field.'" />';
				$code .= '<input type="hidden" name="row_id" value="'.$row_id.'" />';
				$code .= '<input type="hidden" name="act_id" value="1" />';
				$code .= '<input type="file" name="upfile" style="margin:5px 0;display: inline-block;" /> ';
				$code .= '<input type="submit" value="上传" class="btn btn-info btn-xs" />';
				$code .= '</form>';
				break;
			case AUTO_FIELD_MULTI_SELECT:
				if($value){
					$vals = json_decode($value, true);
				}else{
					$vals = array();
				}
				$arr = $this->getSelectArray($param, $row);
				$html = array();
				foreach($arr as $val){
					$ckd = in_array($val[0], $vals) ? 'checked="checked"' : '';
					$html[] = '<label><input class="auto-field-value" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" type="checkbox" '.$ckd.' value="'.addslashes($val[0]).'" />'.$val[1].'</label>';
				}
				$code = implode('<br />', $html);
				break;
			case AUTO_FIELD_VIRTUAL:
				$code = '<span class="red">虚拟字段参数错误</span>';
				$tmps = explode(':', $param, 2);
				if(count($tmps) === 2){
					$type = trim(strtolower($tmps[0]));
					$val = $tmps[1];
					if(in_array($type, array('text', 'html', 'php', 'sql'))){
						if($type === 'text' || $type === 'html' || $type === 'sql'){
							
							//处理可能出现的php变量
							preg_match_all('/\$([a-zA-Z0-9_]+)/is', $val, $m);
							if($m[1]){
								foreach($m[1] as $key){
									if(isset($row[$key])){
										$val = str_replace('$'.$key, $row[$key], $val);
									}
								}
							}
							
							if($type === 'text'){
								$code = htmlspecialchars($val);
							}elseif($type === 'html'){
								$code = $val;
							}else{
								$result = wei()->db->fetch($val);
								if($result){
									$code = array_shift($result);
								}else{
									$code = '<span class="red">记录不存在</span>';
								}
							}
						}elseif($type === 'php'){
							$func = function() use ($val, $row){
								extract($row);
								return eval($val);
							};
							$code = $func();
						}
					}
				}
				break;
			case AUTO_FIELD_IP:
				$code = '<input style="width:120px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="text" value="'.addslashes($value).'" />';
				try{
					if($value){
						$address = wei()->qqwry->query($value);
						$code .= '<span class="auto-field-virtual" data-field="'.$field.'" data-row-id="'.$row_id.'">'.$address.'</span>';
					}
				}catch(Exception $e){
					$code .= '<span class="red">IP地址无效</span>';
				}
				break;
			case AUTO_FIELD_QQ:
				$code = '<input style="width:90px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="text" value="'.addslashes($value).'" />';
				if($value){
					$code .= '<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='.$value.'&site=qq&menu=yes"><img style="margin-top:2px;" border="0" src="http://wpa.qq.com/pa?p=2:'.$value.':51" alt="点击这里发消息" title="点击这里发消息"/></a>';
				}
				break;
			case AUTO_FIELD_FILESIZE:
				$code = '<input style="width:90px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="text" value="'.addslashes($value).'" />';
				if(is_numeric($value)){
					$code .= '<span class="auto-field-virtual" data-field="'.$field.'" data-row-id="'.$row_id.'">'.format_size($value).'</span>';
				}
				break;
			case AUTO_FIELD_TIMESTAMP:
				$code = '<input style="width:95px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value auto-timestamp" type="text" value="'.addslashes($value).'" />';
				$code .= '<span class="auto-field-virtual" data-field="'.$field.'" data-row-id="'.$row_id.'">'.($value ? date('Y-m-d H:i:s', $value) : '').'</span>';
				break;
			case AUTO_FIELD_DATE:
				$code = '<input style="width:85px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value auto-date" type="text" value="'.addslashes($value).'" />';
				break;
			case AUTO_FIELD_DATETIME:
				$code = '<input style="width:140px;" data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value auto-datetime" type="text" value="'.addslashes($value).'" />';
				break;
			case AUTO_FIELD_PASSWORD:
				$code = '<input data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="password" value="'.addslashes($value).'" />';
				break;
			case AUTO_FIELD_TEXT:
			default:
				$code = '<input data-changed="false" data-field="'.$field.'" data-row-id="'.$row_id.'" class="form-control input-sm auto-field-value" type="text" value="'.addslashes($value).'" />';
				break;
		}
		
		return $code;
	}
	
	/*
	 * 根据分组或选择框参数生成数组
	 */
	public function getSelectArray($param, $row = array()){
		
		$parts = explode(':', $param, 2);
		
		$arr = array();
		
		//如果是sql语法
		if($parts[0] === 'sql'){
				
			preg_match_all('/\$([a-zA-Z0-9_]+)/is', $parts[1], $m);
			if($m[1]){
				foreach($m[1] as $key){
					if(isset($row[$key])){
						$parts[1] = str_replace('$'.$key, $row[$key], $parts[1]);
					}
				}
			}
			
			$rows = wei()->db->fetchAll($parts[1]);
			if($rows){
				foreach($rows as $row){
					$id = array_shift($row);
					$val = array_shift($row);
					
					$arr[] = array($id, $val);
				}
			}
		}elseif($parts[0] === 'phpfunc'){
			$arr = $this->getPHPFunctions();
		}elseif($parts[0] === 'enum'){
			$tmps = explode('|', $parts[1]);
			foreach($tmps as $tmp){
				list($id, $val) = explode(':', $tmp, 2);
				if($val){
					$arr[] = array($id, $val);
				}
			}
		}
		
		return $arr;
	}

	public function getPHPFunctions(){
		
		if($this->phpFunctions){
			return $this->phpFunctions;
		}

		$file_files = include(BASE_PATH . 'vendor/composer/autoload_files.php');
		$file_classes = include(BASE_PATH . 'vendor/composer/autoload_classmap.php');
		
		$vendor_dir = str_replace('public/../', '', BASE_PATH . 'vendor/');
		
		$all = array();
		
		$functions = array();
		foreach($file_files as $file){
			if(!strstr($file, $vendor_dir)){
				$php_code = file_get_contents($file);
				
				$tokens = token_get_all($php_code);
				$count = count($tokens);
				for ($i = 2; $i < $count; $i++) {
				    if (   $tokens[$i - 2][0] == T_FUNCTION
				        && $tokens[$i - 1][0] == T_WHITESPACE
				        && $tokens[$i][0] == T_STRING) {
				
				        $function_name = $tokens[$i][1];
				        $functions[] = $function_name;
				    }
				}
			}
		}
		
		$classes = array();
		foreach($file_classes as $file){
			if(!strstr($file, $vendor_dir)){
				
				$php_code = file_get_contents($file);
				
				$tokens = token_get_all($php_code);
				$count = count($tokens);
				for ($i = 2; $i < $count; $i++) {
				    if (   $tokens[$i - 2][0] == T_CLASS
				        && $tokens[$i - 1][0] == T_WHITESPACE
				        && $tokens[$i][0] == T_STRING) {
				
				        $class_name = trim($tokens[$i][1]);
						
						if(!in_array($class_name, $this->notShowClass)){
				        
					        $class = new ReflectionClass($class_name);
							$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
							
							foreach($methods as $method){
								if(substr($method->name, 0, 2) !== '__'){
									if(!in_array($method->class, $this->notShowClass)){
					        			$classes[] = $method->class . '::' . $method->name;
									}
								}
					        }
						}
				    }
				}
			}
		}
		
		$all = array_merge($functions, $classes);
		
		$newArr = array();
		
		foreach($all as $val){
			$newArr[] = array($val, $val);
		}
		
		array_unshift($newArr, array('', '无'));
		
		$this->phpFunctions = $newArr;
		
		return $newArr;
	}

	private function filterArray($arr, $trim = false, $empty = false, $remove = array()){
	    $rarr = array();
	    foreach($arr as $el){
	        if($trim)
	            $el = trim($el);
	            
	        if($empty == true){
	            if(!empty($el) && !in_array($el, $remove))
	                $rarr[] = $el;
	        }else if(!in_array($el, $remove))
	            $rarr[] = $el;
	    }
	    return $rarr;
	}
}