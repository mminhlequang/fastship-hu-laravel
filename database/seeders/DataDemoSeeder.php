<?php

namespace Database\Seeders;
class DataDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    try {
		    //Agent demo
		    \App\Agent::create( [
			    "id"    => 1,
			    "name"  => "Dai ly 1",
			    "email" => "daily1@gmail.com"
		    ] );
		    \App\Agent::create( [
			    "id"    => 2,
			    "name"  => "Dai ly 2",
			    "email" => "daily2@gmail.com"
		    ] );

		    //User demo
		    \App\User::create( [
			    "name"       => "Nhan vien cong ty",
			    "email"      => "nvct@gmail.com",
			    "username"   => "nhanviencongty",
			    "password"   => bcrypt( env( 'ADMIN_PWD', '123456' ) )
		    ] );
		    \App\User::create( [
			    "name"       => "Nhan vien dai ly 1",
			    "email"      => "nvdl1@gmail.com",
			    "username"   => "nhanviendaily1",
			    "agent_id"   => 1,
			    "password"   => bcrypt( env( 'ADMIN_PWD', '123456' ) )
		    ] );
		    \App\User::create( [
			    "name"       => "Nhan vien dai ly 2",
			    "email"      => "nvdl2@gmail.com",
			    "username"   => "nhanviendaily2",
			    "agent_id"   => 2,
			    "password"   => bcrypt( env( 'ADMIN_PWD', '123456' ) )
		    ] );

		    //Place demo
		    foreach (['Huế', 'Đà nẵng'] as $id=>$city){
			    \App\City::create([
			    	'id' => $id + 1,
				    'name' => $city
			    ]);
		    }
		    /*
		     INSERT INTO `bookings` VALUES ('1', '1', '2', '1', '2018-08-10', '1', '2', '660000', '6 le loi, hue', 'co tre em', null, '5', '2018-08-10 13:15:23', '2018-08-10 13:15:24', null), ('2', '2', '3', '2', '2018-08-11', '1', '0', '600000', '5 ba trieu, hue', null, null, '5', '2018-08-10 13:17:16', '2018-08-10 13:17:21', null);
		    INSERT INTO `booking_detail` VALUES ('1', '220000', '1', '1', 'App\\Journey'), ('2', '600000', '2', '7', 'App\\Tour');
		     */

	    }catch (\Illuminate\Database\QueryException $exception){
			echo $exception->getMessage();
	    }
    }
}
