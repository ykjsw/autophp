
<!DOCTYPE html>
<html lang="zh-cn" >
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,IE=9" />
    <title>腾讯视频VIP-腾讯视频</title>
    <meta name="keywords" content="腾讯视频VIP,腾讯视频,film,dapian,电影,付费,会员,高清,视频,在线观看" />
    <meta name="description" content="好莱坞影院，定位于中国领先的付费在线电影平台，以丰富的内容、极致的观看体验、便捷的登录支付方式、24小时多平台无缝应用体验以及快捷分享的产品特性，满足用户付费在线观看视频的需求。" />
    <link rel="Shortcut Icon" href="http://v.qq.com/favicon.ico" type="image/x-icon" />
    <link rel="bookmark" href="http://v.qq.com/favicon.ico" type="image/x-icon" />
    <!--#include virtual="/inc/common/comm_head_fun.html"-->
    <!--#include virtual="/inc/common/css_version.html"-->
    <!--#include virtual="/inc/common/js_version.html"-->
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/tencentvideo_v1/vstyle/film/v3/style/vip/vip_base.css" type="text/css" />
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/tencentvideo_v1/vstyle/film/v3/style/vip/vip_pri_detail.css" type="text/css" />

</head>
<body>

<!-- 导航 开始 -->



<!--#include virtual="/inc/nav/nav_vip_2.html"-->

<div class="mod_hand_banner">
    <div class="hand_banner">
        <h3 class="tit">会员特权</h3>
        <p class="txt">成为腾讯视频VIP，畅享电影世界！</p>
    </div>
</div>

