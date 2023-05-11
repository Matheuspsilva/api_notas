<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseCleaner
{
    public static function cleanDatabase(array $tables)
    {
        // Desabilita a verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            // Limpa os dados da tabela atual
            DB::table($table)->truncate();
        }

        // Habilita a verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
