<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabEst7sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sic_tab_est7s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->string('Recebimento',41);
            $table->boolean('fixo')->nullable();
            $table->string('frEcf',41)->nullable();
            $table->string('frID',3)->nullable();
            $table->string('tipo',1)->nullable();
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
        Schema::dropIfExists('m_sic_tab_est7s');
    }
}
