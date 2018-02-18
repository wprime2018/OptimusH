<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiliaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_filiais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',10);
            $table->string('fantasia',30);
            $table->string('razao_social',60);
            $table->string('cep',9);
            $table->string('logradouro',40);
            $table->integer('numero', false, true)->length(6);
            $table->string('compl',30)->nullable();
            $table->string('bairro',30);
            $table->string('cidade',30);
            $table->string('estado',2);
            //$table->integer('cnpj', false, true)->length(14);
            $table->Integer('ibge',false,true);
            $table->bigInteger('cnpj')->unsigned()->nullable();
            $table->integer('ie', false, true)->length(10)->nullable();
            $table->bigInteger('im',false,true)->nullable();
            $table->boolean('ativo');
            $table->timestamps();
            $table->index('codigo');
            $table->index(['fantasia','razao_social']);
            $table->unique('cnpj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_filiais');
    }
}
