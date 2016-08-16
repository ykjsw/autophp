<?php

class AutoController extends BaseController{
	
	public function __construct(){
		
	}
	
	/*
	 * www.autophp.net/auto/?id=0
	 */
	public function autoAdmin($req){
		$cid = 0;
		if(isset($req['id'])){
			$cid = intval($req['id']);
		}
		
		$page = 1;
		if(isset($req['page'])){
			$page = intval($req['page']);
		}
		
		$orderby = 'asc';
		if(isset($req['orderby']) && $req['orderby'] == 'desc'){
			$orderby = 'desc';
		}
		
		//配置是否存在
		$config = wei()->db->select('ap_auto_config', array('id' => $cid));
		if(!$config){
			exit('配置不存在');
		}
		
		//读出字段配置
		$tablefields = array();
		$rows = wei()->db('ap_auto_config_tablefield')->where('config_id = ?', $cid)->orderBy('display_order', 'ASC')->fetchAll();
		
		if($rows){
			foreach($rows as $row){
				$tablefields[$row['field']] = $row;
				$tablefields[$row['field']]['is_virtual'] = $row['auto_type'] == AUTO_FIELD_VIRTUAL ? true : false;
			}
		}
		
		$querydata = array();
		$querydata['id'] = $cid;
		//$querydata['page'] = $page;
		//$querydata['orderby'] = $orderby;

		//contentDB
		if($cid < 2){
			wei()->contentDb = wei()->db;
		}else{

			$dbcfg = explode(' ', $config['db_config']);

			if(!isset($dbcfg[3])){
				exit('DB配置错误');
			}

			wei(array(
				'content.db' => array(
						'driver'    => 'mysql',
						'host'      => $dbcfg[0].':'.$dbcfg[1],
						'dbname'    => $config['db_name'],
						'charset'   => $config['charset'],
						'user'      => $dbcfg[2],
						'password'  => $dbcfg[3],
				)
			));
		}
		
		//列表内容
		$listdb = wei()->contentDb($config['db_table'])->where('1=1');
		
		//是否有富文本 日期
		$has_richtext_field		= false;
		$has_datetime_field		= false;
		$has_timestamp_field	= false;
		
		//看是否需要分组过滤
		$groups = array();
		foreach($tablefields as $field){
			if($field['auto_type'] == AUTO_FIELD_GROUP){
				$group = wei()->autofield->getSelectArray($field['auto_param']);
				
				$queryid = null;
				$queryfield = 't_'.$field['field'];
				if(isset($req[$queryfield])){
					$queryid = trim($req[$queryfield]);
				}else{
					if($group[0][0] != ''){
						$queryid = $group[0][0];
					}
				}
			
				if($queryid !== null){
					$listdb->andWhere($field['field'] . ' = ?', $queryid);
				}
				
				$groups[] = array(
						'name'		=> $field['name'],
						'field'		=> $field['field'],
						'default'	=> $queryid,
						'value'		=> $group
				);
				
				$querydata[$queryfield] = $queryid;
				
				if(isset($queryid)){
					unset($tablefields[$field['field']]);
				}
			}elseif($field['auto_type'] == AUTO_FIELD_DEFAULT){
				$listdb->andWhere($field['field'] . ' = ?', $field['auto_param']);
				unset($tablefields[$field['field']]);
			}elseif($field['auto_type'] == AUTO_FIELD_RICHTEXT){
				$has_richtext_field = true;
			}elseif($field['auto_type'] == AUTO_FIELD_DATETIME || $field['auto_type'] == AUTO_FIELD_DATE){
				$has_datetime_field = true;
			}elseif($field['auto_type'] == AUTO_FIELD_TIMESTAMP){
				$has_timestamp_field = true;
			}
		}
		
		$tablerows = $listdb->orderBy($config['primary_key'], strtoupper($orderby))->fetchAll();
		
		$sql = $listdb->getSql();
		
		$result = array();
		
		$result['title'] = $config['name'];
		$result['config'] = $config;
		$result['tablefield'] = $tablefields;
		$result['has_richtext_field'] = $has_richtext_field;
		$result['has_datetime_field'] = $has_datetime_field;
		$result['has_timestamp_field'] = $has_timestamp_field;
		$result['tablerows'] = $tablerows;
		$result['groups'] = $groups;
		$result['querydata'] = $querydata;
		$result['orderby'] = $orderby;
		$result['newrows'] = wei()->autofield->getFieldHtmlRows($tablefields);
		$result['sql'] = $sql;
		
		$this->render($result);
	}
	
