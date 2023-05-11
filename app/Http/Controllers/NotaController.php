<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Nota;
use App\Models\Remetente;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listarNotasRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = Remetente::where('cnpj', $remetente_id)->first();

            if(!isset($remetente)){
                throw new ModelNotFoundException('Remetente não encontrado', 404);
            }
            $notas = $remetente->notas;

            return response()->json($notas, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function valorTotalNotasRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = Remetente::where('cnpj', $remetente_id)->first();

            if(!isset($remetente)){
                throw new ModelNotFoundException('Remetente não encontrado', 404);
            }
            $notas = $remetente->notas;

            $sum = $notas->sum('valor');

            return response()->json($sum, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function valorTotalNotasRemetenteEntregado(Request $request, $remetente_id)
    {
        try {
            $remetente = Remetente::where('cnpj', $remetente_id)->first();

            if(!isset($remetente)){
                throw new ModelNotFoundException('Remetente não encontrado', 404);
            }

            $notas = Nota::where('remetente_id', $remetente->id)->where('status', 'COMPROVADO')->get();


            $sum = $notas->sum('valor');

            return response()->json($sum, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function valorTotalNotasRemetenteNaoEntregue(Request $request, $remetente_id)
    {
        try {
            $remetente = Remetente::where('cnpj', $remetente_id)->first();

            if(!isset($remetente)){
                throw new ModelNotFoundException('Remetente não encontrado', 404);
            }

            $notas = Nota::where('remetente_id', $remetente->id)->where('status', 'ABERTO')->get();


            $sum = $notas->sum('valor');

            return response()->json($sum, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function valorPerdidoAtrasosPorRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = Remetente::where('cnpj', $remetente_id)->first();

            if(!isset($remetente)){
                throw new ModelNotFoundException('Remetente não encontrado', 404);
            }

            $notas = Nota::where('remetente_id', $remetente->id)->where('status', 'COMPROVADO')->get();

            $sum = 0;
            foreach ($notas as $nota){
                if(!$nota->notaEntregue()){
                    $sum += $nota->valor;
                }
            }

            return response()->json($sum, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

}
