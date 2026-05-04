<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportDatabase extends Command
{
    protected $signature = 'db:export {--output=database/backup.sql}';
    protected $description = 'Export all database tables to SQL file';

    public function handle()
    {
        $output = $this->option('output');
        $sql = '';

        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");

        foreach ($tables as $table) {
            $tableName = $table->tablename;
            $this->info("Exporting: $tableName");

            // Get columns
            $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = ? AND table_schema = 'public' ORDER BY ordinal_position", [$tableName]);
            $colNames = array_map(fn($c) => '"' . $c->column_name . '"', $columns);
            $colList = implode(', ', $colNames);

            // Get rows
            $rows = DB::table($tableName)->get();

            if ($rows->isEmpty()) continue;

            $sql .= "\n-- Table: $tableName\n";

            foreach ($rows as $row) {
                $values = array_map(function ($val) {
                    if ($val === null) return 'NULL';
                    if (is_bool($val)) return $val ? 'TRUE' : 'FALSE';
                    return "'" . addslashes($val) . "'";
                }, (array) $row);

                $sql .= "INSERT INTO \"$tableName\" ($colList) VALUES (" . implode(', ', $values) . ");\n";
            }
        }

        file_put_contents(base_path($output), $sql);
        $this->info("Exported to: $output");
    }
}
