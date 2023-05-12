<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Destinatario;
use App\Models\DTO\NotaDTO;
use App\Models\Nota;
use App\Models\Transportador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    private BuscarRemetenteService $buscarRemetenteService;

    public function __construct()
    {
        $this->buscarRemetenteService = new BuscarRemetenteService();
    }

    public function listarNotasRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);
            $notas = $remetente->notas;

            $notasDTO = array();
            foreach ($notas as $nota){

                $trasportador = Transportador::whereId($nota->transportador_id)->first();
                $destinatario = Destinatario::whereId($nota->destinatario_id)->first();

                $notaDto = new NotaDTO(
                    $nota->chave,
                    $nota->numero,
                    $remetente->cnpj,
                    $remetente->nome,
                    $trasportador->nome,
                    $trasportador->cnpj,
                    $nota->status,
                    $nota->valor, $nota->volumes,
                    $nota->dt_emis,
                    $nota->dt_entrega,
                    $destinatario->nome,
                    $destinatario->cod
                );

                array_push($notasDTO,  $notaDto);

            }

            return response()->json($notasDTO, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function valorTotalNotasRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);
            $notas = $remetente->notas;

            $sum = $notas->sum('valor');

            $data = ['data' => $sum];
            return response()->json($data, 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    public function valorTotalNotasRemetenteEntregado(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);

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
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);

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
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);

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
