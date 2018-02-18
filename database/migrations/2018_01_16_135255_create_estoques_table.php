<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filial_id', false, true);
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->integer('LkProduto', false, true)->nullable();
            $table->foreign('LkProduto')->references('Controle')->on('m_sic_tab_est1s')->onDelete('cascade');
            $table->decimal('Atual', 10, 3)->nullable();
            $table->decimal('Minimo', 10, 3)->nullable();
            $table->decimal('Ideal', 10, 3)->nullable();
            $table->decimal('Vendidos', 10, 3)->nullable();
            $table->decimal('Comprar', 10, 3)->nullable();
            $table->string('Status',20)->nullable();
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
        Schema::dropIfExists('estoques');
    }
}
