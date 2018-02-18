<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\Despesas;

class TpDespesas extends Model
{
    public function despesas()
    {
        return $this->hasMany(Despesas::class);
    }

    protected $table = 'tb_tpdespesas';
    
    protected $fillable = [
            'descricao',
            'compartilhada'
            ];
}
