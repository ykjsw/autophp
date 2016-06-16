<?cs def:focusLink(item)?><?cs if:item.c_url_target==2?><?cs var:item.c_url?><?cs else?><?cs if:((item._cover.ex.is_trailer==1)||item._cover.ex.pay==0)&&(string.find(item._cover.ex.play,"http://")==-1)?>http://v.qq.com<?cs /if?><?cs var:item._cover.ex.play?><?cs if:(item.c_url_target==3)&&(string.length(item.c_url)==11)?>?vid=<?cs var:item.c_url?><?cs /if?><?cs /if?><?cs /def?>
<?cs def:setMaxage(pic)?><?cs var:pic?><?cs if:string.find(pic,"?")==#-1?>?<?cs else?>&<?cs /if?>max_age=604800<?cs /def?>
<?cs set:focusTarget="_blank"?><?cs set:limitSize=#12?>
<div class="banner_wrap" id="mod_focus" style="background-color:<?cs var:bigpic[0].c_ex_2?>">
	<div class="banner_bg"><?cs set:cnt=#1?><?cs each:it=bigpic?>
    	<a href="<?cs call:focusLink(it)?>" <?cs if:cnt>1?>style="display:none;"<?cs /if?> target="<?cs var:focusTarget?>" data-role="panel" data-img="<?cs call:setMaxage(it.c_pic)?>" data-color="<?cs var:it.c_ex_2?>" _hot="index.focus.img.<?cs var:cnt?>">
		<div class="img"<?cs if:cnt==1?> style="background:url(<?cs call:setMaxage(it.c_pic)?>) no-repeat 0 0;"<?cs /if?>></div>
		</a><?cs set:cnt=cnt+1?><?cs /each?>
    </div>
    <div class="banner_control">
    	<ul class="control_list"><?cs set:cnt=#1?><?cs each:item=bigpic?>
        	<li<?cs if:first(item)?> class="current"<?cs /if?> data-role="trigger"><a href="javascript:;" _hot="index.focus.dot.<?cs var:cnt?>"><i></i></a></li><?cs set:cnt=cnt+1?><?cs /each?>
        </ul>
        <a href="javascript:;" _hot="index.focus.prev" class="arrow_pre" data-role="prev">向前</a>
        <a href="javascript:;" _hot="index.focus.next" class="arrow_next" data-role="next">向后</a>
    </div>
    <div class="privilege_wrap" id="module-plaster">
        <div class="mod_privilege mod_privilege_v3">
           <div class="info_user" data-act="login">
                <a href="javascript:;" class="btn_login" title="登录" _hot="index.kaitong.login">用户登录</a>
            </div>
            <div class="info_privilege">
                <a class="title" title="VIP会员特权" href="<?cs var:viprightcss.0.vipright.0.c_url ?>" _hot="index.kaitong.kaitong_link" target="_blank">VIP会员特权>></a>
                <ul class="privilege_list">
                <?cs set:cnt=1?><?cs each:item=viprightcss?><?cs if:cnt<4 ?>
                    <li><a href="<?cs var:item.vipright.0.c_url?>" _hot="index.kaitong.kaitong<?cs var:cnt?>" target="_blank" title="<?cs var:html_escape(item.vipright.0.c_title)?>">
                        <i class="icon <?cs var:item.c_desc?>"></i>
                        <?cs var:html_escape(item.vipright.0.c_title)?>
                    </a></li><?cs set:cnt=cnt+1?><?cs /if ?><?cs /each?>
                </ul>
            </div>
            <div class="info_package" id="info_package">
                <p class="txt"><strong class="num"><?cs var:price.1.c_desc?></strong>元/月（开通3个月每月仅需15元）</p>
                <a data-toggle="pay" data-type=<?cs if:string.length(btn_open_config.0.c_url)>0?>"package"<?cs else?>"minipay"<?cs /if?> target="_blank" href="javascript:" title="开通VIP会员"  class="get_vip" _source="19" _hot="<?cs if:string.length(btn_open_config.0.c_url)>0?>index.kaitong.open<?cs else?>index.kaitong.open.minipay<?cs /if?>">开通VIP会员<?cs if:string.length(btn_open_config.0.c_ex_1)>0?><i class="icon_sale"><?cs var:html_escape(btn_open_config.0.c_ex_1)?></i><?cs /if?>
                </a>
            </div>
        </div>
    </div>
    <textarea style="display:none;" id="tpl-plaster-head">
        <div class="info_user">
            <a href="http://film.qq.com/home/" target="_blank" _hot="index.avatar.pic" class="user_pic">
                <img src="{avatar}" class="user_head" alt="">
            </a>
            <h3 class="user_name">
                <a href="http://film.qq.com/home/" target="_blank" _hot="nav.nickname" class="name">{nick}</a>
                {iconHtml}
            </h3>
            <p class="user_txt">{userTxt}</p>
            <a href="javascript:" _hot="index.plaster.logout" data-act="logout" class="log_out">[退出]</a>
        </div>
    </textarea>
    <textarea style="display:none;" id="tpl-vip-plaster">
        <div class="mod_privilege mod_privilege_v3">
            {head}
            <div class="vip_level_wrap">
                <p class="tit">成长值：<span class="tips">{score}</span>（{dailyscore}点/天）</p>
                <div class="vip_bar_wrap">
                <span class="{tit1}">LV{level}</span>
                <span class="vip_bar">
                    <span class="bar" style="width:{percent};">
                        <i class="bar_l"></i>
                        <i class="bar_r"></i>
                    </span>
                    <i class="vip_bar_l"></i>
                    <i class="vip_bar_r"></i>
                </span>
                <span class="{tit2}">LV{nextLevel}</span>
                </div>
                <p class="txt">{updateTips}</p>
            </div>
            <div class="info_package info_renew">
                <a data-toggle="pay" data-type=<?cs if:string.length(btn_open_config.0.c_url)>0?>"package"<?cs else?>"minipay"<?cs /if?> target="_blank" href="javascript:" class="get_vip renew"  _source="19"  _hot="<?cs if:string.length(btn_open_config.0.c_url)>0?>index.kaitong.renew<?cs else?>index.kaitong.renew.minipay<?cs /if?>">
                    续费VIP会员<?cs if:string.length(btn_open_config.0.c_ex_1)>0?><i class="icon_sale"><?cs var:html_escape(btn_open_config.0.c_ex_1)?></i><?cs /if?>
                </a>
            </div>
            <div class="info_privilege info_privilege_weixin">
                <ul class="privilege_list">
                    <li><a href="javascript:" data-toggle="modal" data-target="#modal-weixin" _hot="index.weixin.follow" data-act="followWeixin" title="关注微信号，领取1张观影券"><i class="icon icon_8"></i>关注微信号，领取1张观影券</a></li>
                </ul>
            </div>
            {activity}
        </div>
    </textarea>
    <textarea style="display:none;" id="tpl-outdate-plaster">
        <div class="mod_privilege mod_privilege_v3">
            {head}
            <div class="vip_level_wrap">
                <p class="tit">您的VIP会员已于 {endTime} 到期</p>
                <p class="tit">成长值：<span class="tips">{score}</span>（每天下降{dailyscore}点<i class="icon_down"></i>）</p>
                <p class="txt">{degradeText}</p>
            </div>
            <i class="line"></i>
            {payBtn}
            <i class="line"></i>
            {activity}
        </div>
    </textarea>
    <textarea style="display:none;" id="tpl-plaster-activity">
        <?cs escape:"html" ?>
            <div class="info_suggest">
                <ul class="suggest_list">
                    <li>
                        <a href="<?cs var:tips.0.c_url ?>" _hot="index.plaster.active" title="<?cs var:tips.0.c_desc ?>" target="_blank">
                            <?cs var:tips.0.c_desc ?>
                        </a>
                    </li>
                </ul>
            </div>
        <?cs /escape ?>
    </textarea>
	<?cs set:fadecover_h="490px"?><?cs set:fadecover_w="100%"?><?cs linclude:config.tplpath+"/film/common/comm_fadecover.tpl"?>
</div>
