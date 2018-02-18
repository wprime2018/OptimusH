<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportFileSicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_file_sics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filial_id', false, true)->nullable();
            $table->foreign('filial_id')->references('id')->on('tb_Filiais')->onDelete('cascade');
            $table->string('path_file',191)->nullable();
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
        Schema::dropIfExists('import_file_sics');
    }
}
