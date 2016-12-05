<?php 

/*
/home/eznewlife/ad.eznewlife.com/laravel/frank/config/ 底下的設定檔
*/

function cc($prop){
    
    static $dic=null;
    
    if($dic !== null){
        return $dic[$prop];
    }


    $file = __DIR__.'/config/'.__DOMAIN__.'.php';

    $config=[];
    require $file;

    $dic = $config;


    return $dic[$prop];
}



// $dbtype = master, slave
function oconn($dbtype){
    
    static $dic=[];

    if(isset($dic[$dbtype])) return $dic[$dbtype];


    $conn = omake_conn($dbtype);

    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dic[$dbtype]=$conn;

    return $conn;
    
}

function omake_conn($dbtype){
    $db_user=_env($dbtype.'_DB_USERNAME');
    $db_host=_env($dbtype.'_DB_HOST');
    $db_password=_env($dbtype.'_DB_PASSWORD');
    $db_name=_env($dbtype.'_DB_DATABASE');

    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $conn = new light_pdo($dsn, $db_user, $db_password);
    return $conn;
}



function _env($key){
    static $dic = null;

    if($dic !== null) return $dic[$key];

    $dic=[];

    $rs = file_get_contents(__DIR__.'/../.env');
    $rs = explode("\n", $rs);
    
    for ($i=0,$imax=count($rs); $i < $imax; $i++) { 
        $r=$rs[$i];

        if(strpos($r, '=') !== false){
            $r = explode('=', $r);
            $k = $r[0];
            $v = $r[1];
            $dic[$k]=$v;
        }
    }

    return $dic[$key];
}


/**
 * Replaces double line-breaks with paragraph elements.
 *
 * A group of regex replaces used to identify text formatted with newlines and
 * replace double line-breaks with HTML paragraph tags. The remaining line-breaks
 * after conversion become <<br />> tags, unless $br is set to '0' or 'false'.
 *
 * @since 0.71
 *
 * @param string $pee The text which has to be formatted.
 * @param bool   $br  Optional. If set, this will convert all remaining line-breaks
 *                    after paragraphing. Default true.
 * @return string Text which has been converted into correct paragraph tags.
 */

function wpautop($pee, $br = true) {

    $pre_tags = array();

    if ( trim($pee) === '' )
        return '';

    // Just to make things a little easier, pad the end.
    $pee = $pee . "\n";

    /*
     * Pre tags shouldn't be touched by autop.
     * Replace pre tags with placeholders and bring them back after autop.
     */
    if ( strpos($pee, '<pre') !== false ) {
        $pee_parts = explode( '</pre>', $pee );
        $last_pee = array_pop($pee_parts);
        $pee = '';
        $i = 0;

        foreach ( $pee_parts as $pee_part ) {
            $start = strpos($pee_part, '<pre');

            // Malformed html?
            if ( $start === false ) {
                $pee .= $pee_part;
                continue;
            }

            $name = "<pre wp-pre-tag-$i></pre>";
            $pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

            $pee .= substr( $pee_part, 0, $start ) . $name;
            $i++;
        }

        $pee .= $last_pee;
    }
    // Change multiple <br>s into two line breaks, which will turn into paragraphs.
    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);

    $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

    // Add a single line break above block-level opening tags.
    $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);

    // Add a double line break below block-level closing tags.
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

    // Standardize newline characters to "\n".
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee);

    // Find newlines in all elements and add placeholders.
    $pee = wp_replace_in_html_tags( $pee, array( "\n" => " <!-- wpnl --> " ) );

    // Collapse line breaks before and after <option> elements so they don't get autop'd.
    if ( strpos( $pee, '<option' ) !== false ) {
        $pee = preg_replace( '|\s*<option|', '<option', $pee );
        $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
    }

    /*
     * Collapse line breaks inside <object> elements, before <param> and <embed> elements
     * so they don't get autop'd.
     */
    if ( strpos( $pee, '</object>' ) !== false ) {
        $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
        $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
        $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
    }

    /*
     * Collapse line breaks inside <audio> and <video> elements,
     * before and after <source> and <track> elements.
     */
    if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
        $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
        $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
        $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
    }

    // Remove more than two contiguous line breaks.
    $pee = preg_replace("/\n\n+/", "\n\n", $pee);

    // Split up the contents into an array of strings, separated by double line breaks.
    $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

    // Reset $pee prior to rebuilding.
    $pee = '';

    // Rebuild the content as a string, wrapping every bit with a <p>.
    foreach ( $pees as $tinkle ) {
        $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
    }

    // Under certain strange conditions it could create a P of entirely whitespace.
    $pee = preg_replace('|<p>\s*</p>|', '', $pee); 

    // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
    $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
    
    // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); 
    
    // In some cases <li> may get wrapped in <p>, fix them.
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); 
    
    // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
    
    // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
    
    // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    // Optionally insert line breaks.
    if ( $br ) {
        // Replace newlines that shouldn't be touched with a placeholder.
        $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);

        // Replace any new line characters that aren't preceded by a <br /> with a <br />.
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); 

        // Replace newline placeholders with newlines.
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }

    // If a <br /> tag is after an opening or closing block tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
    
    // If a <br /> tag is before a subset of opening or closing block tags, remove it.
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

    // Replace placeholder <pre> tags with their original content.
    if ( !empty($pre_tags) )
        $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

    // Restore newlines in all elements.
    $pee = str_replace( " <!-- wpnl --> ", "\n", $pee );


    $pee = str_replace(']]>', ']]&gt;', $pee);
    
    return $pee;
}


