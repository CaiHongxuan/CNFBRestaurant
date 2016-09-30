<?php

use Illuminate\Database\Seeder;

class CateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    	DB::table('cnfb_cate')->delete();

    	for($i=0; $i < 10; $i++)
    	{
    		\App\Cate::create([
				'name'   => '牛排 ' . $i,
    		]);
    	}
    }
}
