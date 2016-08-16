<?php

class User extends \Wei\Base{
	
	const AUTH_COOKIE = 'auto_token';
	const OAUTH_COOKIE = 'auto_oauth';
	
	private $table = 'ap_auto_user';
	
	public $logined	= false;
	public $uid = 0;
	public $username = '';
	public $userinfo = array();
	public $openid = '';
	public $oauthinfo = array();
	
	public function __construct(){
		
	}
	
	public function auth(){
		if(isset($_COOKIE[self::AUTH_COOKIE])){
			list($uid, $pwd, $time) = explode('|', authcode($_COOKIE[self::AUTH_COOKIE], 'DECODE'));
			
			if($uid && $pwd && $time){
				$record = wei()->db($this->table)->where('id=?', $uid)->find();
				if($record){
					$row = $record->toArray();
					
					if($row['passwd'] === $pwd){
						
						$row['avatar'] = $row['avatar'] ? 'http://'.STATIC_FILE_HOST.'/avatar/' . $row['avatar'] : 'http://'.STATIC_FILE_HOST.'/img/nophoto.png';
						$row['avatar_small'] = str_replace('_200.', '_100.', $row['avatar']);
						
						$this->userinfo = $row;
						$this->logined = true;
						$this->uid = intval($uid);
						$this->username = $row['username'];
						
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	public function oauth(){
		if(isset($_COOKIE[self::OAUTH_COOKIE])){
			list($platform, $openid) = explode('|', $_COOKIE[self::OAUTH_COOKIE]);
			
			if($platform && $openid){
				$array = wei()->db->fetch('SELECT * FROM jser_oauthlogin WHERE `platform` = \''.$platform.'\' AND `openid` = \''.$openid.'\';');
				
				if($array){
					$this->openid = $openid;
					$this->oauthinfo = json_decode($array['userinfo'], true);
					$this->oauthinfo['platform'] = $platform;
				
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function hashPassword($password, $salt){
		return md5($salt.'~~'.md5($password));
	}
	
	public function checkPassword($password, $hashedPassword, $salt){
		return $hashedPassword === $this->hashPassword($password, $salt);
	}
	
	public function createUser($data){
		
		$salt = random(5);
		
		$data = array(
			'name'			=> $data['name'],
			'email'			=> $data['email'],
			'salt'			=> $salt,
			'passwd'		=> $this->hashPassword($data['password'], $salt),
			'register_ip'	=> $data['ip'],
			'create_time'	=> time()
		);
		
		wei()->db->insert($this->table, $data);
	}
	
	public function checkUserName($name){
		
		$strs = array(';', '\'', '"', ' ', '\\', '/', '<', '>', '$', '!', '@', '#', '&', '*', '(', ')', '{', '}');
		foreach($strs as $str){
			if(strstr($name, $str)){
				return false;
			}
		}
		
		return true;
	}
	
	public function getUserInfoBatch($uids){
		
		$userstr = implode(',', $uids);
		$users = wei()->db->fetchAll('SELECT id, name, email, avatar, points, thread_count, reply_count, create_time FROM '. $this->table . ' WHERE id IN ('.$userstr.');');
		
		$users_obj = array();
		foreach($users as $user){
			$users_obj[$user['id']] = $user;
			$users_obj[$user['id']]['avatar'] = $user['avatar'] ? 'http://'.STATIC_FILE_HOST.'/avatar/'.$user['avatar'] : 'http://'.STATIC_FILE_HOST.'/img/nophoto.png';
			$users_obj[$user['id']]['avatar_small'] = str_replace('_200.', '_100.', $users_obj[$user['id']]['avatar']);
		}
		
		return $users_obj;
	}
	
	public function getUserInfo($uid){
		$record = wei()->db->find($this->table, array('id' => $uid));
		if($record){
			$row = $record->toArray();
			
			$row['avatar'] = $row['avatar'] ? 'http://'.STATIC_FILE_HOST.'/avatar/'.$row['avatar'] : 'http://'.STATIC_FILE_HOST.'/img/nophoto.png';
			$row['avatar_small'] = str_replace('_200.', '_100.', $row['avatar']);
			
			return $row;
		}
		
		return false;
	}
	
	public function getUserList($q = null, $page = 1){
		$db = wei()->db($this->table);
		
		if($q){
			$db->where('name LIKE ?', '%'.$q.'%');
		}
		
		$count = $db->count();
		
		$rows = $db->orderBy('id', 'DESC')->limit(THREAD_LIST_PAGESIZE)->offset(($page - 1) * THREAD_LIST_PAGESIZE)->fetchAll();
		if($rows){
			foreach($rows as &$row){
				$row['avatar'] = $row['avatar'] ? 'http://'.STATIC_FILE_HOST.'/avatar/'.$row['avatar'] : 'http://'.STATIC_FILE_HOST.'/img/nophoto.png';
				$row['avatar_small'] = str_replace('_200.', '_100.', $row['avatar']);
			}
			unset($row);
		}
		
		return array(
			'count'		=> $count,
			'pages'		=> ceil($count / THREAD_LIST_PAGESIZE),
			'page'		=> $page,
			'list'		=> $rows
		);
	}
	
	public function updateUserInfo($uid, $data){
		wei()->db->update($this->table, $data, array('id' => $uid));
	}
	
	public function setPassword($uid, $password){
		$salt = random(5);
		$hash_password = $this->hashPassword($password, $salt);
		
		$this->updateUserInfo($uid, array('passwd' => $hash_password, 'salt' => $salt));
	}
	
	public function setLoginCookie($uid, $remember = true){
		$time = $remember ? 86400 * 60 : 86400;
		
		$user = wei()->db($this->table)->select('name, passwd')->where('id=?', $uid)->find();
		
		$token = authcode($uid.'|' . $user['passwd'].'|'.time(), 'ENCODE');

		setcookie(self::AUTH_COOKIE, $token, time() + $time, '/', COOKIE_DOMAIN);
	}
}