/**
 * Newline preservation help function for wpautop
 *
 * @since 3.1.0
 * @access private
 *
 * @param array $matches preg_replace_callback matches array
 * @return string
 */
function _autop_newline_preservation_helper( $matches ) {
    return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
}


/**
 * Replace characters or phrases within HTML elements only.
 *
 * @since 4.2.3
 *
 * @param string $haystack The text which has to be formatted.
 * @param array $replace_pairs In the form array('from' => 'to', ...).
 * @return string The formatted text.
 */
function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
    // Find all elements.
    $textarr = wp_html_split( $haystack );
    $changed = false;

    // Optimize when searching for one item.
    if ( 1 === count( $replace_pairs ) ) {
        // Extract $needle and $replace.
        foreach ( $replace_pairs as $needle => $replace );

        // Loop through delimeters (elements) only.
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) { 
            if ( false !== strpos( $textarr[$i], $needle ) ) {
                $textarr[$i] = str_replace( $needle, $replace, $textarr[$i] );
                $changed = true;
            }
        }
    } else {
        // Extract all $needles.
        $needles = array_keys( $replace_pairs );

        // Loop through delimeters (elements) only.
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) { 
            foreach ( $needles as $needle ) {
                if ( false !== strpos( $textarr[$i], $needle ) ) {
                    $textarr[$i] = strtr( $textarr[$i], $replace_pairs );
                    $changed = true;
                    // After one strtr() break out of the foreach loop and look at next element.
                    break;
                }
            }
        }
    }

    if ( $changed ) {
        $haystack = implode( $textarr );
    }

    return $haystack;
}



/**
 * Separate HTML elements and comments from the text.
 *
 * @since 4.2.4
 *
 * @param string $input The text which has to be formatted.
 * @return array The formatted text.
 */
