<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->double('price', 8, 2)->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('purpose', ['venta', 'renta']);
            $table->enum('type', ['casa', 'apartamento','edificio']);
            $table->string('image')->nullable();
            $table->integer('bedroom');
            $table->integer('bathroom');
            $table->string('city');
            $table->string('city_slug');
            // fk doctor
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('address')->nullable();
            $table->integer('area')->nullable();
            $table->integer('agent_id');
            $table->longText('description')->nullable();
            $table->string('video')->nullable();
            $table->string('floor_plan')->nullable();
            $table->string('location_latitude')->nullable();
            $table->string('location_longitude')->nullable();
            $table->text('nearby')->nullable();
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
        Schema::dropIfExists('properties');
    }
}
