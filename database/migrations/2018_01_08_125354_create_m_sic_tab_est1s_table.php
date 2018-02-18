<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMSicTabEst1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sic_tab_est1s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Controle', false, true)->unique();
            $table->string('Codigo',21);
            $table->string('CodInterno',21)->nullable();
            $table->string('Produto', 50, 3)->nullable();
            $table->integer('LkSetor', false, true)->nullable();
            $table->string('Fabricante',21)->nullable();
            $table->integer('LkFornec', false, true)->nullable();
            $table->decimal('PrecoCusto', 10, 3)->nullable();
            $table->decimal('CustoMedio', 10, 3)->nullable();
            $table->decimal('PrecoVenda', 10, 3)->nullable();
            $table->decimal('Quantidade', 10, 3)->nullable();
            $table->decimal('EstMinimo', 10, 3)->nullable();
            $table->string('Unidade',21)->nullable();
            $table->decimal('Lucro', 10, 3)->nullable();
            $table->decimal('Comissao', 10, 3)->nullable();
            $table->string('Moeda',6)->nullable();
            $table->datetime('UltReaj')->nullable();
            $table->string('Foto',190)->nullable();
            $table->text('Obs')->nullable();
            $table->boolean('NaoSaiTabela')->nullable();
            $table->boolean('Inativo')->nullable();
            $table->string('CodIPI',13)->nullable();
            $table->decimal('IPI', 10, 3)->nullable();
            $table->string('CST',4)->nullable();
            $table->decimal('ICMS', 10, 3)->nullable();
            $table->decimal('BaseCalculo', 10, 3)->nullable();
            $table->decimal('PesoBruto', 10, 3)->nullable();
            $table->decimal('PesoLiq', 10, 3)->nullable();
            $table->integer('LkModulo', false, true)->nullable();
            $table->string('Armazenamento',5)->nullable();
            $table->integer('QntEmbalagem', false, true)->nullable();
            $table->boolean('ELV')->nullable();
            $table->datetime('Previsao')->nullable();
            $table->datetime('DataFoto')->nullable();
            $table->datetime('DataInc')->nullable();
            $table->integer('LkUserInc', false, true)->nullable();
            $table->string('CodEx',21)->nullable();
            $table->decimal('IVA_ST', 10, 3)->nullable();
            $table->decimal('PFC', 10, 3)->nullable();
            $table->string('IPI_CST',3)->nullable();
            $table->decimal('IPI_BaseCalc', 10, 3)->nullable();
            $table->string('IPPT',2)->nullable();
            $table->string('IAT',2)->nullable();
            $table->datetime('DataUltMov')->nullable();
            $table->string('EAD',173)->nullable();
            $table->string('cEAN',15)->nullable();
            $table->string('cEANTrib',15)->nullable();
            $table->integer('cProdANP', false, true)->nullable();
            $table->string('CEST',8)->nullable();
            $table->integer('Origem', false, true)->nullable();
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
        Schema::dropIfExists('m_sic_tab_est1s');
    }
}
