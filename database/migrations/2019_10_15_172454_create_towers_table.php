<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('towers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->double('bet', 255, 2);
            $table->integer('bombs');
            $table->string('currency');
            $table->json('field');
            $table->json('revealed');
            $table->double('coeff', 255, 2)->default(0.00);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('towers');
    }
}