	//http://www.autophp.net/auto/in?auto_id=12
	public function autoDataIn($req){

	}

	// http://www.autophp.net/auto/out?auto_id=12&jsonp=1
	public function autoDataOut($req){

		$isJsonp = false;

		if(isset($req['jsonp'])){
			$callbackFunc = 'callback';
			$isJsonp = true;

			if(isset($req['callback'])){
				$ref = preg_replace('/([\w.]+)/is', '', $req['callback']);
				if($ref === ''){
					$callbackFunc = $req['callback'];
				}
			}
		}

		if(!isset($req['auto_id'])){
			$resp = array('ret' => 1, 'msg' => 'auto id error');

			if($isJsonp){
				$this->jsonp($callbackFunc, $resp);
			}else{
				$this->json($resp);
			}
			return false;
		}

		$auto_id = intval($req['auto_id']);

		$cache_file = wei()->autodata->getCacheFile($auto_id);

		if(!file_exists($cache_file)){
			$ret = wei()->autodata->genCacheFile($auto_id);

			if(!$ret){
				$resp = array('ret' => 2, 'msg' => 'autodata cache file generate fail');

				if($isJsonp){
					$this->jsonp($callbackFunc, $resp);
				}else{
					$this->json($resp);
				}
				return false;
			}
		}

		$resp = wei()->autodata->getAutoData($auto_id, $req);

		if($isJsonp){
			$this->jsonp($callbackFunc, $resp);
		}else{
			$this->json($resp);
		}
	}
	
	
	public function create($req){
		$cid = 0;
		if(isset($req['config_id'])){
			$cid = intval($req['config_id']);
		}

		$config = wei()->db->select('ap_auto_config', array('id' => $cid));
		if(!$config){
			$resp = array('ret' => 1, 'msg' => '配置不存在');
			
			$this->json($resp);
			return false;
		}
		
		if(!isset($req['data'])){
			$resp = array('ret' => 2, 'msg' => '添加数据错误');
			
			$this->json($resp);
			return false;
		}
		
		$create_data = json_decode($req['data'], true);
		if(!$create_data){
			$resp = array('ret' => 3, 'msg' => '添加数据格式不正确');
			
			$this->json($resp);
			return false;
		}
		
		//读出字段配置
		$rows = wei()->db('ap_auto_config_tablefield')->where('config_id = ?', $cid)->fetchAll();
		
		//隐藏字段用默认值
		if($rows){
			foreach($rows as $row){
				if($row['auto_type'] == AUTO_FIELD_DEFAULT){
					$create_data[$row['field']] = $row['auto_param'];
				}
			}
		}
		
		//针对checkbox值的json化处理
		$create_data = array_map(function($value){
			return is_array($value) ? json_encode($value) : trim($value);
		}, $create_data);
		
		wei()->db->insert($config['db_table'], $create_data);
		
		$key_id = wei()->db->lastInsertId();
		
		$new_row = wei()->db->select($config['db_table'], array($config['primary_key'] => $key_id));
		
		//触发回调
		if($config['callback'] !== ''){
			wei()->autodata->callUserFunc($config['callback'], array('act' => 'create', 'data' => $new_row));
		}

		$this->json(array('ret' => 0, 'data' => array()));
	}
	
