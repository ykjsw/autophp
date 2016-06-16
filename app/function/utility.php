<?php

function contentTrim($html){
	$str = strip_tags($html);
	$str = str_replace('\'', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('\\', '', $str);
	$str = str_replace('<', '', $str);
	$str = str_replace('>', '', $str);
	
	return $str;
}

function htmlTransfor($html) {

	preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

	$searchs[] = '<';
	$replaces[] = '&lt;';
	$searchs[] = '>';
	$replaces[] = '&gt;';

	if($ms[1]) {
		$allowtags = 'pre|code|img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
		$ms[1] = array_unique($ms[1]);
		foreach ($ms[1] as $value) {
			$searchs[] = "&lt;".$value."&gt;";

			$value = str_replace('&amp;', '_uch_tmp_str_', $value);
			$value = htmlspecialchars($value);
			$value = str_replace('_uch_tmp_str_', '&amp;', $value);

			$value = str_replace(array('\\','/*'), array('.','/.'), $value);
			$skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
					'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
					'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
					'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
					'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
					'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
					'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
					'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
					'onsubmit','onunload','javascript','script','eval','behaviour','expression');
			$skipstr = implode('|', $skipkeys);
			$value = preg_replace(array("/($skipstr)/i"), '.', $value);
			if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
				$value = '';
			}
			$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
		}
	}
	$html = str_replace($searchs, $replaces, $html);

	return $html;
}

function pretty_time($time){
	if($time < 60){
		return $time .'秒';
	}
	
	if($time < 3600){
		$min = floor($time / 60);
		$sec = $time % 60;
		return $min.'分钟 ' .$sec.'秒';
	}
	
	$hour = floor($time / 3600);
	$min = floor(($time - 3600 * $hour) / 60);
	return $hour .'小时' . $min.'分钟';
}

//一个中文算两个字符，一个英文和其他算一个
function cn_strlen($string){
	$n = preg_match_all('/[\x{4e00}-\x{9fa5}]/u', $string);
	$cn_len = $n * 2;
	
	$string = preg_replace('/[\x{4e00}-\x{9fa5}]+/u', '', $string);
	$en_len = strlen($string);
	
	return $cn_len + $en_len;
}

//是否全是汉字英文数字空格
function is_good_word($string){
	$string = preg_replace('/[\x{4e00}-\x{9fa5}]+/u', '', $string);
	$string = preg_replace('/(\w+)/u', '', $string);
	$string = trim($string);
	
	return $string === '';
}

function check_captcha($string){
	if(isset($_COOKIE['jser_captcha'])){
		if($_COOKIE['jser_captcha'] === md5($string)){
			return true;
		}
	}
	
	return false;
}

function login_page(){
	$url = 'http://'. $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];
	$login_url = '/auth/login?url=' . urlencode($url);
	
	header('Location: ' .$login_url);
	exit;
}

function is_jser_url($url){
	$arr = parse_url($url);
	
	if($arr){
		if(strstr($arr['host'], 'jser.com')){
			return true;
		}
	}
	
	return false;
}

function get_mail_content($name, $vars){
	$msg = file_get_contents(STATIC_PATH .'email/' .$name.'.html');
	foreach($vars as $k => $v){
		$msg = str_replace('{{'.$k.'}}', $v, $msg);
	}
	return $msg;
}

function send_email($email, $title, $body){
	$mail = new Nette\Mail\Message;
	$mail->setFrom('Jser <system@jser.com>')
    ->addTo($email)
    ->setSubject($title)
    ->setHTMLBody($body);
	
	$mailer = new Nette\Mail\SmtpMailer(array(
	        'host' => 'smtp.exmail.qq.com',
	        'username' => 'system@jser.com',
	        'password' => 'dfjfj2@@@!X',
	        'secure' => 'ssl',
	));
	$mailer->send($mail);
}

