<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportSchema extends Command
{
    /**
     * Tên lệnh Artisan
     */
    protected $signature = 'export:schema';

    /**
     * Mô tả
     */
    protected $description = 'Export database schema to JSON';

    public function handle()
    {
        $dbName = DB::getDatabaseName();
        $tables = DB::select("SHOW TABLES");

        $schema = [];

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $columns = DB::select("SHOW COLUMNS FROM {$tableName}");

            $schema[$tableName] = array_map(fn($col) => (array) $col, $columns);
        }

        // Lưu file JSON vào storage/app/schema.json
        Storage::put('schema.json', json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ Schema exported to storage/app/schema.json");
    }
}
