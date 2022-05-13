<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zips', function (Blueprint $table) {
            $table->id();
            $table->string('d_codigo')->index('zipcode')->nullable(false);
            $table->string('d_asenta')->nullable(true)->default('');
            $table->string('d_tipo_asenta')->nullable(true)->default('');
            $table->string('D_mnpio')->nullable(true)->default('');
            $table->string('d_estado')->nullable(true)->default('');
            $table->string('d_ciudad')->nullable(true)->default('');
            $table->integer('d_CP')->nullable(true);
            $table->integer('c_estado')->nullable(true);
            $table->integer('c_oficina')->nullable(true);
            $table->integer('c_CP')->nullable(true);
            $table->integer('c_tipo_asenta')->nullable(true);
            $table->integer('c_mnpio')->nullable(true);
            $table->integer('id_asenta_cpcons')->nullable(true);
            $table->string('d_zona')->nullable(true)->default('');
            $table->integer('c_cve_ciudad')->nullable(true);
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
        Schema::dropIfExists('zips');
    }
};
