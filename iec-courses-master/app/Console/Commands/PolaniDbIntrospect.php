<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PolaniDbIntrospect extends Command
{
    protected $signature = 'polani:db-introspect {table? : Optional table to describe}';
    protected $description = 'List database tables or describe a specific table (Polani helper).';

    public function handle(): int
    {
        $table = $this->argument('table');

        if ($table) {
            $safe = str_replace('`', '``', (string) $table);
            $columns = DB::select("SHOW COLUMNS FROM `{$safe}`");

            if (!$columns) {
                $this->error("No columns found for table: {$table}");
                return self::FAILURE;
            }

            $this->table(
                ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'],
                array_map(
                    fn($c) => [(string) $c->Field, (string) $c->Type, (string) $c->Null, (string) $c->Key, (string) $c->Default, (string) $c->Extra],
                    $columns
                )
            );

            return self::SUCCESS;
        }

        $rows = DB::select('SHOW TABLES');
        if (!$rows) {
            $this->warn('No tables returned.');
            return self::SUCCESS;
        }

        $tables = [];
        foreach ($rows as $row) {
            foreach ((array) $row as $name) {
                $tables[] = ['table' => (string) $name];
            }
        }

        $this->table(['table'], $tables);
        return self::SUCCESS;
    }
}