<!-- 
@all_privs 参数说明
c_s_title(短标题) : 院线新片
c_s_subtitle(短副标题) : 月省百元电影票钱
c_l_title(长标题) : 院线新片全覆盖
c_l_subtitle(长副标题) : 拥有海量优质内容，持续更新最新大片，让你足不出户看电影，享受私人观影时光！
c_pic(红色图片) : 图片链接
c_pic_noselected(灰色图片) : 图片链接
c_classify_priv(所属特权分类) : 内容特权
-->
<!-- 
@pri_detail 参数说明
c_classify_priv(所属特权名称) : 院线新片
c_subtitle : 副标题
c_desc 具体内容 : 有链接会被分割  c_desc_item.0 为前置文字内容 c_desc_item.1 为链接 c_desc_item.2 为链接的html文字
c_modules(版块分类) : datasource(影片列表)/introduce(特权介绍)/use(特权使用)
c_pic(可能需要展示的图片)
-->
<div class="site_container">
   <!-- 可配置导航 开始 -->
    <div class="panel_title">
        <div class="mod_nav_pri mod_slide_page for_slider" data-role="content" data-step="1" data-rel="2;1" count="12">
            <div class="nav_slide">
                <ul class="pri_list cf">
                <?cs each:item=all_privs?>
                    <li class="list_item <?cs if:first(item)?>current<?cs /if?>" data-role="panel" data-title="<?cs var:item.c_s_title?>">
                        <a class="item_link" href="javascript:;" >
                            <span class="icon_pri">
                                <img class="img img_normal" src="<?cs var:item.c_pic_noselected?>" alt="<?cs var:item.c_s_title?>">
                                <img class="img img_current" src="<?cs var:item.c_pic?>" alt="<?cs var:item.c_s_title?>">
                            </span>
                            <p class="name"><?cs var:item.c_s_title?></p>
                            <span class="arrow"></span>
                        </a>
                    </li>
                <?cs /each?>
                </ul>
            </div>
            <!--超过12个加翻页-->
            <a href="#" class="arrow_pre arrow_none" data-role="prev">向前</a>
            <a href="#" class="arrow_next <?cs if:subcount(all_privs)<13 ?>arrow_none<?cs /if?>" data-role="next">向后</a>
        </div>
    </div>
    <!-- 可配置导航 结束 -->
    <!-- 影片列表模块生成函数 -->
    <?cs def:createPosterSlider(mode,hot)?>
    <div class="mod_vip_film mod_slide_page for_slider" data-role="panel" data-step="4" data-exp="tv_series.all.pager" data-rel="5;4">
            <div class="vip_film_show">
                <ul class="mod_vip_figure mod_vip_figure_v cf" style="width:<?cs var:subcount(mode)*275?>px;">
                <?cs each:item=mode?>
                    <li class="list_item" data-type="hover" data-role="panel">
                        <a class="figure_img" href="<?cs var:item.fields.url?>" target="_blank" title="<?cs var:html_escape(item.fields.title)?>" _hot="index.<?cs var:hot?>.img" >
                            <img src="<?cs var:item.fields.vertical_pic_url?>" alt="<?cs var:item.fields.title?>" data-tags='<?cs var:item.fields.web_imgtag?>' onerror="picerr(this)" class="cover" />
                            <i class="mark_play"></i>
                        </a>
                        <div class="figure_info">
                            <h3 class="info_tit">
                                <a href="javascript:;" class="figure_tit"><?cs var:html_escape(item.fields.title)?></a>
                                <span class="figure_score" data-score="<?cs var:item.fields.score.score?>"></span>
                            </h3>
                            <p class="info_txt"><?cs var:item.fields.second_title?></p>
                        </div>
                    </li>
                <?cs /each?>
                </ul>
        </div>
        <a href="#" class="arrow_pre arrow_none" data-role="prev">向前</a>
        <a href="#" class="arrow_next" data-role="next">向后</a>
    </div>
    <?cs /def?>
    <!--特权内容 标准模块 开始-->
    <?cs each:item=all_privs?>
        <div class="panel_content" data-title="<?cs var:item.c_s_title?>" style="display:<?cs if:first(item)?>block<?cs else?>none<?cs /if?>">
            <!--描述+开通模块-->
            <div class="mod_pri_open">
                <div class="pri_open">
                    <h3 class="tit"><?cs var:item.c_l_title?></h3>
                    <p class="txt"><?cs var:item.c_l_subtitle?></p>
                </div>
                <a class="btn_open" href="javascript:;" data-toggle="pay" _source="27" _hot="vip.film.priv_detail.kaitong" data-amount="12">开通腾讯视频VIP</a>
            </div>
            <!--特权介绍模块-->
            <div class="mod_pri_tit">
                <h3 class="pri_tit">
                    <fieldset>
                        <legend>特权介绍</legend>
                    </fieldset>
                </h3>
            </div>
            <div class="mod_pri_detail">
                <?cs each:subitem=pri_detail?>
                    <?cs if:item.c_s_title==subitem.c_classify_priv?>
                        <?cs if:subitem.c_modules=="introduce"?>
                        <div class="pri_detail">
                            <p class="tit"><?cs var:subitem.c_subtitle?></p>
                            <p class="txt"><?cs var:subitem.c_desc?></p>
                            <?cs if:subitem.c_pic!=""?>
                                <div class="pri_pic">
                                        <img src="<?cs var:subitem.c_pic?>" alt="特权介绍" />
                                </div>
                            <?cs /if?>
                        </div>
                        <?cs /if?>
                    <?cs /if?>   
                <?cs /each?>
            </div>
            <?cs each:subitem=pri_detail?>
                <?cs if:item.c_s_title==subitem.c_classify_priv?>
                    <?cs if:subitem.c_modules=="use"?>
                    <!--特权使用模块-->
                    <div class="mod_pri_tit">
                        <h3 class="pri_tit">
                            <fieldset>
                                <legend>特权使用</legend>
                            </fieldset>
                        </h3>
                    </div>
                    <div class="mod_pri_use">
                        <div class="pri_tips">
                        <?cs if:subitem.c_desc_count>1?>
                            <p class="pri_tips">
                                <?cs var:subitem.c_desc_item.0?>
                                <a class="link" href="<?cs var:subitem.c_desc_item.1?>" target="_blank"><?cs var:subitem.c_desc_item.2?></a>
                            </p>
                            <?cs else?>
                                <p class="pri_tips"><?cs var:subitem.c_desc?></p>
                        <?cs /if?>
                            <img src="<?cs var:subitem.c_pic?>" alt="特权使用说明" />
                        </div>
                    </div>
                    <?cs /if?>   
                <?cs /if?>   
            <?cs /each?>     
            <?cs each:subitem=pri_detail?>
                <?cs if:item.c_s_title==subitem.c_classify_priv?>
                    <?cs if:subitem.c_modules=="datasource"?>
                        <!--影片推荐模块-->
                        <div class="mod_pri_tit">
                            <h3 class="pri_tit">
                                <fieldset>
                                    <legend><?cs var:subitem.c_classify_priv?></legend>
                                </fieldset>
                            </h3>
                            <?cs if:subitem.c_desc_count>1?>
                                <p class="pri_tips">
                                    <?cs var:subitem.c_desc_item.0?>
                                    <a class="link" href="<?cs var:subitem.c_desc_item.1?>" target="_blank"><?cs var:subitem.c_desc_item.2?></a>
                                </p>
                                <?cs else?>
                                    <p class="pri_tips"><?cs var:subitem.c_desc?></p>
                            <?cs /if?>
                        </div>
                        <?cs if:item.c_datasource=="newfilm"?>
                            <?cs call:createPosterSlider(new_newfilm,"pri_detail_newfilm")?>
                        <?cs /if?>
                        <?cs if:item.c_datasource=="documentary"?>
                            <?cs call:createPosterSlider(new_documentary,"pri_detail_documentary")?>
                        <?cs /if?>
                        <?cs if:item.c_datasource=="america"?>
                            <?cs call:createPosterSlider(new_america,"pri_detail_america")?>
                        <?cs /if?>
                        <?cs if:item.c_datasource=="hottv"?>
                            <?cs call:createPosterSlider(new_hottv,"pri_detail_hottv")?>
                        <?cs /if?>
                        <?cs if:item.c_datasource=="useticket"?>
                            <?cs call:createPosterSlider(new_useticket,"pri_detail_useticket")?>
                        <?cs /if?>
                    <?cs /if?>
                <?cs /if?>   
            <?cs /each?> 
        </div>
    <?cs /each?>
    <!--特权内容 标准模块 结束-->

