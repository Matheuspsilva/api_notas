<?php

namespace Tests\Feature;

use App\Models\Destinatario;
use App\Models\Nota;
use App\Models\Remetente;
use App\Models\Transportador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class NotaTest extends TestCase
{

    use RefreshDatabase, WithFaker;


    protected function setUp(): void
    {
        parent::setUp();

        $destinatario = Destinatario::create([
            'nome' => 'TERRITORIAL TRANSPORTES E EMPREEDIMENTOS',
            'cod' => '03889255000145'
        ]);

        $destinatario2 = Destinatario::create([
            'nome' => 'TERRITORIAL TRANSPORTES E EMPREEDIMENTOS',
            'cod' => '03889255000226'
        ]);

        $destinatario3 = Destinatario::create([
            'nome' => 'EMPRESA SAO GONCALO LTDA',
            'cod' => '19792977000117'
        ]);

        $destinatario4 = Destinatario::create([
            'nome' => 'SANTOS DUMONT TRANSPORTES EIRELI',
            'cod' => '22900414000100'
        ]);

        $destinatario5 = Destinatario::create([
            'nome' => 'HUGO ADRIANO LIDUARIO',
            'cod' => '10636110000106'
        ]);

        $remetente = Remetente::create([
            'nome' => 'CARVALHO ONIBUS LTDA',
            'cnpj' => '23326986000190'
        ]);

        $remetente2 = Remetente::create([
            'nome' => 'DIST CENTRO OESTE DE MEDICAMENTOS L',
            'cnpj' => '66438011000166'
        ]);

        $transportador = Transportador::create([
            'nome' => 'CARVALHO PECAS E ONIBUS',
            'cnpj' => '23326986000190'
        ]);

        $transportador2 = Transportador::create([
            'nome' => 'PH LOGISTICA LTDA',
            'cnpj' => '12227730000109'
        ]);

        $nota1 = Nota::create(
            [
                'chave' => '55200423326986000190000309355',
                'numero' => '000309355',
                'status' => 'COMPROVADO',
                'valor' => '100',
                'volumes' => '2',
                'dt_emis' => '2020-04-16 15:51:24',
                'dt_entrega' => '2020-04-17 20:11:00',
                'remetente_id' => $remetente->id,
                'transportador_id' => $transportador->id,
                'destinatario_id' => $destinatario->id
            ]
        );

        $nota2 = Nota::create([
            'chave' => '55200423326986000190000309356',
            'numero' => '000309356',
            'status' => 'COMPROVADO',
            'valor' => '110.47',
            'volumes' => '2',
            'dt_emis' => '2020-04-16 15:51:39',
            'dt_entrega' => '2020-04-17 10:51:39',
            'remetente_id' => $remetente->id,
            'transportador_id' => $transportador->id,
            'destinatario_id' => $destinatario2->id
        ]);

        $nota3 = Nota::create([
            'chave' => '55200423326986000190000309349',
            'numero' => '000309349',
            'status' => 'COMPROVADO',
            'valor' => '209.00',
            'volumes' => '1',
            'dt_emis' => '2020-04-16 15:51:39',
            'dt_entrega' => '2020-04-19 09:05:18',
            'remetente_id' => $remetente->id,
            'transportador_id' => $transportador->id,
            'destinatario_id' => $destinatario3->id
        ]);

        $nota4 = Nota::create([
            'chave' => '55200423326986000190000309347',
            'numero' => '000309347',
            'status' => 'ABERTO',
            'valor' => '348.60',
            'volumes' => '1',
            'dt_emis' => '2020-04-16 15:04:13',
            'remetente_id' => $remetente->id,
            'transportador_id' => $transportador->id,
            'destinatario_id' => $destinatario4->id
        ]);

        $nota = Nota::create([
            'chave' => '55200466438011000166003473488',
            'numero' => '003473488',
            'status' => 'COMPROVADO',
            'valor' => '295.54',
            'volumes' => '5',
            'dt_emis' => '2020-04-09 13:00:05',
            'dt_entrega' => '2020-04-15 23:51:23',
            'remetente_id' => $remetente2->id,
            'transportador_id' => $transportador2->id,
            'destinatario_id' => $destinatario5->id
        ]);

    }

    public function testListarRemetentesComSucesso()
    {
        $nota = Nota::whereChave('55200423326986000190000309355')->first();
        $this->json('get', 'api/notas/' . $nota->remetente->cnpj)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    [
                        'chave' => $nota->chave,
                        'numero' => $nota->numero,
                        'dest' => [
                            'nome' => $nota->destinatario->nome,
                            'cod' => $nota->destinatario->cod
                        ],
                        'cnpj_remete' => $nota->remetente->cnpj,
                        'nome_remete' => $nota->remetente->nome,
                        'nome_transp' => $nota->transportador->nome,
                        'cnpj_transp' => $nota->transportador->cnpj,
                        'status' => $nota->status,
                        'valor' => $nota->valor,
                        'volumes' => $nota->volumes,
                        'dt_emis' => $nota->dt_emis,
                        'dt_entrega' => $nota->dt_entrega
                    ]
                ]
            );
    }

    public function testvalorTotalNotasPorRemetente()
    {
        $remetente = Remetente::whereCnpj('23326986000190')->first();
        $this->json('get', 'api/notas/' . $remetente->cnpj . '/total')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'valor_total' => '768.07'
                ]
            );
    }

    public function testvalorTotalNotasPorRemetenteEntregado()
    {
        $remetente = Remetente::whereCnpj('23326986000190')->first();
        $this->json('get', 'api/notas/' . $remetente->cnpj . '/total_entregado')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'valor_total_entregado' => '210.47'
                ]
            );
    }

    public function testvalorTotalNotasPorRemetenteNaoEntregado()
    {
        $remetente = Remetente::whereCnpj('23326986000190')->first();
        $this->json('get', 'api/notas/' . $remetente->cnpj . '/total_nao_entregado')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'valor_total_nao_entregado' => '348.6'
                ]
            );
    }

    public function testvalorTotalPerdidoNotasPorRemetenteComAtraso()
    {
        $remetente = Remetente::whereCnpj('23326986000190')->first();
        $this->json('get', 'api/notas/' . $remetente->cnpj . '/total_perdido')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    "valor_total_perdido" => '209'
                ]
            );
    }


}
