<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabEst3AsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sic_tab_est3_as', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->integer('filial_id', false, true);
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->datetime('Data');
            $table->integer('LkTipo', false, true);
            $table->integer('Nota', false, true)->nullable();
            $table->string('Serie',2)->nullable();
            $table->integer('Pedido', false, true)->nullable();
            $table->integer('LkReceb', false, true)->nullable();
            $table->foreign('LkReceb')->references('Controle')->on('m_sic_tab_est7s')->onDelete('cascade');
            $table->integer('LkVendedor', false, true)->nullable();
            $table->foreign('LkVendedor')->references('Controle')->on('m_sic_tab_vends')->onDelete('cascade');
            $table->integer('LkCliente', false, true)->nullable();
            $table->integer('LkFornec', false, true)->nullable();
            $table->integer('TagCliente', false, true)->nullable();
            $table->decimal('Comissao', 10, 3)->nullable();
            $table->decimal('ComissaoVend', 10, 3)->nullable();
            $table->text('Obs')->nullable();
            $table->boolean('Venda',1)->nullable();
            $table->integer('LkUser', false, true)->nullable();
            $table->string('CFOP',5)->nullable();
            $table->datetime('DataNota')->nullable();
            $table->boolean('Cancelada',1)->nullable();
            $table->string('TipoDoc',2)->nullable();
            $table->boolean('Frete',1)->nullable();
            $table->decimal('ValorFrete', 10, 3)->nullable();
            $table->integer('LkTrans', false, true)->nullable();
            $table->boolean('CGI',1)->nullable();
            $table->boolean('RetTrib',1)->nullable();
            $table->integer('LkLoja', false, true)->nullable();
            $table->integer('LkCliM', false, true)->nullable();
            $table->integer('nfe', false, true)->nullable();
            $table->integer('NumCF', false, true)->nullable();
            $table->string('NFE_CHAVE_TEST',44)->nullable();
            $table->string('NFE_CHAVE_PROD',44)->nullable();
            $table->string('NFE_CHAVE',44)->nullable();
            $table->integer('NFE_AMBIENTE', false, true)->nullable();
            $table->string('ID_nota',36)->nullable();
            $table->integer('StatusPagamento', false, true)->nullable();
            $table->integer('Revenda', false, true)->nullable();
            $table->decimal('RevendaComissao', 10, 3)->nullable();
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
        Schema::dropIfExists('m_sic_tab_est3_as');
    }
}
