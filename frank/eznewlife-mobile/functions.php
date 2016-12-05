<?php 
// This file is part of the EzNewLife Theme for WordPress
// http://eznewlife.com
// **********************************************************************

function show($text){
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}

function enl_get_thumbnail_url(){
    global $post;
    $empty_url = get_bloginfo('template_directory') . '/images/thumbnail.png';
    $id = $post->ID;
    if (function_exists('has_post_thumbnail') && has_post_thumbnail($id)){
        return get_the_post_thumbnail();
    }else{
        return $empty_url;
    }
    
}
function enl_get_thumbnail(){
    global $post;
    $empty_thumbnail = '<img src="' . get_bloginfo('template_directory') . '/images/thumbnail.png" alt="' . get_the_excerpt() . '" title="' . get_the_title() . '" />';
    if (function_exists('has_post_thumbnail')){
        if (has_post_thumbnail($id)) {
            return get_the_post_thumbnail( $id, $size, array(
                'alt'     => get_the_excerpt(), 
                'title' => get_the_title()
            ) );
        }
    }else{
        return $empty_thumbnail;
    }
    
}

function ___arras_get_thumbnail($size = 'thumbnail', $id = NULL) {
    global $post, $arras_image_sizes;
    
    $empty_thumbnail = '<img src="' . get_bloginfo('template_directory') . '/images/thumbnail.png" alt="' . get_the_excerpt() . '" title="' . get_the_title() . '" />';
    
    if ($post) $id = $post->ID;
    
    // get post thumbnail (WordPress 2.9)
    if (function_exists('has_post_thumbnail')) {
        if (has_post_thumbnail($id)) {
            return get_the_post_thumbnail( $id, $size, array(
                'alt'     => get_the_excerpt(), 
                'title' => get_the_title()
            ) );
        } else {
            // Could it be an attachment?
            if ($post->post_type == 'attachment') {
                return wp_get_attachment_image( $id, $size, false, array(
                    'alt'     => get_the_excerpt(), 
                    'title' => get_the_title()
                ) );
            }        
            // Use first thumbnail if auto thumbs is enabled.
            if (arras_get_option('auto_thumbs')) {
                $img_id = arras_get_first_post_image_id();
                if (!$img_id) return $empty_thumbnail;
                
                return wp_get_attachment_image($img_id, $size, false, array(
                    'alt'     => get_the_excerpt(), 
                    'title' => get_the_title()
                ) );
            }
        }
    }
    
    // go back to legacy (phpThumb or timThumb)
    $thumbnail = get_post_meta($id, ARRAS_POST_THUMBNAIL, true);
    
    $w = $arras_image_sizes[$size]['w'];
    $h = $arras_image_sizes[$size]['h'];
    
    if ($thumbnail != '') {
        if (!$arras_image_sizes[$size]) return false;    
        return '<img src="' . get_bloginfo('template_directory') . '/library/timthumb.php?src=' . arras_timthumb_wpmu_image_src($thumbnail) . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;zc=1" alt="' . get_the_excerpt() . '" title="' . get_the_title() . '" />';
    } else if (arras_get_option('auto_thumbs')) {
        if (!$arras_image_sizes[$size]) return false;
        
        $img_id = arras_get_first_post_image_id();
        if (!$img_id) return $empty_thumbnail;
        
        $image = wp_get_attachment_image_src($img_id, 'full', false);
        if ($image) {
            list($src, $width, $height) = $image;
            return '<img src="' . get_bloginfo('template_directory') . '/library/timthumb.php?src=' . arras_timthumb_wpmu_image_src($src) . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;zc=1" alt="' . get_the_excerpt() . '" title="' . get_the_title() . '" />';
        }
    }
    
    return $empty_thumbnail;    
}

function ___arras_get_single_thumbs_size() {
    $layout = arras_get_option('layout');

    if ( strpos($layout, '1c') !== false ) {
        $size = array(930, 375);
    } else if ( strpos($layout, '3c') !== false ) {
        $size = array(465, 190);
    } else {
        $size = array(620, 300);
    }
    
    return apply_filters('arras_content_width', $size);
}

function ___arras_get_first_post_image_id($id = NULL) {
    global $post;
    if (!$id) $id = $post->ID;
    
    $attachments = get_children('post_parent=' . $id . '&post_type=attachment&post_mime_type=image');
    if (!$attachments) return false;
    
    $keys = array_reverse(array_keys($attachments));
    return $keys[0];
}

