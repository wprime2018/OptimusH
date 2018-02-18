<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDespesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_despesas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filial_id', false, true);
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->string('descricao',50);
            $table->integer('documento', false, true)->nullable();
            $table->enum('tp_pgto', ['Banco', 'Cheque', 'Dinheiro']);
            $table->integer('tp_desp_id', false, true);
            $table->foreign('tp_desp_id')->references('id')->on('tb_tpDespesas')->onDelete('cascade');
            $table->decimal('valor',11,2);
            $table->integer('qtde_parcelas', false, true)->length(2)->nullable();
            $table->boolean('fixa',1)->nullable();
            $table->text('obs')->nullable();
            $table->timestamp('data_pgto');
            $table->string('path_comp',191);
            $table->string('user_cad',20);
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
        Schema::dropIfExists('tb_despesas');
    }
}
