<?php 


class api_shorturl{


    public function __construct(){

    }





    public function call(){

        $url = http::post('url');

        if(!$url) return [];

        if(strpos($url,'http')!==0){
            $url='http://'.$url;
        }

        $url = urldecode($url);

        $conn = oconn('M');

        $sql="select * from ezgo where url=:url";

        $row = $conn->getOne($sql, $url);

        if($row === false){
            $unique_id = unique_id(6);
            // $sql="INSERT INTO ezgo SET url=:url, unique_id=:unique_id";
            // $insert_id = $conn->insert($sql, $url, $unique_id);


            $sql="INSERT INTO ezgo SET url='$url', unique_id='$unique_id'";
            //echo $sql;
            $insert_id = $conn->insert($sql);
            
            if($insert_id === false){
                $r = [];
                $r['short_url']='error';
                return $r;
            }

            $short_url = http::domain().client_path(__APP__).$unique_id;
        }else{
            $short_url = http::domain().client_path(__APP__).$row->unique_id;
        }


        


        $r = [];
        $r['short_url']=$short_url;

        return $r;





    }

}