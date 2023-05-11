<?php

namespace App\Services;

use App\Helpers\DatabaseCleaner;
use App\Models\Destinatario;
use App\Models\Nota;
use App\Models\Remetente;
use App\Models\Transportador;
use Illuminate\Support\Facades\Http;

class DatabasePopulator
{
    public static function populate()
    {
        DatabaseCleaner::cleanDatabase(['remetentes', 'transportadores', 'destinatarios', 'notas']);

        $response = Http::get('http://homologacao3.azapfy.com.br/api/ps/notas');

        $content = $response->getBody()->getContents();

        $data = json_decode($content);

            foreach ($data as $nota) {

                $destinatario = self::getDestinatario($nota);

                $remetente = self::getRemetente($nota);

                $transportador = self::getTransportador($nota);

                $novaNota = self::getNota($nota);

                $novaNota->destinatario()->associate($destinatario);
                $novaNota->transportador()->associate($transportador);
                $novaNota->remetente()->associate($remetente);

                $novaNota->save();
            }
    }

    /**
     * @param mixed $nota
     * @return Destinatario
     */
    public static function getDestinatario(mixed $nota): Destinatario
    {
        if (!Destinatario::where('cod', $nota->dest->cod)->exists()) {
            $destinatario = new Destinatario();
            $destinatario->cod = $nota->dest->cod;
            $destinatario->nome = $nota->dest->nome;
            $destinatario->save();

        } else {
            $destinatario = Destinatario::where('cod', $nota->dest->cod)->first();
        }
        return $destinatario;
    }

    /**
     * @param mixed $nota
     * @return Remetente
     */
    public static function getRemetente(mixed $nota): Remetente
    {
        if (!Remetente::where('cnpj', $nota->cnpj_remete)->exists()) {
            $remetente = new Remetente();
            $remetente->nome = $nota->nome_remete;
            $remetente->cnpj = $nota->cnpj_remete;
            $remetente->save();

        } else {
            $remetente = Remetente::where('cnpj', $nota->cnpj_remete)->first();
        }
        return $remetente;
    }

    /**
     * @param mixed $nota
     * @return Transportador
     */
    public static function getTransportador(mixed $nota): Transportador
    {
        if (!Transportador::where('cnpj', $nota->cnpj_transp)->exists()) {
            $transportador = new Transportador();
            $transportador->nome = $nota->nome_transp;
            $transportador->cnpj = $nota->cnpj_transp;
            $transportador->save();

        } else {
            $transportador = Transportador::where('cnpj', $nota->cnpj_transp)->first();
        }
        return $transportador;
    }

    /**
     * @param mixed $nota
     * @return Nota
     */
    public static function getNota(mixed $nota): Nota
    {
        if (!Nota::where('chave', $nota->chave)->exists()) {
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

        } else {
            $novaNota = Nota::where('chave', $nota->chave)->first();
        }
        return $novaNota;
    }
}
