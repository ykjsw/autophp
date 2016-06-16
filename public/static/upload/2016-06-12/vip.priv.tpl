
<!DOCTYPE html>
<html lang="zh-cn" >
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,IE=9" />
    <title>VIP会员-腾讯视频VIP</title>
    <meta name="keywords" content="腾讯视频VIP,腾讯视频,film,dapian,电影,付费,会员,高清,视频,在线观看" />
    <meta name="description" content="腾讯视频VIP，定位于中国领先的付费在线电影平台，以丰富的内容、极致的观看体验、便捷的登录支付方式、24小时多平台无缝应用体验以及快捷分享的产品特性，满足用户付费在线观看视频的需求。" />
    <link rel="Shortcut Icon" href="http://v.qq.com/favicon.ico" type="image/x-icon" />
    <link rel="bookmark" href="http://v.qq.com/favicon.ico" type="image/x-icon" />
    <!--#include virtual="/inc/common/comm_head_fun.html"-->
    <!--#include virtual="/inc/common/css_version.html"-->
    <!--#include virtual="/inc/common/js_version.html"-->
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/tencentvideo_v1/vstyle/film/v3/style/vip/vip_base.css?<!--#echo encoding="none" var="css.version.global"-->" type="text/css">
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/tencentvideo_v1/vstyle/film/v3/style/vip/vip_privilege.css?<!--#echo encoding="none" var="css.version.film_parent_child"-->" type="text/css" />
    <link rel="stylesheet" href="http://imgcache.gtimg.cn/tencentvideo_v1/vstyle/film/v3/style/vip/vip_pri.css" type="text/css" />

</head>
<body>

<!-- 导航 开始 -->



<!--#include virtual="/inc/nav/nav_vip_2.html"-->

<div class="mod_hand_banner">
    <div class="hand_banner">
        <h3 class="tit">VIP特权</h3>
        <p class="txt">成为VIP会员，畅享电影世界！</p>
    </div>
</div>
<!-- 
@classify_pri 参数说明
c_itle(特权分类) : 内容特权
c_num(该分类下特权数目) : 5
-->
<!-- 
@all_privs 参数说名
c_s_title(短标题) : 院线新片
c_s_subtitle(短副标题) : 月省百元电影票钱
c_l_title(长标题) : 院线新片全覆盖
c_l_subtitle(长副标题) : 拥有海量优质内容，持续更新最新大片，让你足不出户看电影，享受私人观影时光！
c_pic(红色图片) : 图片链接
c_pic_noselected(灰色图片) : 图片链接
c_classify_priv(所属特权分类) : 内容特权
-->
<div class="site_container">
    <?cs each:item=classify_pri?>
    <div class="col_1row">
        <div class="mod_pri_tit">
            <h3 class="pri_tit">
                <fieldset>
                    <legend><?cs var:item.c_title?></legend>
                </fieldset>
            </h3>
        </div>
        <div class="mod_pri_list">
            <div class="pri_list_inner">
                <!--pri_list_5 代表5列。可以设置pri_list_1或pri_list_2或pri_list_3或pri_list_4或pri_list_5-->
                <ul class="pri_list pri_list_<?cs var:item.c_num?> cf">
                    <?cs each:subitem=all_privs?>
                        <?cs if:item.c_title==subitem.c_classify_priv?>
                            <li class="list_item">
                                <a class="item_link" href="http://film.qq.com/vip/vip_privilege_detail.html?s_title=<?cs var:subitem.c_s_title?>">
                                    <div class="link_inner">
                                        <span class="icon_pri">
                                            <img src="<?cs var:subitem.c_pic?>" alt="<?cs var:subitem.c_l_title?>">
                                        </span>
                                        <p class="name"><?cs var:subitem.c_l_title?></p>
                                        <p class="txt"><?cs var:subitem.c_s_subtitle?></p>
                                    </div>
                                </a>
                            </li>
                        <?cs /if?>
                    <?cs /each?>
                </ul>
            </div>
        </div>
    </div>
   <?cs /each?>

    <!-- 成为腾讯视频VIP 开始 -->
    <div class="col_1row">
        <div class="mod_line_tit">
        </div>
        <div class="mod_vip_join">
            <div class="vip_join">
                <ul class="list list_4 cf js_package_list">
                </ul>
            </div>
        </div>
    </div>
    <!-- 成为腾讯视频VIP 结束 -->
</div>
    

<div class="mod_vip_footer">
<!--#include virtual="/inc/bottom.html"-->
</div>
<!--#include virtual="/inc/rightfloat.html"-->
<!--#include virtual="/inc/common/core-inc.html"-->
<script src="http://qzs.qq.com/tencentvideo_v1/js/film/vip/vip.priv.js?v=20150105&max_age=86400"></script>
<script>
    stats.speed.setPageId(16).setPageLoadPoint(3);
    
    $(function () {
        stats.speed.setFlag(1, logger.elapsed('page start'));
        logger.time('init time');
        pageInit();
        stats.speed.setFlag({
            '2': logger.elapsed('page start'),
            '4': logger.timeEnd('init time')
        }).report();
    }); 
</script>
</body>
</html>
