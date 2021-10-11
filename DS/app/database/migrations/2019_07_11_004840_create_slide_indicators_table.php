<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlideIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide_indicators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('x');
            $table->smallInteger('y');
            $table->integer('indicator_id');
            $table->integer('slide_id');
            $table->timestamps();

            $table->unique(['x', 'y', 'slide_id']);

            $table->foreign('indicator_id')
                ->references('id')
                ->on('indicators')
                ->onDelete('cascade');

            $table->foreign('slide_id')
                ->references('id')
                ->on('slides')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide_indicators');
    }
}
