<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arPermissions = [
            "1" => ["HomeController@index", "Trang chủ"],

            "2" => ["SettingController", "Cấu hình công ty"],

            "3" => ["RolesController@index", "Quản lý Vai trò"],
            "4" => ["RolesController@show", "Quản lý Vai trò"],
            "5" => ["RolesController@store", "Quản lý Vai trò"],
            "6" => ["RolesController@update", "Quản lý Vai trò"],
            "7" => ["RolesController@destroy", "Quản lý Vai trò"],
            "8" => ["RolesController@active", "Quản lý Vai trò"],

            "9" => ["UsersController@index", "Quản lý nhân viên"],
            "10" => ["UsersController@show", "Quản lý nhân viên"],
            "11" => ["UsersController@store", "Quản lý nhân viên"],
            "12" => ["UsersController@update", "Quản lý nhân viên"],
            "13" => ["UsersController@destroy", "Quản lý nhân viên"],
            "14" => ["UsersController@active", "Quản lý nhân viên"],

            //Trường hợp cho phép người dùng sửa, thì cho phép sửa profile của người dùng đó
            "15" => ["UsersController@postProfile", "Quản lý nhân viên"],

            "16" => ["CategoryController@index", "Quản lý danh mục"],
            "17" => ["CategoryController@show", "Quản lý danh mục"],
            "18" => ["CategoryController@store", "Quản lý danh mục"],
            "19" => ["CategoryController@update", "Quản lý danh mục"],
            "20" => ["CategoryController@destroy", "Quản lý danh mục"],
            "21" => ["CategoryController@active", "Quản lý danh mục"],

            "22" => ["NewsController@index", "Quản lý tin tức"],
            "23" => ["NewsController@show", "Quản lý tin tức"],
            "24" => ["NewsController@store", "Quản lý tin tức"],
            "25" => ["NewsController@update", "Quản lý tin tức"],
            "26" => ["NewsController@destroy", "Quản lý tin tức"],
            "27" => ["NewsController@active", "Quản lý tin tức"],


            "28" => ["ProvinceController@index", "Quản lý tỉnh thành phố"],
            "29" => ["ProvinceController@show", "Quản lý tỉnh thành phố"],
            "30" => ["ProvinceController@store", "Quản lý tỉnh thành phố"],
            "31" => ["ProvinceController@update", "Quản lý tỉnh thành phố"],
            "32" => ["ProvinceController@destroy", "Quản lý tỉnh thành phố"],

            "33" => ["DistrictController@index", "Quản lý quận huyện"],
            "34" => ["DistrictController@show", "Quản lý quận huyện"],
            "35" => ["DistrictController@store", "Quản lý quận huyện"],
            "36" => ["DistrictController@update", "Quản lý quận huyện"],
            "37" => ["DistrictController@destroy", "Quản lý quận huyện"],

            "38" => ["WardController@index", "Quản lý phường xã"],
            "39" => ["WardController@show", "Quản lý phường xã"],
            "40" => ["WardController@store", "Quản lý phường xã"],
            "41" => ["WardController@update", "Quản lý phường xã"],
            "42" => ["WardController@destroy", "Quản lý phường xã"],


        ];

        //ADD PERMISSIONS - Thêm các quyền
        DB::table('permissions')->delete(); //empty permission
        $addPermissions = [];
        foreach ($arPermissions as $name => $label) {
            $addPermissions[] = [
                'id' => $name,
                'name' => $label[0],
                'label' => $label[1],
            ];
        }
        \DB::table('permissions')->insert($addPermissions);

        //ADD ROLE - Them vai tro
        DB::table( 'roles' )->delete();//empty permission
        $datenow = date('Y-m-d H:i:s');
        $role = [
            ['id' => 1, 'name' => 'admin', 'label' => 'Admin', 'created_at' => $datenow, 'updated_at' => $datenow],
            ['id' => 2, 'name' => 'user', 'label' => 'User', 'created_at' => $datenow, 'updated_at' => $datenow],
            ['id' => 3, 'name' => 'company', 'label' => 'Company', 'created_at' => $datenow, 'updated_at' => $datenow],
        ];
       $addRoles = [];
       foreach ($role as $key => $label) {
           $addRoles[] = [
               'id' => $label['id'],
               'name' => $label['name'],
               'label' => $label['label'],
               'created_at' => $datenow,
               'updated_at' => $datenow,
           ];
       }
       //KIỂM TRA VÀ THÊM CÁC VAI TRÒ TRUYỀN VÀO NẾU CÓ
        DB::table('roles')->insert($addRoles);

        //BỔ SUNG ID QUYỀN NẾU CÓ
        //Full quyền Admin công ty
        $persAdmin = \App\Models\Permission::pluck('id');

        //Quyền cộng tác viên (vendor)
        $persVendor = [
            1, 2, 3
        ];

        //Quyền khách hàng
        $persCustomer = [
            1, 2, 3
        ];

        //Gán quyền vào Vai trò Admin
        $rolePerAdminCompany = \App\Models\Role::findOrFail(1);
        $rolePerAdminCompany->permissions()->sync($persAdmin);

        //Gán quyền vào Vai trò User
        $rolePerAgentEmployee = \App\Models\Role::findOrFail(2);
        $rolePerAgentEmployee->permissions()->sync($persVendor);

        //Gán quyền vào Vai trò Company
        $rolePerCustomer = \App\Models\Role::findOrFail(3);
        $rolePerCustomer->permissions()->sync($persCustomer);

        //Set tài khoản ID=1 và ID=2 là Admin
        $roleAdmin = User::findOrFail(2);
        $roleAdmin->roles()->sync([1]);

       
    }
}