</div>

<div class="mod_vip_footer">
<!--#include virtual="/inc/bottom.html"-->
</div>
<!--#include virtual="/inc/rightfloat.html"-->
<!--#include virtual="/inc/common/core-inc.html"-->
<script src="http://<!--#echo var="js.imgcache"-->/tencentvideo_v1/js/film/vip/vip.priv.js?<!--#echo encoding="none" var="js.version.family"-->"></script>
<script>
    stats.speed.setPageId(16).setPageLoadPoint(3);
    
    $(function () {
        stats.speed.setFlag(1, logger.elapsed('page start'));
         fm.common.initPage();  
        logger.time('init time');
         var sliders=new Array();
        var arrStepMap=new Array();
        //各模块 data-rel用来限定slider的step
        $('.for_slider').each(function(){
            
            var step=5;
            if(typeof($(this).attr("data-rel"))!="undefined")
            {
                var arrSteps=$(this).attr("data-rel").split(";");
                arrStepMap.push(arrSteps);
                if(fm.common.scrollStep==5)
                {
                    step=arrSteps[0];
                }
                else
                {
                    step=arrSteps[1];
                }
            }
            
            var count = $(this).attr("count");
            var prevDisClass=$(this).hasClass("for_arrow_disable")?'arrow_pre_disable':'arrow_none';
            var nextDisClass=$(this).hasClass("for_arrow_disable")?'arrow_next_disable':'arrow_none';
            
            var slider= new $(this).Slider({ 
                step: step,
                lazyStr: 'lz_src',
                effect: 'scroll',
                prevDisClass: prevDisClass,
                nextDisClass: nextDisClass,
                circular: false,
                count: !!count?count:0
            });
            if(slider!=null)
            {
                sliders= sliders.concat(slider);
            }
        });
        
        fm.common.changeBodyCb.add(function(type){
            for(var i=0;i<sliders.length;i++){
                var newStep= type=='big'?arrStepMap[i][0]:arrStepMap[i][1];
                sliders[i].reload(newStep);
            }
        });
        
        stats.speed.setFlag({
            '2': logger.elapsed('page start'),
            '4': logger.timeEnd('init time')
        }).report();
    }); 
