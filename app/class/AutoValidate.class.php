<?php

class AutoValidate extends \Wei\Base{
	
	public function getAllValidation(){
		return array(
			'isAlnum' => '必须只由字母(a-z)和数字(0-9)组成',
			'isAlpha' => '必须只由字母(a-z)组成',
			'isDecimal' => '必须为小数',
			'isDigit' => '必须只由数字组成',
			'isEndsWith' => '必须以指定字符串结尾',
			'isIn' => '必须在指定的数组中',
			'isNaturalNumber' => '必须为自然数(大于等于0的整数)',
			'isNumber' => '必须为有效数字',
			'isPositiveInteger' => '必须为正整数(大于0的整数)',
			'isRegex' => '必须匹配指定的正则表达式',
			'isStartsWith' => '必须以指定字符串开头',

			'isLength' => '必须为指定的长度,或在指定的长度范围内',
			'isCharLength' => '字符数必须为指定的数值',
			'isMinLength' => '必须大于等于指定长度',
			'isMaxLength' => '必须小于等于指定长度',

			'isEqualTo' => '必须等于(==)指定的值',
			'isGreaterThan' => '必须大于(>)指定的值',
			'isGreaterThanOrEqual' => '必须大于等于(>=)指定的值',
			'isLessThan' => '必须小于(<)指定的值',
			'isLessThanOrEqual' => '必须小于等于(<=)指定的值',
			'isBetween' => '必须在指定的两个值之间($min < $input < $max)',

			'isDate' => '必须为合法的日期',
			'isDateTime' => '必须为合法的日期时间',
			'isTime' => '必须为合法的时间',
			'isEmail' => '必须为有效的邮箱地址',
			'isUrl' => '必须为有效的URL地址',

			'isChinese' => '必须只由中文组成',
			'isIdCardCn' => '必须为有效的中国身份证',
			'isPhoneCn' => '必须为有效的电话号码',
			'isPostcodeCn' => '必须为有效的邮政编码',
			'isPlateNumberCn' => '必须为有效的中国车牌号码',
			'isQQ' => '必须为有效的QQ号码',
			'isMobileCn' => '必须为有效的手机号码',

			'isRecordExists' => '表必须存在指定的记录',

			'isColor' => '必须为有效的十六进制颜色'
		);
	}

	public function getPhpCode($vals){
		
		$types = $this->getAllValidation();
		
		$call_val = 'array()';
		
		if($vals['args'] === ''){
			$vals['args'] = array();
		}else{
			$vals['args'] = explode(',', $vals['args']);
		}
		
		if($vals['query_name']){
			array_unshift($vals['args'], 'auto_php_req_ph');
			$call_val = str_replace('\'auto_php_req_ph\'', '$params[\''.$vals['query_name'].'\']', var_export($vals['args'], true));
		}
		
		$code = '';
		
		if($vals['check_func'] !== 'useUserFunc'){
			
			$code .= 'if(isset($params[\''.$vals['query_name'].'\'])){' . "\r\n";
			$code .= "\t".'if(!call_user_func_array(array(wei(), \''.$vals['check_func'].'\'), '.$call_val.')){' ."\r\n";
			$code .= "\t\treturn array('ret' => 10, 'msg' => '".$vals['error_msg']."');\r\n";
			$code .= "\t}\r\n";
			$code .= "\t".'}else{' . "\r\n";
			
			if($vals['is_required'] == 1){
				$code .= "\t\treturn array('ret' => 10, 'msg' => '".$vals['error_msg']."');\r\n";
			}else{
				if($vals['default_value']){
					$code .= "\t" .'$params[\''.$vals['query_name'].'\'] = \''. $vals['default_value'] . "';\r\n";
				}
			}
			
			$code .= "\t}";
			
			$code .= "\r\n\r\n";
		}else{
			if($vals['user_check_func'] !== ''){
				if(strstr($vals['user_check_func'], '::')){
					list($class, $method) = explode('::', $vals['user_check_func']);
					if(class_exists($class, true) && method_exists(new $class, $method)){
						$code .= 'if(!call_user_func_array(array(\''.$class.'\', \''.$method.'\'), '.var_export($vals['args'], true).')){' ."\r\n";
						$code .= "\treturn array('ret' => 20, 'msg' => '". $vals['error_msg'] ."');\r\n";
						$code .= "}";
					}
				}elseif(function_exists($vals['user_check_func'])){
					$code .= 'if(!call_user_func_array(\''.$vals['user_check_func'].'\', '.var_export($vals['args'], true).')){' ."\r\n";
					$code .= "\treturn array('ret' => 30, 'msg' => '" . $vals['error_msg'] ."');\r\n";
					$code .= "}";
				}
			}
		}

		return $code;
	}
}