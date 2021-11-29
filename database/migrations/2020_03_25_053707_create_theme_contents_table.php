<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemeContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100)->nullable();
            $table->string('sub_title', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->text('link_url')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('theme_component_id');
            $table->timestamps();

            $table->foreign('theme_component_id')
                ->references('id')
                ->on('theme_components')
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
        Schema::dropIfExists('theme_contents');
    }
}
