<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('userID');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('IC')->unique();
            $table->date('date_of_birth');
            $table->enum('gender', ['female', 'male']);
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->enum('role', ['student', 'staff', 'admin']);
            $table->rememberToken();
            $table->timestamps();
        });

        // Update the existing records with hashed passwords
        DB::table('users')->update([
            'password' => bcrypt('your_default_password'),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
