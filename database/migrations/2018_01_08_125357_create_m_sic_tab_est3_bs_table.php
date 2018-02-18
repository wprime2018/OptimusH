<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabEst3BsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sic_tab_est3_bs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->integer('filial_id', false, true);
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->integer('LkEst3A', false, true);
            $table->foreign('LkEst3A')->references('Controle')->on('m_sic_tab_est3_as')->onDelete('cascade');
            $table->decimal('Quantidade', 10, 3)->nullable();
            $table->integer('LkProduto', false, true)->nullable();
            $table->foreign('LkProduto')->references('Controle')->on('m_sic_tab_est1s')->onDelete('cascade');
            $table->decimal('Total', 10, 3)->nullable();
            $table->decimal('TotVenda', 10, 3)->nullable();
            $table->decimal('Lucro', 10, 3)->nullable();
            $table->decimal('Acrescimo', 10, 3)->nullable();
            $table->datetime('DataInc');
            $table->decimal('ICMS', 10, 3)->nullable();
            $table->decimal('QuantCanc', 10, 3)->nullable();
            $table->decimal('ValorCanc', 10, 3)->nullable();
            $table->string('CFOPProd',5)->nullable();
            $table->integer('LkPrecoProd', false, true)->nullable();
            $table->decimal('ComissaoProd', 10, 3)->nullable();
            $table->datetime('Previsao')->nullable();
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
        Schema::dropIfExists('m_sic_tab_est3_bs');
    }
}