</script>

<script type="text/javascript">
/* tab切换 */
$(function(){
    var index=null;
    var tabA = $(".mod_nav_pri li");
    var pageA = $(".site_container .panel_content");

    var current='list_item current';//当前点击
    // var currentIndex = 0;
    var indexValue=function(self,obj){//获取索引值
        for(var i=0;i<obj.length;i++){ 
            if(obj[i]==self) return i; 
        }; 
    }; 

    firstOpen();
    // tabA[currentIndex].className=current;//第一个默认高亮

    // for(var i=0;i<pageA.length;i++){//用循环定义默认显示第一个切换区块,其他隐藏.
    //     pageA[i].style.display='none'; 
    //     pageA[currentIndex].style.display='block'; 
    // }

    for(var i=0;i<tabA.length;i++){//点击事件 
        tabA[i].onclick=function(){ 
            index=indexValue(this,tabA);//利用前面定义的indexValue函数取当前点击在选项导航中的索引值, 
            for(var j=0;j<tabA.length;j++){ 
                tabA[j].className=(j==index) ? current : 'list_item';//高亮显示点击项并移除其他项高亮 
                pageA[j].style.display=(j==index) ? 'block' : 'none';
            };
        };
    }
    
    function firstOpen() {
        var title = getUrlParam('s_title') || '院线新片';
        for(var ii = 0 ; ii < tabA.length ; ii++){
            tabA[ii].className = 'list_item';
            pageA[ii].style.display='none';
            if($(tabA[ii]).attr('data-title') == title){
                tabA[ii].className = 'list_item current';
                pageA[ii].style.display='block';
            }
        }
    }

    //获取url中的参数
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = window.location.search.substr(1).match(reg); //匹配目标参数
        if (r != null) return decodeURIComponent(r[2]);
        return null; //返回参数值
    }

    function loginaction(){
        seajs.use(['hw.request', 'hw.vip'], function (Req, Vip) {
            Request = Req;
            Req.vip().done(function (data) {
                if (data.result.code !== 0) {
                    return ;
                }
                function update(vip){
                    if (vip.isVip()) {
                        $('.btn_open').html('续费腾讯视频VIP');
                        $('.btn_open').attr('_hot',"vip.film.priv_detail.xufei");
                    }
                    else{
                        $('.btn_open').html('开通腾讯视频VIP');
                        $('.btn_open').attr('_hot',"vip.film.priv_detail.kaitong");                     
                    }
                }
                Vip(data).done(update);
            });
       });  
    }
    
    function logoutaction(){
        $('.btn_open').html('开通腾讯视频VIP');
        $('.btn_open').attr('_hot',"vip.film.priv_detail.kaitong");
    }
    
    if(txv.login.isLogin()){
        loginaction();
    }else{
        logoutaction();
    }
        
    Live.login.addLogoutCallback(logoutaction);
    Live.login.addLoginCallback(loginaction);
    
    var tab = $.util.getUrlParam('tab');
    if(tab && tabA[tab]){
        tabA[tab].click();
    }

    //added by nino
    $(".figure_score").each(function(idx, ele){
        var sc = Math.round(10 * this.getAttribute("data-score")) / 10;
        this.innerHTML = sc;
    });

    $("a.figure_img img").each(function(idx, ele){
        var tags = eval("("+this.getAttribute("data-tags")+")");

        var html = '';

        if(tags.tag_1.text){
             html += '<sup class="'+tags.tag_1.param+'">'+tags.tag_1.text+'</sup>';  
        }
        if(tags.tag_2.text){
             html += '<span class="'+tags.tag_2.param+'"><em class="mark_inner">'+tags.tag_2.text+'</em></span>';  
        }
        if(tags.tag_4.text){
             html += '<span class="'+tags.tag_4.param+'"><em class="mark_inner">'+tags.tag_4.text+'</em></span>';  
        }

        $(this).parent().append(html);
    });
});

</script>
</body>
</html>