function enl_get_content($c){
    $content='123';
    $page_status = $c['page_status'];
    $datas=array();
    if($page_status=='is_home' || $page_status=='is_category'){
        while(have_posts()){
            the_post();
            global $post;
            //if(is_admin=='1') show($post);
            $tmp = array();
            $tmp['post_id'] = $post->ID;
            $tmp['date'] = esc_html(get_the_time('Y-m-d'));//the_date('Y-m-d','','',false);
            $tmp['time'] = esc_attr( get_the_time() );
            
            $tmp['alt'] = get_the_excerpt();
            $tmp['title'] = get_the_title();
            //$tmp['alt'] = text_to_dot($tmp['alt']);
            $_e = '';
            if(mb_strlen($tmp['alt']) >30) $_e='...';
            $tmp['alt'] = mb_substr($tmp['alt'],0,30,"UTF-8").$_e;
            $tmp['thumbnail_url'] = enl_get_thumbnail_url();
            
            //echo $tmp['thumbnail_url']."<br>";
            //$tmp['thumbnail_url'] = str_replace('fun-vdo.com/enl2/','eznewlife.com/',$tmp['thumbnail_url']);
            //$tmp['thumbnail_url']='<img width="305" height="210" src="'.$tmp['thumbnail_url'].'" class="attachment-post-thumbnail wp-post-image" alt="61-1">';
            if(strpos($tmp['thumbnail_url'],'thumbnail.png')!==false){
                $tmp['thumbnail_url'] = '<img width="80" height="80" src="'.$tmp['thumbnail_url'].'" class="attachment-post-thumbnail wp-post-image" >';
            }
            
            $tmp['guid'] = $post->guid;
            $url = explode('?',$tmp['guid']);
            $tmp['guid'] = $c['site_url'].'?'.$url[1];
            $terms = get_the_category($post->ID);
            $name = $terms[0]->name;
            if(!$name) $name = '未分類';
            $tmp['category'] = $name;
            $datas[] = $tmp;
        }
        
         if(!empty($_POST['isAjax']) && $_POST['isAjax']==1){
            //echo $content;
            $json = array();
            for($i=0,$imax=count($datas);$i<$imax;$i++){
                $d = $datas[$i];
                $json[] = array(
                    'post_id' => $d['post_id']
                    ,'guid' =>$d['guid']
                    ,'thumbnail_url' =>$d['thumbnail_url']
                    ,'category' =>$d['category']
                    ,'date' =>$d['date']
                    ,'time' =>$d['time']
                    ,'title' =>$d['title']
                    ,'alt' =>$d['alt']
                );
            }
            $rtn = array(
                'success'=>1
                ,'msg'=>''
                ,'data'=>$json
            );
            //$content = json_encode($rtn);
            $content = '^^^___^^^'.json_encode($rtn).'^^^___^^^';
        }else{
            $content = '';
            for($i=0,$imax=count($datas);$i<$imax;$i++){
                $d = $datas[$i];
                $content.='<a href="'.$d['guid'].'" class="link" post_id="'.$d['post_id'].'">';
                //$content.='<img src="'.$d['thumbnail_url'].'" />';
                $content.=$d['thumbnail_url'];
                $content.='<p>';
                //$content.='<span class="tab">'.$d['category'].'</span><span class="date">'.$d['date'].' '.$d['time'].'</span><br />';
                $content.='<span class="tab">'.$d['category'].'</span><span class="date">'.$d['date'].'</span><br />';
                $content.='<span class="title">'.$d['title'].'</span><br />';
                $content.='<span class="alt">'.$d['alt'].'</span>';
                $content.='</p></a>';
            }
            //echo "<textarea>".$content."</textarea>";
            
        }
    }else if($page_status=='is_single'){
        while(have_posts()){
            the_post();
            $tmp=array();
            $_content = get_the_content();
            $_content = apply_filters('the_content', $_content);
            $_content = str_replace(']]>', ']]&gt;', $_content);
            $_content = str_replace('fun-vdo.com/enl2/','eznewlife.com/',$_content);
            $tmp['title'] = '<h2>'.get_the_title().'</h2>';
            $tmp['content'] = $_content;
            
            $content.=$tmp['title'];
            $content.='<div id="ad_block_2">{ad_block_2}</div>';
            
            $content.='<p style="text-align:center;"><img src="'.get_og_image().'" style="max-width:100%;margin:0 auto;margin-top:20px;margin-bottom:20px;" /></p>';

            //$content.=$_content;
            $content.='<div style="text-align:center;"><a class="button_seemore" id="seemore" onclick="seemore();">點我看文章</a></div>';            

            $content.='<div id="enl_real_content" style="display:none;">'.$_content.'</div>';

        }
    }
    return $content;
}

