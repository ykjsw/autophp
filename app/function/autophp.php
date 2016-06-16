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

function html_ordered_menu($array, $parent_id = 0){
	$menu_html = '<ul>';
	foreach($array as $element){
		
	    if($element['parent_id'] == $parent_id){
			
			if($element['url'] == ''){
				$menu_html .= '<li class="isFolder">';
				$menu_html .= $element['name'];
				$menu_html .= html_ordered_menu($array, $element['id']);
			}else{
				$menu_html .= '<li>';
				if($element['new_window'] == 1){
					$menu_html .= '<a href="'.$element['url'].'" target="_blank">'.$element['name'].'</a>';
				}else{
					$menu_html .= '<a href="'.$element['url'].'" target="framebody">'.$element['name'].'</a>';
				}
			}

			$menu_html .= '</li>';
	    }
	}
  $menu_html .= '</ul>';
  return $menu_html;
}