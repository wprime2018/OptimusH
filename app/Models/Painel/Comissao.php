<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    protected $table = 'comissao';
    protected $fillable = [
        'filial_id', 
        'vendas', 
        'comissao'
    ];
}
