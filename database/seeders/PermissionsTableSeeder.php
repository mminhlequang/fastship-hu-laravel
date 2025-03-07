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

            "3" => ["RolesController@index", "Vai trò"],
            "4" => ["RolesController@show", "Vai trò"],
            "5" => ["RolesController@store", "Vai trò"],
            "6" => ["RolesController@update", "Vai trò"],
            "7" => ["RolesController@destroy", "Vai trò"],
            "8" => ["RolesController@active", "Vai trò"],

            "9" => ["UsersController@index", "Tài khoản"],
            "10" => ["UsersController@show", "Tài khoản"],
            "11" => ["UsersController@store", "Tài khoản"],
            "12" => ["UsersController@update", "Tài khoản"],
            "13" => ["UsersController@destroy", "Tài khoản"],
            "14" => ["UsersController@active", "Tài khoản"],

            //Trường hợp cho phép người dùng sửa, thì cho phép sửa profile của người dùng đó
            "15" => ["UsersController@postProfile", "Tài khoản"],

            "16" => ["NotificationController@index", "Thông báo"],
            "17" => ["NotificationController@show", "Thông báo"],
            "18" => ["NotificationController@store", "Thông báo"],
            "19" => ["NotificationController@update", "Thông báo"],
            "20" => ["NotificationController@destroy", "Thông báo"],

            "21" => ["NewsController@index", "Tin tức"],
            "22" => ["NewsController@show", "Tin tức"],
            "23" => ["NewsController@store", "Tin tức"],
            "24" => ["NewsController@update", "Tin tức"],
            "25" => ["NewsController@destroy", "Tin tức"],
            "26" => ["NewsController@active", "Tin tức"],

            "27" => ["CategoryController@index", "Danh mục"],
            "28" => ["CategoryController@show", "Danh mục"],
            "29" => ["CategoryController@store", "Danh mục"],
            "30" => ["CategoryController@update", "Danh mục"],
            "31" => ["CategoryController@destroy", "Danh mục"],
            "32" => ["CategoryController@active", "Danh mục"],

            "33" => ["ProductController@index", "Sản phẩm"],
            "34" => ["ProductController@show", "Sản phẩm"],
            "35" => ["ProductController@store", "Sản phẩm"],
            "36" => ["ProductController@update", "Sản phẩm"],
            "37" => ["ProductController@destroy", "Sản phẩm"],
            "38" => ["ProductController@active", "Sản phẩm"],

            "39" => ["CustomerController@index", "Khách hàng"],
            "40" => ["CustomerController@show", "Khách hàng"],
            "41" => ["CustomerController@store", "Khách hàng"],
            "42" => ["CustomerController@update", "Khách hàng"],
            "43" => ["CustomerController@destroy", "Khách hàng"],
            "44" => ["CustomerController@active", "Khách hàng"],

            "45" => ["DriverController@index", "Tài xế"],
            "46" => ["DriverController@show", "Tài xế"],
            "47" => ["DriverController@store", "Tài xế"],
            "48" => ["DriverController@update", "Tài xế"],
            "49" => ["DriverController@destroy", "Tài xế"],
            "50" => ["DriverController@active", "Tài xế"],

            "51" => ["PartnerController@index", "Cộng tác viên"],
            "52" => ["PartnerController@show", "Cộng tác viên"],
            "53" => ["PartnerController@store", "Cộng tác viên"],
            "54" => ["PartnerController@update", "Cộng tác viên"],
            "55" => ["PartnerController@destroy", "Cộng tác viên"],
            "56" => ["PartnerController@active", "Cộng tác viên"],


            "57" => ["BookingController@index", "Đơn hàng"],
            "58" => ["BookingController@show", "Đơn hàng"],
            "59" => ["BookingController@store", "Đơn hàng"],
            "60" => ["BookingController@update", "Đơn hàng"],
            "61" => ["BookingController@destroy", "Đơn hàng"],

            "62" => ["DiscountController@index", "Mã giảm giá"],
            "63" => ["DiscountController@show", "Mã giảm giá"],
            "64" => ["DiscountController@store", "Mã giảm giá"],
            "65" => ["DiscountController@update", "Mã giảm giá"],
            "66" => ["DiscountController@destroy", "Mã giảm giá"],

            "67" => ["AddressDeliveryController@index", "Địa chỉ giao hàng"],
            "68" => ["AddressDeliveryController@show", "Địa chỉ giao hàng"],
            "69" => ["AddressDeliveryController@store", "Địa chỉ giao hàng"],
            "70" => ["AddressDeliveryController@update", "Địa chỉ giao hàng"],
            "71" => ["AddressDeliveryController@destroy", "Địa chỉ giao hàng"],


            "72" => ["ApproveController@index", "Trạng thái đơn hàng"],
            "73" => ["ApproveController@show", "Trạng thái đơn hàng"],
            "74" => ["ApproveController@store", "Trạng thái đơn hàng"],
            "75" => ["ApproveController@update", "Trạng thái đơn hàng"],
            "76" => ["ApproveController@destroy", "Trạng thái đơn hàng"],


            "77" => ["TransactionController@index", "Giao dịch"],
            "78" => ["TransactionController@show", "Giao dịch"],
            "79" => ["TransactionController@store", "Giao dịch"],
            "80" => ["TransactionController@update", "Giao dịch"],
            "81" => ["TransactionController@destroy", "Giao dịch"],

            "82" => ["StoreController@index", "Cửa hàng"],
            "83" => ["StoreController@show", "Cửa hàng"],
            "84" => ["StoreController@store", "Cửa hàng"],
            "85" => ["StoreController@update", "Cửa hàng"],
            "86" => ["StoreController@destroy", "Cửa hàng"],
            "87" => ["StoreController@active", "Cửa hàng"],

            "88" => ["BannerController@index", "Banner"],
            "89" => ["BannerController@show", "Banner"],
            "90" => ["BannerController@store", "Banner"],
            "91" => ["BannerController@update", "Banner"],
            "92" => ["BannerController@destroy", "Banner"],
            "93" => ["BannerController@active", "Banner"],


            "94" => ["ProvinceController@index", "Tỉnh thành phố"],
            "95" => ["ProvinceController@show", "Tỉnh thành phố"],
            "96" => ["ProvinceController@store", "Tỉnh thành phố"],
            "97" => ["ProvinceController@update", "Tỉnh thành phố"],
            "98" => ["ProvinceController@destroy", "Tỉnh thành phố"],

            "99" => ["DistrictController@index", "Quận huyện"],
            "100" => ["DistrictController@show", "Quận huyện"],
            "101" => ["DistrictController@store", "Quận huyện"],
            "102" => ["DistrictController@update", "Quận huyện"],
            "103" => ["DistrictController@destroy", "Quận huyện"],

            "104" => ["WardController@index", "Phường xã"],
            "105" => ["WardController@show", "Phường xã"],
            "106" => ["WardController@store", "Phường xã"],
            "107" => ["WardController@update", "Phường xã"],
            "108" => ["WardController@destroy", "Phường xã"],

            "109" => ["ContactController@index", "Liên hệ"],
            "110" => ["ContactController@show", "Liên hệ"],
            "111" => ["ContactController@store", "Liên hệ"],
            "112" => ["ContactController@update", "Liên hệ"],
            "113" => ["ContactController@destroy", "Liên hệ"],

            "114" => ["GroupController@index", "Group topping"],
            "115" => ["GroupController@show", "Group topping"],
            "116" => ["GroupController@store", "Group topping"],
            "117" => ["GroupController@update", "Group topping"],
            "118" => ["GroupController@destroy", "Group topping"],

            "119" => ["ToppingController@index", "Topping"],
            "120" => ["ToppingController@show", "Topping"],
            "121" => ["ToppingController@store", "Topping"],
            "122" => ["ToppingController@update", "Topping"],
            "123" => ["ToppingController@destroy", "Topping"],

            "124" => ["StepController@index", "Steps"],
            "125" => ["StepController@show", "Steps"],
            "126" => ["StepController@store", "Steps"],
            "127" => ["StepController@update", "Steps"],
            "128" => ["StepController@destroy", "Steps"],

            "129" => ["ServiceController@index", "Service"],
            "130" => ["ServiceController@show", "Service"],
            "131" => ["ServiceController@store", "Service"],
            "132" => ["ServiceController@update", "Service"],
            "133" => ["ServiceController@destroy", "Service"],

        ];

        //ADD PERMISSIONS - Thêm các quyền
        \DB::table('permissions')->delete(); //empty permission
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
