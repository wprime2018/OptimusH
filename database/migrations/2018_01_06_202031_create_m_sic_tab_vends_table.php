<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabVendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sic_tab_vends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->string('Nome',46);
            $table->decimal('Comissao', 10, 3)->nullable();
            $table->datetime('DataInc');
            $table->string('Tipo',11)->nullable();
            $table->string('Codigo',14)->nullable();
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
        Schema::dropIfExists('m_sic_tab_vends');
    }
}
