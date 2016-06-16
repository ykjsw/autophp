<?php

function get_db_table($name, $id = 0){
	
	switch ($name) {
		case 'member':
			$table = 'tb_forum_member_' . $id;
			break;
		case 'image':
			$id = substr($id, 0, 2);
			$table = 'tb_image_' . $id;
			break;
		case 'reply':
			$table = 'tb_reply_' . $id;
			break;
		case 'post':
			$table = 'tb_post_' . $id;
			break;
		case 'thread':
			$table = 'tb_thread_' . $id;
			break;
		case 'userimage':
			$sid = $id % 10;
			$table = 'tb_user_image_' . $sid;
			break;
		
		default:
			$table = 'jser_' . $name;
			break;
	}
	
	return $table;
}

function encode_ids($id1, $id2){
	$hashids = new Hashids\Hashids(ENCODE_HASH_KEY);
	$id = $hashids->encode($id1, $id2);
	
	return $id;
}

function decode_ids($hash_id){
	$hashids = new Hashids\Hashids(ENCODE_HASH_KEY);
	$numbers = $hashids->decode($hash_id);
	
	return $numbers;
}