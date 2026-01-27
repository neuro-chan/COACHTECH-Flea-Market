<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportDbml extends Command
{
    protected $signature = 'chartdb:export-dbml {--file= : Save output to file}';
    protected $description = 'Export database schema as DBML for ChartDB';

    public function handle()
    {
        $this->info('Generating DBML schema...');

        $tables = DB::select("
            SELECT TABLE_NAME
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_TYPE = 'BASE TABLE'
        ");

        $dbml = '';

        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;

            // テーブル定義の開始
            $dbml .= "Table {$tableName} {\n";

            // カラム情報を取得
            $columns = DB::select("
                SELECT
                    COLUMN_NAME,
                    DATA_TYPE,
                    COLUMN_KEY,
                    EXTRA,
                    IS_NULLABLE,
                    COLUMN_DEFAULT,
                    CHARACTER_MAXIMUM_LENGTH
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                ORDER BY ORDINAL_POSITION
            ", [$tableName]);

            foreach ($columns as $column) {
                $columnName = $column->COLUMN_NAME;
                $dataType = $this->mapDataType($column);

                $attributes = [];

                // Primary Key
                if ($column->COLUMN_KEY === 'PRI') {
                    $attributes[] = 'pk';
                }

                // Auto Increment
                if (str_contains($column->EXTRA, 'auto_increment')) {
                    $attributes[] = 'increment';
                }

                // Not Null
                if ($column->IS_NULLABLE === 'NO' && $column->COLUMN_KEY !== 'PRI') {
                    $attributes[] = 'not null';
                }

                // Default Value
                if ($column->COLUMN_DEFAULT !== null) {
                    $default = $column->COLUMN_DEFAULT;

                    // CURRENT_TIMESTAMP を `now()` に変換
                    if (stripos($default, 'CURRENT_TIMESTAMP') !== false) {
                        $default = '`now()`';
                    }
                    // その他の関数もバッククォートで囲む
                    elseif (stripos($default, 'NOW()') !== false) {
                        $default = '`now()`';
                    }
                    elseif (stripos($default, 'CURRENT_DATE') !== false) {
                        $default = '`CURRENT_DATE`';
                    }
                    elseif (stripos($default, 'CURRENT_TIME') !== false) {
                        $default = '`CURRENT_TIME`';
                    }
                    // 数値でない場合はシングルクォートで囲む
                    elseif (!is_numeric($default)) {
                        $default = "'{$default}'";
                    }

                    $attributes[] = "default: {$default}";
                }

                $attrString = !empty($attributes) ? ' [' . implode(', ', $attributes) . ']' : '';
                $dbml .= "  {$columnName} {$dataType}{$attrString}\n";
            }

            $dbml .= "}\n\n";
        }

        // 外部キー制約を取得
        $foreignKeys = DB::select("
            SELECT
                TABLE_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME,
                CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // リファレンスを追加
        foreach ($foreignKeys as $fk) {
            $dbml .= "Ref: {$fk->TABLE_NAME}.{$fk->COLUMN_NAME} > {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
        }

        // 出力
        if ($this->option('file')) {
            $filePath = $this->option('file');
            file_put_contents($filePath, $dbml);
            $this->info("DBML exported to: {$filePath}");
        } else {
            $this->line($dbml);
        }

        $this->newLine();
        $this->info('✓ DBML export completed!');

        return 0;
    }

    private function mapDataType($column)
    {
        $type = strtolower($column->DATA_TYPE);

        $typeMap = [
            'bigint' => 'bigint',
            'int' => 'int',
            'integer' => 'int',
            'tinyint' => 'tinyint',
            'smallint' => 'smallint',
            'mediumint' => 'mediumint',
            'varchar' => 'varchar',
            'char' => 'char',
            'text' => 'text',
            'longtext' => 'longtext',
            'mediumtext' => 'mediumtext',
            'tinytext' => 'tinytext',
            'datetime' => 'datetime',
            'timestamp' => 'timestamp',
            'date' => 'date',
            'time' => 'time',
            'decimal' => 'decimal',
            'float' => 'float',
            'double' => 'double',
            'boolean' => 'boolean',
            'json' => 'json',
            'blob' => 'blob',
            'enum' => 'enum',
        ];

        $mappedType = $typeMap[$type] ?? $type;

        // 長さ情報を追加
        if ($column->CHARACTER_MAXIMUM_LENGTH && in_array($type, ['varchar', 'char'])) {
            $mappedType .= "({$column->CHARACTER_MAXIMUM_LENGTH})";
        }

        return $mappedType;
    }
}
