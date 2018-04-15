<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\MSicTabEst3A;

class MSicTabEst7 extends Model
{
    protected $fillable = [
        'Controle',
        'Recebimento',
        'Fixo',
        'frEcf',
        'frID'
    ];
    public function vendas() {
        return $this->hasMany(MSicTabEst3A::Class, 'LkReceb', 'Controle')->orderBy('filial_id');
    }
}
