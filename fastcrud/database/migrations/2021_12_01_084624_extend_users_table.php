<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ExtendUsersTable extends Migration
{
    private $defaultAdminEmail = 'admin@example.com';
    private $defaultAdminPassword = 'secret';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table){
            $table->boolean('is_admin')->default(false);
        });

        $now = Carbon::now();

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => $this->defaultAdminEmail,
            'password'  => Hash::make($this->defaultAdminPassword),
            'is_admin' => true,
            'created_at' => $now,
            'updated_at' => $now,
            'email_verified_at' => $now
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table){
            $table->dropColumn('is_admin');
        });

        DB::table('users')
            ->where('email', $this->defaultAdminEmail)
            ->delete();
    }
}
