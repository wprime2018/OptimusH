<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\MSicTabEst1;

class Estoque extends Model
{
    protected $fillable = [
        'filial_id', 
        'LkProduto', 
        'Atual', 
        'Minimo', 
        'Ideal', 
];

public function produto() {
    return $this->belongsTo(MSicTabEst1::Class, 'LkProduto', 'Controle')->orderBy('Codigo');
}

public function filial() {
    return $this->belongsTo(Filiais::Class, 'filial_id', 'id');
}
}
