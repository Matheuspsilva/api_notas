<?php

namespace App\Http\Controllers;

use App\Models\Remetente;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BuscarRemetenteService
{

    /**
     * @param $remetente_id
     * @return Remetente
     */
    public function findOrFailRemetente($remetente_id):Remetente
    {
        $remetente = Remetente::where('cnpj', $remetente_id)->first();

        if (!isset($remetente)) {
            throw new ModelNotFoundException('Remetente não encontrado', 404);
        }
        return $remetente;
    }
}
