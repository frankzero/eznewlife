<?php 

class __pagebar{

    public $maxPage;

    public $size=10;

    public function __construct($maxPage, $currentPage, $size=null){
        
        $this->maxPage = $maxPage;

        $this->currentPage=$currentPage;

        if($size!==null) $this->size=$size;

    }



    public function bar_KILL(){


        $data=$this->barData();
        $rs=[];
        for ($i=0,$imax=count($data); $i < $imax; $i++) { 
            $n=$data[$i];
            $rs[]=$this->makeItem($n);
        }



        return $rs;
    }



    public function bar(){
        $ps = [];

        $n = $this->currentPage;
        $ps[]= $n; 
        $c=0;

        // 往前找4個
        for($i=4; $i > 0 ; $i--){
            $n--;
            if($n < 1) break;

            array_unshift($ps, $n);
            $c++;
        }


        $c = 10+4-$c;


        $n = $this->currentPage;
        //往後找5個 
        for($i=6; $i<=$c; $i++){
            $n++;

            if($n > $this->maxPage) break;

            array_push($ps, $n);

        }


        if(count($ps) === 0) return [];


        $rs=[];
        for ($i=0,$imax=count($ps); $i < $imax; $i++) { 
            $n=$ps[$i];
            $rs[]=$this->makeItem($n);
        }


        if($ps[0] != 1){
            array_unshift($rs, $this->makeItem('', '', '...'));
            array_unshift($rs, $this->makeItem(1));
        }



        if($rs[0]->active !== 1){
            array_unshift($rs, $this->makeItem($this->currentPage-1, $this->currentPage-1, '上一頁' ) );
        }else{
            //array_unshift($rs, $this->makeItem('', '', '上一頁' ) );
        }



        if($ps[count($ps)-1] != $this->maxPage){
            array_push($rs, $this->makeItem('', '', '...'));
            array_push($rs, $this->makeItem($this->maxPage));
        }


        if($rs[count($rs)-1]->active !== 1){
            array_push($rs, $this->makeItem($this->currentPage+1, $this->currentPage+1, '下一頁'));
        }else{
            //array_push($rs, $this->makeItem('', '', '下一頁'));
        }

        return $rs;
        
    }


    private function barData(){

        $size = 10;
        $maxPage = $this->maxPage;
        $n = $this->currentPage;

        $page = floor( ($n-1)/$size) + 1;

        $from = $size*($page-1) + 1;

        $to = $to = $from + $size -1;
        if($to > $this->maxPage) $to = $this->maxPage;


        $previous = $from-1;
        if($previous <=0 ) $previous='*';

        $next = $to+1;
        if($next > $this->maxPage) $next='*';

        $rs=[];

        $rs[]=$previous;

        for ($i=$from,$imax=$from+$size; $i < $imax; $i++) { 
            if($i<=$to) $rs[]=$i;
            else $rs[]='*';
        }

        $rs[]=$next;

        return $rs;

    }


    private function exists($n){

        if($n < 1) return false;

        if($n > $this->maxPage) return false;

        return true;
    }


     private function makeItem($n, $number=null, $text=null){

        $item = new stdClass;

        /*
        if($n==='*'){
            $item->active=0;
            $item->number='';
            $item->text='';
            return $item;
        }
        */

        $item->active = ($n == $this->currentPage ? 1 : 0);

        $item->number = ($number===null ? $n : $number);

        $item->text = $text === null ? $n : $text;

        return $item;

    }
}
