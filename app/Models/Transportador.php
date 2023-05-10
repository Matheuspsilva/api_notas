<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportador extends Model
{
    use HasFactory;

    protected $table = 'transportadores';

    protected $fillable = [
        'nome',
        'cnpj'
    ];

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
