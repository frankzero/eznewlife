<?php 


class __article extends __orm{

    public $table;

    public $conn;

    public function __construct($conn){


        $this->conn=$conn;
        $this->table='articles';
        parent::__construct($this->table, $conn);
    }



    public function load_by_id($id){
        parent::load_by_id($id);
        $this->loadUnique_id();
        $this->loadCategory();
        $this->loadName();
        $this->loadTag();
        $this->loadPorn();
    }


    
    public function load_by_unique_id($unique_id){
        
        $this->unique_id=$unique_id;

        $sql="select articles_id from articles_map where unique_id=:unique_id";
        $row = $this->conn->getOne($sql, $unique_id);
        $id = $row->articles_id;


        $this->load_by_id($id);
        

    }



    private function loadCategory(){
        // categories_name

        $id=$this->category_id;
        $sql="select name,description from categories where id=:id";
        $row = $this->conn->getOne($sql, $id);
        
        $this->_data['categories_name'] = $row->name;
        $this->_data['categories_description'] = $row->description;
    }


    private function loadName(){
        $this->_data['created_user_name'] = $this->getName($this->created_user);
        $this->_data['updated_user_name'] = $this->getName($this->updated_user);
        $this->_data['deleted_user_name'] = $this->getName($this->deleted_user);
    }



    private function loadUnique_id(){
        if(isset($this->_data['unique_id'])) return;

        $articles_id=$this->id;
        $sql="select unique_id from articles_map where articles_id=:articles_id";
        $unique_id=$this->conn->getOne($sql, $articles_id)->unique_id;

        $this->_data['unique_id']=$unique_id;

    }


    private function loadPorn(){

        $this->isPorn=0;

        if($this->flag==='P'){
            $this->isPorn=1;
            return;
        }


        if(!function_exists('config')) return;

        if( in_array($this->category_id, config('app.porn_categories') ) ){
            $this->isPorn=1;
            return;
        }
        
    }


    private function loadTag(){
        $sql="SELECT * FROM `tagging_tagged` WHERE `taggable_id`=:taggable_id";
        $taggable_id=$this->id;
        $rows = $this->conn->get($sql, $taggable_id);

        $tags=[];
        for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
            $row=$rows[$i];
            $tags[] = $row->tag_name;
        }

        $this->_data['tags']=$tags;
    }


    private function getName($user_id){
        
        if(!$user_id) return '';
        $sql="select name from users where id=:user_id";
        return $this->conn->getOne($sql, $user_id)->name;
    }


    private function handle_content($content){
        $content = $this->cdn_handle($content);

        //lazyload 
        //$content= preg_replace("#(<img[^>]*)\ssrc=([^>]+>)#", '$1 data-original= $2', $content);

        // 移除 img 的 寬高 
        $content = preg_replace("#(<img[^>]+style=\"[^\"]*)width:[^;]+;*([^>]+>)#", '$1 $2', $content);
        $content = preg_replace("#(<img[^>]+style=\"[^\"]*)height:[^;]+;*([^>]+>)#", '$1 $2', $content);

        // 針對 peer 處理 移除 srcset 因為裡面有一堆 peer的資訊 
        $content = preg_replace("#(<img[^>]+)srcset=\"[^\"]+\"([^>]+>)#", '$1 $2', $content);


        // 讓換行好看一點  增加 p  tag 
        $content = $this->wwpautop($content);


        return $content;
    }



    private function cdn_handle($content){
    
        if(!defined('__DOMAIN__')) return $content;

        if( strpos(__DOMAIN__, 'eznewlife.com') === false ) return $content;
        

        //preg_match_all('/<img[^>]*\ssrc="([^>]+)">/iU', $content, $match);
        

        $content = str_replace('/eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
        $content = str_replace('/admin.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
        $content = str_replace('/dark.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);
        $content = str_replace('/demo.eznewlife.com/focus_photos/', '/cdn.eznewlife.com/focus_photos/', $content);

        $content = str_replace('/eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
        $content = str_replace('/admin.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
        $content = str_replace('/dark.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);
        $content = str_replace('/demo.eznewlife.com/wp-content/uploads/', '/cdn.eznewlife.com/wp-content/uploads/', $content);

        $content = str_replace('/eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
        $content = str_replace('/admin.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
        $content = str_replace('/dark.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
        $content = str_replace('/demo.eznewlife.com/uploads/', '/cdn.eznewlife.com/uploads/', $content);
        $content = str_replace('"/comic/images/', '"//cdn.eznewlife.com/comic/images/', $content);

        //preg_match_all('/<img[^>]*\ssrc="([^>]+)">/', $content, $match);
        preg_match_all('#<img[^>]*\ssrc="([^>]+)"#', $content, $match);

        if(isset($match[1])){
            $ms = $match[1];
            for ($i=0,$imax=count($ms); $i < $imax; $i++) { 
                $m=$ms[$i];
                //echo $m."\n";
                if(strpos($m, 'http') !== false) continue;
                if(substr($m, 0, 2) === '//') continue;
                
                $r = $m;

                if(substr($m, 0, 1) !== '/') $r='/'.$m;

                //echo $m."\n";

                $r = 'http://cdn.eznewlife.com'.$r;
                //echo $r."\n";
                $content = str_replace($m, $r, $content);

            }
        }


        //preg_match_all('#<img[^>]*\ssrc="([^>]+)"#', $content, $match);
        //print_r($match);

        return $content;
    }



    private function wwpautop($pee, $br = true) {

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

}






