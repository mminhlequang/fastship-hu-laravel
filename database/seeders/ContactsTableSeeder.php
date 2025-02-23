<?php

use App\Contact;
use Illuminate\Database\Eloquent\Model;
namespace Database\Seeders;
class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        for ($id = 0; $id <= 3; $id++){
            Contact::create([
                'id' => $id + 1,
                'fullname' => "Trần Đình Giang $id",
                'email' => "demo.it$id@gmail.com",
                'message' => "Góp ý và liên hệ $id"
            ]);
        }
    }
}
