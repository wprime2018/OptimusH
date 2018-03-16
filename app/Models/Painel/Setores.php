<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\MSicTabEst1;

class tb_setores extends Model
{
    public function produtos()
    {
        return $this->hasMany(MSicTabEst1::class);
    }

    protected $table = 'tb_setores';
    
    protected $fillable = [ 'Controle', 'Setor'];
}
