<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Destinatario;
use App\Models\DTO\NotaDTO;
use App\Models\Nota;
use App\Models\Transportador;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="API Controle de pagamentos", version="0.1")
 */
class NotaController extends Controller
{
    private BuscarRemetenteService $buscarRemetenteService;

    public function __construct()
    {
        $this->buscarRemetenteService = new BuscarRemetenteService();
    }

    /**
     * @OA\Get(
     *     tags={"Notas"},
     *     summary="Retorna uma lista de notas por remetente",
     *     description="Retorna um ou mais objetos de notas",
     *     path="/api/notas/{cnpj_remetente}",
     *      @OA\Parameter(
     *         description="Cnpj do remetente",
     *         in="path",
     *         name="cnpj_remetente",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="23326986000190", summary="CNPJ"),
     *
     *     ),
     *     @OA\Response(response="200", description="Uma lista com notas"),
     *     @OA\Response(response="404", description="Remetente não encontrado"),
     *     @OA\Response(response="500", description="Erro interno do servidor"),
     * ),
     *
     */
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

    /**
     * @OA\Get(
     *     tags={"Notas"},
     *     summary="Retorna o valor total de notas por remetente",
     *     description="Retorna um objeto que representa o valor",
     *     path="/api/notas/{cnpj_remetente}/total",
     *      @OA\Parameter(
     *         description="Cnpj do Remetente",
     *         in="path",
     *         name="cnpj_remetente",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="23326986000190", summary="CNPJ"),
     *
     *     ),
     *     @OA\Response(response="200", description="Uma lista com notas"),
     *     @OA\Response(response="404", description="Remetente não encontrado"),
     *     @OA\Response(response="500", description="Erro interno do servidor"),
     * ),
     *
     */
    public function valorTotalNotasRemetente(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);
            $notas = $remetente->notas;

            $sum = $notas->sum('valor');

            return response()->json(['valor_total' => $sum], 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Notas"},
     *     summary="Retorna o valor total de notas por remetente daquilo que foi entregado",
     *     description="Retorna um objeto que representa o valor",
     *     path="/api/notas/{cnpj_remetente}/total_entregado",
     *      @OA\Parameter(
     *         description="Cnpj do Remetente",
     *         in="path",
     *         name="cnpj_remetente",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="23326986000190", summary="CNPJ"),
     *
     *     ),
     *     @OA\Response(response="200", description="Uma lista com notas"),
     *     @OA\Response(response="404", description="Remetente não encontrado"),
     *     @OA\Response(response="500", description="Erro interno do servidor"),
     * ),
     *
     */
    public function valorTotalNotasRemetenteEntregado(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);

            $notas = Nota::where('remetente_id', $remetente->id)->where('status', 'COMPROVADO')->get();

            $sum = 0;
            foreach ($notas as $nota){
                if($nota->notaEntregue()){
                    $sum += $nota->valor;
                }
            }

            return response()->json(['valor_total_entregado' => $sum], 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Notas"},
     *     summary="Retorna o valor total de notas por remetente daquilo que não foi entregado",
     *     description="Retorna um objeto que representa o valor",
     *     path="/api/notas/{cnpj_remetente}/total_nao_entregado",
     *      @OA\Parameter(
     *         description="Cnpj do Remetente",
     *         in="path",
     *         name="cnpj_remetente",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="23326986000190", summary="CNPJ"),
     *
     *     ),
     *     @OA\Response(response="200", description="Uma lista com notas"),
     *     @OA\Response(response="404", description="Remetente não encontrado"),
     *     @OA\Response(response="500", description="Erro interno do servidor"),
     * ),
     *
     */
    public function valorTotalNotasRemetenteNaoEntregue(Request $request, $remetente_id)
    {
        try {
            $remetente = $this->buscarRemetenteService->findOrFailRemetente($remetente_id);

            $notas = Nota::where('remetente_id', $remetente->id)->where('status', 'ABERTO')->get();

            $sum = $notas->sum('valor');

            return response()->json(['valor_total_nao_entregado' => $sum], 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Notas"},
     *     summary="Retorna o valor total perdido de notas por remetente daquilo que foi atrasado",
     *     description="Retorna um objeto que representa o valor",
     *     path="/api/notas/{cnpj_remetente}/total_perdido",
     *      @OA\Parameter(
     *         description="Cnpj do Remetente",
     *         in="path",
     *         name="cnpj_remetente",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="23326986000190", summary="CNPJ"),
     *
     *     ),
     *     @OA\Response(response="200", description="Uma lista com notas"),
     *     @OA\Response(response="404", description="Remetente não encontrado"),
     *     @OA\Response(response="500", description="Erro interno do servidor"),
     * ),
     *
     */
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

            return response()->json(['valor_total_perdido' => $sum], 200);
        }catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        }
    }


}
