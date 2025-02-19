User
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->foreign('user_id')->references('userID')->on('users');
            $table->boolean('visible')->default(true); // Add visibility column with default value true
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
