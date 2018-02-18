<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class ImportFileSic extends Model
{
    protected $fillable = [
        'filial_id','path_file'
    ];
}
