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

            // fk provincia
            $table->unsignedBigInteger('provincia_id');
            $table->foreign('provincia_id')->references('id')->on('provincias');

            // fk canton
            $table->unsignedBigInteger('canton_id')->nullable();;
            $table->foreign('canton_id')->references('id')->on('cantones');

            // fk canton
            $table->unsignedBigInteger('distrito_id')->nullable();;
            $table->foreign('distrito_id')->references('id')->on('distritos');

            // fk barrio
            $table->unsignedBigInteger('barrio_id')->nullable();
            $table->foreign('barrio_id')->references('id')->on('barrios');

            $table->string('numero_finca')->nullable();

            $table->longText('address')->nullable();
            $table->integer('area')->nullable();
            $table->integer('agent_id');
            $table->longText('description')->nullable();
            $table->string('video')->nullable();
            $table->string('floor_plan')->nullable();
            $table->string('location_latitude')->nullable();
            $table->string('location_longitude')->nullable();
            $table->text('nearby')->nullable();
            $table->timestamps();
            $table->double('price_local', 8, 2)->nullable();
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
