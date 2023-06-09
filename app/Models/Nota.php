<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'chave',
        'numero',
        'status',
        'volumes',
        'valor',
        'dt_emis',
        'dt_entrega',
        'remetente_id',
        'transportador_id',
        'destinatario_id'
    ];

    public function destinatario()
    {
        return $this->belongsTo(Destinatario::class);
    }

    public function remetente()
    {
        return $this->belongsTo(Remetente::class);
    }

    public function transportador()
    {
        return $this->belongsTo(Transportador::class);
    }

    public function setdataEmissaoAttribute($value)
    {
        $this->attributes['dt_emis'] = Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s');
    }
    public function setdataEntregaAttribute($value)
    {
        $this->attributes['dt_entrega'] = Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s');
    }

    public function getDataEmissaoAttribute()
    {
        $value = $this->attributes['dt_emis'];
        $carbon = \Illuminate\Support\Carbon::parse($value);
        return $carbon;
    }
    public function getDataEntregaAttribute()
    {
        $value = $this->attributes['dt_entrega'];
        $carbon = \Illuminate\Support\Carbon::parse($value);
        return $carbon;
    }

    public function notaEntregue(){

        if($this->getDataEntregaAttribute()->lessThan($this->getDataEmissaoAttribute()->addDays(2)) && $this->attributes['status'] == 'COMPROVADO' )
            return true;

        return false;
    }
}
