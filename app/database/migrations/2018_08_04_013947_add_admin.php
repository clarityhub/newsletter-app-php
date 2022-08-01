<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::table('users')->insert(
                array(
                    'name' => 'Ivan Montiel',
                    'email' => 'ivan@clarityhub.io',
                    'password' => Hash::make('clarity_hub_1'),
                    'type' => 'admin'
                )
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::table('users')->where('email', '=', 'ivan@clarityhub.io')->delete();
        });
    }
}