function authcode($string, $operation = 'DECODE', $expiry = 0) {
	$ckey_length = 4;
	$key = 'B0BA633DCA0CD5CDCA99AD1AB8CF773D';
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function getPageHTML($url, $currentPage, $pageCount, $neighborLength = 3){

	if($pageCount < 2 ){
		return "";
	}

	$start = $currentPage  - $neighborLength;
	$end = $currentPage + $neighborLength;

	if($start <= 4){
		$start = 2;
	}

	$start = $start > 1 ? $start : 2;

	$end = 2* $neighborLength - ($start < $currentPage? ($currentPage - $start ) : 0 ) + $currentPage;
	$end = $end < $pageCount ? $end : ( $pageCount - 1 );

	$str = "";
	//上一页
	$str.= $currentPage == 1 ? '<li><a class="disabled" href="javascript:void(0);">上一页</a></li>' : '<li><a href="' . str_replace("{page}", $currentPage - 1, $url) . '">上一页</a></li>';

	//第一页
	$str.= $currentPage == 1 ? "" : '<li><a href="' . str_replace("{page}", 1, $url) . '">1</a></li>';

	//左邻居
	if( $start != 2 )
	$str.= '<li><span class="page-break">...</span></li>';
	for($i = $start; $i < $currentPage; $i++){
		$str.=  '<li><a href="' . str_replace("{page}", $i, $url) . '">' . $i . '</a></li>';
	}

	//当前页
	$str.= '<li class="active"><span>' . $currentPage . '</span><li>';

	//右邻居
	for($i = $currentPage + 1; $i < $end + 1; $i++){
		$str.=  '<li><a href="' . str_replace("{page}", $i, $url) . '">' . $i . '</a></li>';
	}

	if( $end != $pageCount - 1 )
	$str.= '<li><span>...</span></li>';

	//最后一页
	$str.= $currentPage != $pageCount  ? '<li><a href="' . str_replace("{page}", $pageCount, $url) . '">' . $pageCount . '</a></li>' : '';

	//下一页
	$str.= $currentPage != $pageCount  ? '<li><a href="' .  str_replace("{page}", $currentPage + 1, $url) . '">下一页</a></li>' : '<li><span>下一页</span><li>';

	//输入框跳转
	//$str.= '<span class="page-skip"> 到第<input type="text" value="' . $currentPage . '" maxlength="3">页<button value="go" onclick="var a=parseInt($(this).parent().find(\'input[type=text]\').val(),10);a=(!!a&&a>0&&a<='. $pageCount .')?a:1;window.location.href=\''.str_replace("{page}", '\'+a+\'', $url).'\'">确定</button></span>';

	return '<ul class="pagination pagination-sm no-margin pull-right">'.$str.'</ul>';
}

function check_ip($ip) {

	$ip = trim($ip);
	$pt = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';

	if ( preg_match($pt, $ip) === 1 ) {
		return true;
	}

	return false;
}

function qvia2ip($qvia) {

	if ( strlen($qvia) != 40 ) {
		return false;
	}

	$ips = array(hexdec(substr($qvia,0,2)), hexdec(substr($qvia,2,2)), hexdec(substr($qvia,4,2)), hexdec(substr($qvia,6,2)));
	$ipbin = pack('CCCC', $ips[0], $ips[1], $ips[2], $ips[3]);
	$m = md5('QV^10#Prefix'.$ipbin.'QV10$Suffix%');

	if ( $m == substr($qvia, 8) ) {
		return implode('.', $ips);
	} else {
		return false;
	}
}

function get_client_ip() {

	if (isset($_SERVER['HTTP_QVIA']) ) {
		$ip = qvia2ip($_SERVER['HTTP_QVIA']);

		if ($ip) {
			return $ip;
		}
	}

	if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) ) {
		return check_ip($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '0.0.0.0';
	}

	if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
		$ip = strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
		do {
			$tmpIp = explode('.', $ip);
			//-------------------
			// skip private ip ranges
			//-------------------
			// 10.0.0.0 - 10.255.255.255
			// 172.16.0.0 - 172.31.255.255
			// 192.168.0.0 - 192.168.255.255
			// 127.0.0.1, 255.255.255.255, 0.0.0.0
			//-------------------
			if(is_array($tmpIp) && count($tmpIp) == 4){
				if (($tmpIp[0] != 10) && ($tmpIp[0] != 172) && ($tmpIp[0] != 192) && ($tmpIp[0] != 127) && ($tmpIp[0] != 255) && ($tmpIp[0] != 0) ){
					return $ip;
				}
				if(($tmpIp[0] == 172) && ($tmpIp[1] < 16 || $tmpIp[1] > 31)){
					return $ip;
				}
				if(($tmpIp[0] == 192) && ($tmpIp[1] != 168)){
					return $ip;
				}
				if (($tmpIp[0] == 127) && ($ip != '127.0.0.1')){
					return $ip;
				}
				if ($tmpIp[0] == 255 && ($ip != '255.255.255.255'))	{
					return $ip;
				}
				if ($tmpIp[0] == 0 && ($ip != '0.0.0.0')){
					return $ip;
				}
			}
		} while ( $ip = strtok(',') );
	}

	if ( isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER']) ) {
		return check_ip($_SERVER['HTTP_PROXY_USER']) ? $_SERVER['HTTP_PROXY_USER'] : '0.0.0.0';
	}

	if ( isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) ) {
		return check_ip($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	} else {
		return '0.0.0.0';
	}
}

