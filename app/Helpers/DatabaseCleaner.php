<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseCleaner
{
    public static function cleanDatabase(array $tables)
    {
        // Desabilita a verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            // Obtem o nome da tabela atual
            $table_name = $table->{'Tables_in_' . env('DB_DATABASE')};

            // Limpa os dados da tabela atual
            DB::table($table_name)->truncate();
        }

        // Habilita a verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
