<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('contact_type')->nullable()->comment('Type of contact, e.g., individual, company');
            $table->string('contact_full_name')->nullable()->comment('Full name of the contact');
            $table->string('contact_company')->nullable()->comment('Name of the company');
            $table->string('contact_company_address')->nullable()->comment('Address of the company');
            $table->string('contact_phone')->nullable()->comment('Phone number of the contact');
            $table->string('contact_email')->nullable()->comment('Email address of the contact');
            $table->string('contact_card_id')->nullable()->comment('ID of the contact card');
            $table->string('contact_card_id_issue_date')->nullable()->comment('Issue date of the contact card');
            $table->string('contact_card_id_image_front')->nullable()->comment('Front image of the contact card');
            $table->string('contact_card_id_image_back')->nullable()->comment('Back image of the contact card');
            $table->string('contact_image_license')->nullable()->comment('License image of the contact');
            $table->string('contact_tax')->nullable()->comment('Tax ID or tax number');
            $table->string('avatar_image')->nullable()->comment('Path to the avatar image');
            $table->string('facade_image')->nullable()->comment('Path to the facade image');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
}
