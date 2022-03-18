<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->foreignIdFor(User::class);
            $table->integer('book_id')->foreignIdFor(Book::class);
            $table->timestamp('rented_at');
            $table->timestamp('returned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_logs');
    }
}
