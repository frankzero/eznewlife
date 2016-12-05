<?php

use Illuminate\Database\Seeder;
use App\Adv;
class AdvTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advs=Adv::where('domain' ,'like','getez.info');//echo "\n count-".$count."\n ";
        $start=0;
        $times=162;
        $sql= $advs->toSql();
        echo $sql; echo $advs->count();
        $advs=$advs->get();
        foreach ($advs as $s => $adv) {
            Adv::create(array(
                    'adv_id' =>		$adv->adv_id,
                'plan' =>		$adv->plan,
                'code' =>$adv->code,
                'code_onload'=>$adv->code_onload,
                'name'=>$adv->name,
                'domain'=>'dark.eznewlife.com',
                'rate'=>$adv->rate,

            ));
        }


    }
}
