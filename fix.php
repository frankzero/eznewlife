<?php 
namespace fix;
use function \ff\conn;

require __DIR__.'/frank/autoload.php';

handle($argv);


function handle($argv){

    $class_name = '\\fix\\'.$argv[1];
    if(class_exists($class_name)){
        new $class_name;
        return;
    }

}



function fix_article(){



    $conn=ff\conn();

    $db_user='eznewlife_miu';
    $db_host='10.110.13.238';
    $db_password='YUSlxDa6EA';
    $db_name='eznewlife_miu';

    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $conn_ad = new \__mypdo($dsn, $db_user, $db_password);




    //$sql="SELECT * FROM `articles` WHERE title LIKE '%?%' and title not like '%&#%'";
    $sql="SELECT * FROM `articles` WHERE title LIKE '%\?%'";

    // ＃？＆％／

    $rows = $conn->get($sql);
    //echo count($rows);exit;


    for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
        $row=$rows[$i];
        $id = $row['id'];

        //if($id !== '2575') continue;

        echo $row['id'];
        //echo ' '.$row['title'];
        echo ' '.$row['photo'];
        //echo ' '.str_replace('#', '＃', $row['title'] );
        echo "\n";

        
        $title=str_replace('?', '？', $row['title'] );



        $sql="update articles SET title=:title WHERE id=:id";
        $conn->update($sql, $title, $id);
        //break;

    }
}






class scanhack{

    private $count=0;

    public $results=[];

    private $fixcmd=[];

    function __construct(){
        
        $this->scan(__DIR__);

        echo "\n".count($this->results);

        file_put_contents(__DIR__.'/storage/scanhack', implode("\n", $this->results));
        file_put_contents(__DIR__.'/storage/fixhack.sh', implode("\n", $this->fixcmd));
        //print_r($this->results);
    }





    function scan($file){
        $fs = glob($file.'/*');

        for ($i=0,$imax=count($fs); $i < $imax; $i++) { 
            $f=$fs[$i];

            if(is_dir($f)){
                $this->scan($f);
                continue;
            }

            $this->handle($f);
        }
    }
 



    private function handle($file){

        $this->count++;
        echo "\r".$this->count;


        if( strpos($file, '.php') === false
            && strpos($file, '.html') === false
            && strpos($file, '.tpl') === false
            ) return;
        
        $fp = @fopen($file, "r");

        if(!$fp) return;

        $c=0;

        while($buffer = fgets($fp, 4096)){
            
            $c++;
            if( strpos($buffer, '<?php') !== false ){
                if( strlen($buffer) > 2000 ){
                    $this->results[] = $file;
                    $this->fixcmd[]="rsync  -av --rsh='ssh -p22' root@66.228.38.243:$file $file";
                    //echo $file."\n";
                }
                break;
            }

            if($c > 5) break;
            
        }

        fclose($fp);

    }

}