function get_compressed_files($file){
	//use Symfony\Component\Finder\Finder;
	
	$dir = STATIC_PATH .'tmp/'.random(6);
	if(!is_dir($dir)){
		mkdir($dir, 0777);
	}
	
	$specificDirectory = new Mmoreram\Extractor\Filesystem\SpecificDirectory($dir);
	$extensionResolver = new Mmoreram\Extractor\Resolver\ExtensionResolver;
	$extractor = new Mmoreram\Extractor\Extractor(
	    $specificDirectory,
	    $extensionResolver
	);
	
	$files = $extractor->extractFromFile($file);
	
	$list = array();
	if($files){
		foreach ($files as $file) {
		
			if(is_file($file->getRealpath())){
				$list[] = $file->getRealpath();
			}
		}
	}
	
	return array(
		'path'	=> $dir,
		'list'	=> $list
	);
}

function remove_dir($directory){
	foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) { 
            remove_dir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}

function is_mobile_client(){
	
	if(!isset($_SERVER['HTTP_USER_AGENT'])){
		return false;
	}
	
	$tablet_browser = 0;
	$mobile_browser = 0;
	 
	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $tablet_browser++;
	}
	 
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $mobile_browser++;
	}
	 
	if ((isset($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	    $mobile_browser++;
	}
	 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda ','xda-');
	 
	if (in_array($mobile_ua,$mobile_agents)) {
	    $mobile_browser++;
	}
	 
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
	    $mobile_browser++;
	    //Check for tablets on opera mini alternative headers
	    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
	    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
	      $tablet_browser++;
	    }
	}
	 
	if ($tablet_browser > 0 || $mobile_browser > 0) {
	   return true;
	}
	
	return false;  
}

function template($file) {
	$tplfile = BASE_PATH. 'app/view/'.$file.'.html';
	$objfile = BASE_PATH. 'cache/template/'.str_replace('/', '_', $file).'.tpl.php';

	if(@filemtime($tplfile) > @filemtime($objfile) ) {
		parse_template($file);
	}
	return $objfile;
}

function parse_template($file) {
	$nest = 5;
	$tplfile = BASE_PATH. 'app/view/'.$file.'.html';
	$objfile = BASE_PATH. 'cache/template/'.str_replace('/', '_', $file).'.tpl.php';

	if(!$fp = fopen($tplfile, 'r')) {
		exit("Current template file not found or have no access!");
	}

	$template = fread($fp, filesize($tplfile));
	fclose($fp);

	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);

	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
	$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);

	$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "<? include template('\\1'); ?>", $template);
	$template = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", "<? include template(\\1); ?>", $template);
	$template = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? \\1 ?>','')", $template);
	$template = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? echo \\1; ?>','')", $template);
	$template = preg_replace("/[\n\r\t]*\{elseif\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? } elseif(\\1) { ?>','')", $template);
	$template = preg_replace("/[\n\r\t]*\{else\}[\n\r\t]*/is", "<? } else { ?>", $template);

	for($i = 0; $i < $nest; $i++) {
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('\n<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\n\\3\n<? } } ?>\n')", $template);
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('\n<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\n\\4\n<? } } ?>\n')", $template);
		$template = preg_replace("/[\n\r\t]*\{if\s+(.+?)\}[\n\r]*(.+?)[\n\r]*\{\/if\}[\n\r\t]*/ies", "stripvtags('<? if(\\1) { ?>','\\2<? } ?>')", $template);
	}

	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
	
	//$template = str_replace("\r", '', $template);
	//$template = str_replace("\n", '', $template);

	if(!$fp = fopen($objfile, 'w+')) {
		exit("Directory not found or have no access!");
	}

	//$template = preg_replace("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/e", "transamp('\\0')", $template);
	//$template = preg_replace("/\<script[^\>]*?src=\"(.+?)\".*?\>\s*\<\/script\>/ise", "stripscriptamp('\\1')", $template);

	flock($fp, 2);
	fwrite($fp, $template);
	fclose($fp);
}

function transamp($str) {
	//$str = str_replace('&', '&amp;', $str);
	$str = str_replace('&amp;amp;', '&amp;', $str);
	$str = str_replace('\"', '"', $str);
	return $str;
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

function format_size($size) {
    $mod = 1024;
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }
    return round($size, 2) . ' ' . $units[$i];
}