	public function update($req){
		$cid = 0;
		if(isset($req['config_id'])){
			$cid = intval($req['config_id']);
		}

		$config = wei()->db->select('ap_auto_config', array('id' => $cid));
		if(!$config){
			$resp = array('ret' => 1, 'msg' => '配置不存在');
			
			$this->json($resp);
			return false;
		}
		
		if(!isset($req['data'])){
			$resp = array('ret' => 2, 'msg' => '更新数据错误');
			
			$this->json($resp);
			return false;
		}
		
		$update_data = json_decode($req['data'], true);
		if(!$update_data){
			$resp = array('ret' => 3, 'msg' => '更新数据格式不正确');
			
			$this->json($resp);
			return false;
		}
		
		$db_row_data = wei()->db->select($config['db_table'], array($config['primary_key'] => $update_data[0]['row_id']));
		if(!$db_row_data){
			$resp = array('ret' => 2, 'msg' => '记录不存在');
			
			$this->json($resp);
			return false;
		}
		
		foreach($update_data as $data){
			if(isset($data['row_id']) && isset($data['row_data']) && is_numeric($data['row_id']) && is_array($data['row_data'])){
				
				//针对checkbox值的json化处理
				$data['row_data'] = array_map(function($value){
					return is_array($value) ? json_encode($value) : trim($value);
				}, $data['row_data']);
					
				wei()->db->update(
				    $config['db_table'],
				    $data['row_data'],
				    array($config['primary_key'] => $data['row_id'])
				);
				
				//触发回调
				$db_row_data = wei()->db->select($config['db_table'], array($config['primary_key'] => $data['row_id']));
				if($config['callback'] !== ''){
					wei()->autodata->callUserFunc($config['callback'], array('act' => 'update', 'data' => $db_row_data));
				}
			}
		}
		
		$this->json(array('ret' => 0, 'data' => array()));
	}
	
	public function delete($req){
		$cid = 0;
		if(isset($req['config_id'])){
			$cid = intval($req['config_id']);
		}

		$config = wei()->db->select('ap_auto_config', array('id' => $cid));
		if(!$config){
			$resp = array('ret' => 1, 'msg' => '配置不存在');
			
			$this->json($resp);
			return false;
		}
		
		$delete_data = json_decode($req['data'], true);
		if(!$delete_data){
			$resp = array('ret' => 3, 'msg' => '数据格式不正确');
			
			$this->json($resp);
			return false;
		}
		
		foreach($delete_data as $row_id){
			
			//触发回调
			$db_row_data = wei()->db->select($config['db_table'], array($config['primary_key'] => $row_id));
			
			wei()->db->delete($config['db_table'], array(
				$config['primary_key'] => $row_id
			));
			
			if($config['callback'] !== ''){
				wei()->autodata->callUserFunc($config['callback'], array('act' => 'delete', 'data' => $db_row_data));
			}
		}
		
		$this->json(array('ret' => 0, 'data' => array()));
	}
	
	public function fileUpload($req){
		
		$callbackFunc = 'callback';

		if(isset($req['callback'])){
			$ref = preg_replace('/([\w.]+)/is', '', $req['callback']);
			if($ref === ''){
				$callbackFunc = $req['callback'];
			}
		}
		
		if(!$_FILES){
			$this->jsonp($callbackFunc, array('ret' => 1, 'msg' => '上传发生错误1！'));
			return;
		}
		
		$file = $_FILES['upfile'];
		
		if($file['error'] > 0 || $file['size'] == 0){
			$this->jsonp($callbackFunc, array('ret' => 1, 'msg' => '上传发生错误2！'));
			return;
		}
		
		/*
		wei()->user->auth();
		if(!wei()->user->logined){
			$this->jsonp($callbackFunc, array('ret' => 1, 'msg' => '请先登录！'));
			return;
		}
		$uid = wei()->user->uid;*/
		
		$is_image = 0;
		if(in_array($file['type'], array('image/png', 'image/jpeg', 'image/jpg', 'image/gif'))){
			$is_image = 1;
		}
		
		$date = date('Y-m-d');
		$dir = BASE_PATH .'public/static/upload/'.$date;
		if(!is_dir($dir)){
			mkdir($dir, 0755);
		}
		
		$ret = move_uploaded_file($file['tmp_name'], $dir .'/' . $file['name']);
		
		if(!$ret){
			$this->jsonp($callbackFunc, array('ret' => 1, 'msg' => '文件保存失败！'));
			return;
		}
		
		wei()->db->insert('ap_auto_upload', array(
					'user_id'	=> 1,
					'file_name'	=> $file['name'],
					'file_size'	=> $file['size'],
					'file_type'	=> $file['type'],
					'file_path'	=> $date .'/' . $file['name'],
					'is_image'	=> $is_image,
					'upload_time'	=> time()
		));
		
		$data = array(
			'req'		=> $req,
			'url'		=> 'http://'.SITE_HOST .'/static/upload/'.$date .'/' . $file['name'],
			'path'		=> $date .'/' . $file['name'],
			'type'		=> $file['type'],
			'is_image'	=> $is_image
		);
		
		$this->jsonp($callbackFunc, array('ret' => 0, 'data' => $data));
	}
}