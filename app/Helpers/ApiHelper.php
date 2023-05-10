<?php

namespace App\Helpers;

use App\Models\Destinatario;
use App\Models\Nota;
use App\Models\Remetente;
use App\Models\Transportador;
use Illuminate\Support\Facades\Http;

class ApiHelper
{
    public static function callApi()
    {
        $response = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');

        $content = $response->getBody()->getContents();

        $data = json_decode($content);

            foreach ($data as $nota) {

                $destinatario = new Destinatario();

                if(!Destinatario::where('cod', $nota->dest->cod)->exists()){
                    $destinatario->cod = $nota->dest->cod;
                    $destinatario->nome = $nota->dest->nome;
                    $destinatario->save();

                }else{
                    $destinatario = Destinatario::where('cod', $nota->dest->cod)->first();
                }


                $remetente = new Remetente();

                if(!Remetente::where('cnpj', $nota->cnpj_remete)->exists()){
                    $remetente->nome = $nota->nome_remete;
                    $remetente->cnpj = $nota->cnpj_remete;
                    $remetente->save();

                }else{
                    $remetente = Remetente::where('cnpj', $nota->cnpj_remete)->first();
                }


                $transportador = new Transportador();

                $transportador->nome = $nota->nome_transp;
                $transportador->cnpj = $nota->cnpj_transp;
                $transportador->save();

                if(!Transportador::where('cnpj', $nota->cnpj_remete)->exists()){
                    $transportador->nome = $nota->nome_transp;
                    $transportador->cnpj = $nota->cnpj_transp;
                    $transportador->save();

                }else{
                    $transportador = Transportador::where('cnpj', $nota->cnpj_transp)->first();
                }

                if(!Nota::where('chave', $nota->chave)->exists()){
                    $novaNota = new Nota();
                    $novaNota->chave = $nota->chave;
                    $novaNota->numero = $nota->numero;
                    $novaNota->status = $nota->status;
                    $novaNota->volumes = $nota->volumes;
                    $novaNota->valor = $nota->valor;

                    $novaNota->setdataEmissaoAttribute($nota->dt_emis);
                    if (isset($nota->dt_entrega)) {
                        $novaNota->setdataEntregaAttribute($nota->dt_entrega);
                    }

                    $novaNota->destinatario()->associate($destinatario);
                    $novaNota->transportador()->associate($transportador);
                    $novaNota->remetente()->associate($remetente);

                    $novaNota->save();        $novaNota = new Nota();
                    $novaNota->chave = $nota->chave;
                    $novaNota->numero = $nota->numero;
                    $novaNota->status = $nota->status;
                    $novaNota->volumes = $nota->volumes;
                    $novaNota->valor = $nota->valor;

                    $novaNota->setdataEmissaoAttribute($nota->dt_emis);
                    if (isset($nota->dt_entrega)) {
                        $novaNota->setdataEntregaAttribute($nota->dt_entrega);
                    }


                }else{
                    $novaNota = Nota::where('chave', $nota->chave)->first();
                }

                $novaNota->destinatario()->associate($destinatario);
                $novaNota->transportador()->associate($transportador);
                $novaNota->remetente()->associate($remetente);

                $novaNota->save();
            }
    }
}