function enl_display($p){
    include($p['tpl']);
}

function get_adv($content, $page_status){
    $adv=array();
    $adv['ad_block_1'] = '';
    $adv['ad_block_2'] = '';
    $adv['ad_block_3'] = '';
    $adv['ad_block_4'] = '';
    $adv['ad_block_5'] = '';
    if($page_status=='is_single'){
        if(strpos($content,'class="adv" title="')===false){
            $adv['ad_block_2'] = 'ENL.mobile.scupio.300X250';
           // $adv['ad_block_3'] = 'ENL.mobile.yahoo.300X250';
		   // $adv['ad_block_3'] = 'ENL.mobile.cf.300X250';
		   // $adv['ad_block_3'] = 'ENL.mobile.app.300X250';
		    $adv['ad_block_3'] = 'ENL.mobile.google.300X250';
            $adv['ad_block_4'] = 'ENL.mobile.google.300X250_2';
            //window
            $rand = rand(1,10);
            if($rand>0){
                $adv['ad_block_5'] = 'ENL.mobile.google.300X250';
            }else{
                $adv['ad_block_5'] = 'ENL.mobile.yahoo.300X250';
            }
            
            //$adv['ad_block_5'] = (rand(0,1)===0?'ENL.mobile.yahoo.300X250':'ENL.mobile.google.300X250');
        }else{
            $adv['ad_block_2'] = 'ENL.mobile.adp.300X250';
          //  $adv['ad_block_3'] = 'ENL.mobile.yahoo.300X250';
			$adv['ad_block_3'] = 'ENL.mobile.cf.300X250';
		//	$adv['ad_block_3'] = 'ENL.mobile.app.300X250';
		 //   $adv['ad_block_3'] = 'ENL.mobile.scupio.300X250';
            $adv['ad_block_4'] = 'ENL.mobile.adp.300X250';
            //window
           // $adv['ad_block_5'] = 'ENL.mobile.yahoo.300X250';
		    $adv['ad_block_5'] = 'ENL.mobile.app.300X250';
        }
        
        $adv['ad_block_2'] = file_get_contents(__DIR__.'/db/'.$adv['ad_block_2']);
        $adv['ad_block_3'] = file_get_contents(__DIR__.'/db/'.$adv['ad_block_3']);
        $adv['ad_block_4'] = file_get_contents(__DIR__.'/db/'.$adv['ad_block_4']);
        $adv['ad_block_5'] = file_get_contents(__DIR__.'/db/'.$adv['ad_block_5']);
    }else{
        $adv['ad_block_1'] = 'ENL.mobile.google.320X50'; //置底
        $adv['ad_block_1'] = file_get_contents(__DIR__.'/db/'.$adv['ad_block_1']);
        
    }
    

    foreach($adv as $block => $code){
        
        if($block === 'ad_block_1'){
            $adv[$block]='';            
            continue;
        }

        $adv[$block] = '<div style="font-size:.8em;">廣告</div>'.$code;

    }

    return $adv;
}


function get_og_image(){

    static $og_image=null;

    if($og_image) return $og_image;

    global $post;

    $thumb = get_post_meta($post->ID,'_thumbnail_id',false);
    $thumb = wp_get_attachment_image_src($thumb[0], false);     
    $thumb = $thumb[0];     
    $default_img = get_bloginfo('stylesheet_directory').'/images/default_icon.jpg';

    $og_image = '';
    if ( $thumb[0] == null ) { $og_image= $default_img; } else { $og_image= $thumb; }
    
    $file = str_replace('http://eznewlife.com/', '', $og_image);
    $file = '/home/eznewlif/public_html/'.$file;
    
    $file2 = str_replace('wp-content/uploads','ogimage', $file);
    
    if( file_exists($file2) ){
        $og_image = str_replace('wp-content/uploads','ogimage', $og_image);
    }
    
    return $og_image;
}