function wp_html_split( $input ) {
    static $regex;

    if ( ! isset( $regex ) ) {
        $comments =
              '!'           // Start of comment, after the <.
            . '(?:'         // Unroll the loop: Consume everything until --> is found.
            .     '-(?!->)' // Dash not followed by end of comment.
            .     '[^\-]*+' // Consume non-dashes.
            . ')*+'         // Loop possessively.
            . '(?:-->)?';   // End of comment. If not found, match all input.

        $cdata =
              '!\[CDATA\['  // Start of comment, after the <.
            . '[^\]]*+'     // Consume non-].
            . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
            .     '](?!]>)' // One ] not followed by end of comment.
            .     '[^\]]*+' // Consume non-].
            . ')*+'         // Loop possessively.
            . '(?:]]>)?';   // End of comment. If not found, match all input.

        $regex =
              '/('              // Capture the entire match.
            .     '<'           // Find start of element.
            .     '(?(?=!--)'   // Is this a comment?
            .         $comments // Find end of comment.
            .     '|'
            .         '(?(?=!\[CDATA\[)' // Is this a comment?
            .             $cdata // Find end of comment.
            .         '|'
            .             '[^>]*>?' // Find end of element. If not found, match all input.
            .         ')'
            .     ')'
            . ')/s';
    }

    return preg_split( $regex, $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}





function get_ad_KILL($ad_block_id, $plan, $domain){
    
    static $dic=[];
    $key=$ad_block_id.'_'.$plan.'_'.$domain;
    if(isset($dic[$key])){
        return $dic[$key];
    }

    $conn = ff\conn();

    $r=new stdClass();
    $r->id=$ad_block_id;

    $sql="select name,code,code_onload,rate from adv_code where plan=:plan and adv_id=:ad_block_id and domain=:domain";
    $row=$conn->getOne($sql, $plan, $ad_block_id, $domain);
  // echo $sql;
    $r->name=$row['name'];
    $r->code=$row['code'];
    $r->code_onload=$row['code_onload'];
    $r->rate=$row['rate'];

    $dic[$key]=$r;
    return $r;
}


function get_adCode($ad_block_id, $plan, $domain){

    //if($ad_block_id !== 15) return '';

    $ad = new __adcode($ad_block_id, $plan, $domain);

    return $ad->code;

    //$code = _get_adCode($ad_block_id, $plan, $domain);


    /*
    // 開窗  cookie 4小時一次 
    if( $code!=='' && $domain === 'eznewlife.com' && $ad_block_id === 15){
        $cookie_name = 'ad_block_15';
        //echo 'qqq';
        if(isset($_COOKIE[$cookie_name]) ){
            $code = '';
        }else{
            $hour = fconfig('ad_window_time');
            $hour = time() + $hour * 60 * 60;
            $hour = $hour*1000;

            $code.="\n<script>";
            $code.='var expires = new Date('.($hour).').toGMTString();';
            $code.="document.cookie='$cookie_name=1;Path=/;expires='+expires;";
            $code.='</script>'."\n";
            
        }
        
    }

    */


    if(isset($_GET['ad'])){
        return '<div style="background-color: #ffba00;"> <div>廣告'.$ad_block_id.'</div> '.$code.' </div>';
    }

    return $code;
}



function _get_adCode_KILL($ad_block_id, $plan, $domain){
    //echo $ad_block_id.' '.$plan.' '.$domain;

    $r=get_ad($ad_block_id, $plan, $domain); 

    $r->rate = $r->rate-0;


    $code = '';

    if( rand(1,100) < $r->rate){
        $code = $r->code_onload;
        $code = handle_adCode($ad_block_id, $plan, $domain, $code);
        return $code;
    }


    $code = $r->code;
    $code = handle_adCode($ad_block_id, $plan, $domain, $code);
    return $code;
}



function handle_adCode_KILL($block, $plan, $domain, $code){


    //if($block=='1') return $code;
    if($code==='') return $code;

    if(strpos($code, 'cutey5372') !== false) return $code;
    if(strpos(__DOMAIN__, 'dark') !== false) return $code;
    if(strpos(__DOMAIN__, 'avbody') !== false) return $code;
    if($block===15) return $code;

    $code='<div style="text-align:center;">廣告</div>'.$code;
    

    return $code;
}

function get_adCode_onload_kill($ad_block_id, $plan, $domain){
    echo 'get_adCode_onload';
    $r=get_ad($ad_block_id, $plan, $domain);
    return $r->code_onload;
}


function fdebug(){

    static $result=null;

    if($result !== null) return $result;

    if(isset($_GET['debug'])){
        if($_GET['debug'] == '1'){
            setcookie( "debug", 'frank', time()+(60*60*24*30), '/' );
            $result=true;
            return true;
        }
        
        setcookie('debug', null, -1, '/');
        $result=false;
        return false;
    }

    if(isset($_COOKIE['debug'] ) && $_COOKIE['debug'] === 'frank' ){
        $result=true;
        return true;
    }


    $result=false;
    return false;
}


function unique_id($num){
    $t = array(
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
    ,'0','1','2','3','4','5','6','7','8','9'
    );
    
    $r = $t[rand(0,51)];
    for($i=1;$i<$num;$i++)
    {
        $r.= $t[rand(0,61)];
    }
    return $r;
}


function fconfig($key){


    static $map=null;

    if($map!==null) return $map[$key];
    //直接cache return
    

    if(__DOMAIN__ === 'eznewlife.com'){
        $map =Cache::get('enl_parameters')->toArray();
        return $map[$key];
    }




    $domain = __DOMAIN__;

    $domain = explode('.', $domain);

    $domain = $domain[0];

    $map =Cache::get($domain.'_parameters')->toArray();

    /*
    echo '<!--map';
    echo $domain;
    print_r($map);
    echo '-->';

    */
    return $map[$key];

    /*
    if(__DOMAIN__ === 'dark.eznewlife.com'){
        $map =Cache::get('dark_parameters')->toArray();
        return $map[$key];
    }


    if(__DOMAIN__ === 'avbody.info'){
        $map =Cache::get('avbody_parameters')->toArray();
        return $map[$key];
    }


    if(__DOMAIN__ === 'getez.info'){
        $map =Cache::get('getez_parameters')->toArray();
        return $map[$key];
    }

    return $map[$key];
    */

    
    if(defined('__DOMAIN__')){
        $domain = __DOMAIN__;
    }else{
        $domain = 'eznewlife.com';
    }
    $conn = ff\conn();
    $sql="select name,data from parameters where domain='".$domain."'";
    $rows=  $conn->get([$sql],'fetch_obj');

    $map=[];

    for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
        $r=$rows[$i];
        $map[$r['name']]=$r['data'];
    }
  


    return $map[$key];
}