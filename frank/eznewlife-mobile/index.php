<?php
// This file is part of the EzNewLife Theme for WordPress
// http://eznewlife.com
// **********************************************************************
//決定是什麼頁面
/*
$pagetype = array();
$funcs = array('is_404','is_search','is_tax','is_front_page','is_home','is_single','is_category','is_tag','is_author'
    ,'is_date','is_archive','is_comments_popup','is_paged');
for($i=0,$imax=count($funcs);$i<$imax;$i++){
    $f = $funcs[$i];
    eval('$bool = '.$f.'();');
    if($bool){
        $pagetype[$f] = 1;
    }else{
        $pagetype[$f] = 0;
    }
}
show($pagetype);
*/
error_reporting(E_ALL);
include_once(ABSPATH.'wp-includes/post-thumbnail-template.php');
$c = array();
$c['is_admin']=false;
$c['no_cache']=false;
if(!empty($_COOKIE['nom']) && $_COOKIE['nom']=='nomA123999'){
    $c['is_admin']=true;
    DEFINE('is_admin','1');
}else{
    DEFINE('is_admin','0');
}
if(!empty($_COOKIE['no_cache']) && $_COOKIE['no_cache']=='1') $c['no_cache']=true;
if(!$c['is_admin']) error_reporting(0);

$c['adv1']='';
$c['adv2']='';

$ts = microtime(true);
if(!empty($_GET['p'])) $p=$_GET['p'];
else $p = '';

$static = dirname(__FILE__).'/static/'.$p;


$c['no_cache'] = true;//暫時不cache

if($c['no_cache']===false && is_numeric($p) && file_exists($static)){
    include($static);
}else{
    
    $c['root_path'] = ABSPATH;
    $c['site_url'] = site_url().'/';
    $c['template_path'] = ABSPATH.'wp-content/themes/eznewlife-mobile/';
    $c['template_url'] = get_bloginfo( 'template_directory', 'display' ).'/';
    $c['paged'] = '1';
    $c['is_home']=is_home();
    $c['is_single']=is_single();
    $c['is_category']=is_category();
    $c['is_archive']=is_archive();
    $c['is_404']=is_404();
    $c['page_status'] = '';
    $c['title']='';
    $c['previous_link']='';
    $c['next_link']='';
    $c['max_paged']=0;
    $c['content']='';
    $c['ad_block_1'] = '';
    $c['ad_block_2'] = '';
    $c['ad_block_3'] = '';
    $c['ad_block_4'] = '';
    $c['ad_block_5'] = '';
    if(!empty($_GET['paged'])) $c['paged']=$_GET['paged'];


    if($c['is_home']) $c['page_status']='is_home';
    else if($c['is_single']) $c['page_status']='is_single';
    else if($c['is_category']) $c['page_status']='is_category';
    else if($c['is_archive']) $c['page_status']='is_archive';
    else if($c['is_404']) $c['page_status']='is_404';
    switch($c['page_status']){
        case 'is_home': 
            //■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $c['content'] = enl_get_content($c);
            //廣告
            $adv = get_adv($c);
            $c['ad_block_1'] = $adv['ad_block_1'];
            $c['ad_block_2'] = $adv['ad_block_2'];
            $c['ad_block_3'] = $adv['ad_block_3'];
            $c['ad_block_4'] = $adv['ad_block_4'];
            $c['ad_block_5'] = $adv['ad_block_5'];
            
            if(!empty($_POST['isAjax']) && $_POST['isAjax']==1){
                echo $c['content'];
            }else{
                $c['tpl'] = $c['template_path'].'tpl/home.html';
                enl_display($c);
            }
            
            break;
        case 'is_single':
            //■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $c['content'] = enl_get_content($c);
                
            $c['og_image'] = get_og_image();
            //廣告
            $adv = get_adv($c);
            $c['ad_block_1'] = $adv['ad_block_1'];
            $c['ad_block_2'] = $adv['ad_block_2'];
            $c['ad_block_3'] = $adv['ad_block_3'];
            $c['ad_block_4'] = $adv['ad_block_4'];
            $c['ad_block_5'] = $adv['ad_block_5'];
            
            $c['content'] = str_replace('{ad_block_2}',$c['ad_block_2'],$c['content']);
            
            //上下頁連結
            $categories = get_the_category();
            $categoryIDS = array();
            foreach ($categories as $category) {
                array_push($categoryIDS, $category->term_id);
            }
            $categoryIDS = implode(",", $categoryIDS);
            if(is_admin=='1') {
                //print_r($categories);
               //print_r(get_next_post($categoryIDS));
               //echo site_url();
            }
            //■■■■■■■■■■■■■■■■■■■■■■■■■上一頁連結■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            
            $_pre = get_previous_post($categoryIDS);
            if($_pre){
                $c['previous_link'] = $c['site_url'].'?p='.$_pre->ID;
            }
            
            //■■■■■■■■■■■■■■■■■■■■■■■■■下一頁連結■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $_next = get_next_post($categoryIDS);
            if($_next){
                $c['next_link'] = $c['site_url'].'?p='.$_next->ID;
            }
            //■■■■■■■■■■■■■■■■■■■■■■■■■title■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $c['title'] = get_the_title();
            
            //■■■■■■■■■■■■■■■■■■■■■■■■■template■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $c['tpl'] = $c['template_path'].'tpl/home.html';
            
            enl_display($c);
            /*
            ob_start();
            enl_display($c);
            $content = ob_get_contents();
            ob_end_clean();
            $now = date('Y-m-d H:i:s',time()+28800);
            file_put_contents($static,$content."\n<!--ENL mobile cached server $now -->");
            echo $content;
            */
            break;
        case 'is_category':
            //■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
            $c['content'] = enl_get_content($c);
            $category = get_category($_GET['cat']);
            $count = $category->category_count;
            $c['max_paged'] = round($count/20);
            //廣告
            $adv = get_adv($c);
            $c['ad_block_1'] = $adv['ad_block_1'];
            $c['ad_block_2'] = $adv['ad_block_2'];
            $c['ad_block_3'] = $adv['ad_block_3'];
            $c['ad_block_4'] = $adv['ad_block_4'];
            $c['ad_block_5'] = $adv['ad_block_5'];
            
            $c['tpl'] = $c['template_path'].'tpl/home.html';
            enl_display($c);
            break;
        case 'is_archive':
            $c['tpl'] = $c['template_path'].'tpl/home.html';
            enl_display($c);
            break;
        case 'is_404':
            $c['content'] = <<<EOD
<p>錯誤 404 - 找不到您所尋找的資料<br />
抱歉，您所尋找的頁面不存在或已經被刪除。<br />
請再次確認您輸入網址正確。</p>
EOD;
            $c['tpl'] = $c['template_path'].'tpl/home.html';
            enl_display($c);
            break;
        default:
            break;
    }
}

if(is_admin==='1'){
    $te = microtime(true);
    $tt = $te-$ts;
    $now = date('Y-m-d H:i:s',time()+28800);
    echo "<!--$now $tt-->";
    
}

?>
