<?php

namespace App\Models\DTO;

class NotaDTO {
    public $chave;
    public $numero;
    public $dest; // um objeto DestDTO
    public $cnpj_remete;
    public $nome_remete;
    public $nome_transp;
    public $cnpj_transp;
    public $status;
    public $valor;
    public $volumes;
    public $dt_emis;
    public $dt_entrega;

    public function __construct($chave, $numero, $cnpj_remete, $nome_remete,
                                $nome_transp,$cnpj_transp, $status, $valor,
                                $volumes, $dt_emis, $dt_entrega, $nome_dest, $cod_dest) {
        $this->chave = $chave;
        $this->numero = $numero;
        $this->cnpj_remete = $cnpj_remete;
        $this->nome_remete = $nome_remete;
        $this->nome_transp = $nome_transp;
        $this->cnpj_transp = $cnpj_transp;
        $this->status = $status;
        $this->valor = $valor;
        $this->volumes = $volumes;
        $this->dt_emis = $dt_emis;
        $this->dt_entrega = $dt_entrega;

        // criar um objeto DestDTO a partir dos dados fornecidos
        $this->dest = new DestDTO($nome_dest, $cod_dest);
    }
}

class DestDTO {
    public $nome;
    public $cod;

    public function __construct($nome, $cod) {
        $this->nome = $nome;
        $this->cod = $cod;
    }
}

