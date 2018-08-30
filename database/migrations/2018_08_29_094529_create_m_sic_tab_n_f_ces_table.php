<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabNFCesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabnfce', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->integer('filial_id', false, true);
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->integer('LkEst3A', false, true);
            $table->foreign('LkEst3A')->references('Controle')->on('m_sic_tab_est3_as')->onDelete('cascade');
            $table->timestamp('DataHora');
            $table->integer('Ambiente', false, true);
            $table->integer('numero', false, true);
            $table->string('serie',3);
            $table->string('chave',50);
            $table->string('recibo',16);
            $table->timestamp('emitida');
            $table->timestamp('cancelada');
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
        Schema::dropIfExists('m_sic_tab_n_f_ces');
    }
}
