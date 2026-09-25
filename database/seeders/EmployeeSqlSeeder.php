<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSqlSeeder extends Seeder
{
    /**
     * Number of rows to accumulate before executing a batch.
     */
    private const BATCH_SIZE = 500;

    /**
     * The columns that should be updated when a matching EmpNo already exists.
     *
     * @var array<int, string>
     */
    private const UPSERT_COLUMNS = [
        'EmpLName',
        'EmpFName',
        'EmpMName',
        'EmpAddress',
        'PostNo',
        'DeptNo',
        'LocNo',
        'CompNo',
        'EmpContact1',
        'EmpContact2',
        'EmpContact3',
        'EmpEmergency',
        'EmpEmerContact',
        'EmpEmailAd',
        'PictName',
        'ItemPict',
        'Gender',
        'TINNo',
        'SSSNo',
        'PAGIBIGNo',
        'PHILHEALTHNo',
        'EmpStatusNo',
        'StatusNo',
        'DateHired',
        'BirthDate',
        'RegularizationDate',
        'CivilNo',
        'MedCardNo',
        'MedCardPolicyNo',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];

    /**
     * Seed tblEmployee from the SQL dump file using upsert (create or update).
     *
     * Streams the SQL dump line-by-line to avoid memory exhaustion,
     * batches rows, and converts INSERT statements to
     * INSERT ... ON DUPLICATE KEY UPDATE.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/sql/tblEmployee.sql');

        if (! file_exists($sqlPath)) {
            $this->command->warn("SQL file not found: {$sqlPath}");

            return;
        }

        $handle = fopen($sqlPath, 'r');

        if ($handle === false) {
            $this->command->error("Could not open SQL file: {$sqlPath}");

            return;
        }

        $updateClause = implode(', ', array_map(
            fn (string $col) => "`{$col}` = VALUES(`{$col}`)",
            self::UPSERT_COLUMNS,
        ));

        $columnList = '';
        $inInsertBlock = false;
        $inCreateBlock = false;
        $valueRows = [];
        $totalRows = 0;

        $pdo = DB::connection()->getPdo();

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);

            // Skip empty lines and MySQL session variable comments.
            if ($trimmed === '' || preg_match('/^\/\*!\d+/', $trimmed)) {
                // If we were in a CREATE block, a session variable line won't end it.
                continue;
            }

            // Skip DROP TABLE statements.
            if (preg_match('/^DROP TABLE/i', $trimmed)) {
                continue;
            }

            // Detect and skip the CREATE TABLE block.
            if (preg_match('/^CREATE TABLE/i', $trimmed)) {
                $inCreateBlock = true;

                continue;
            }

            if ($inCreateBlock) {
                // The CREATE TABLE block ends with a line containing ENGINE=...;
                if (preg_match('/ENGINE=/i', $trimmed)) {
                    $inCreateBlock = false;
                }

                continue;
            }

            // Detect the start of an INSERT block and capture the column list.
            if (preg_match('/^INSERT INTO\s+`tblEmployee`\s*\(([^)]+)\)\s*VALUES\s*$/i', $trimmed, $matches)) {
                $columnList = $matches[1];
                $inInsertBlock = true;

                continue;
            }

            // Accumulate value rows inside an INSERT block.
            if ($inInsertBlock) {
                // Each row line looks like: (val, val, ...),  or  (val, val, ...);
                $isLastRow = str_ends_with($trimmed, ';');

                // Strip trailing comma or semicolon.
                $row = rtrim($trimmed, ',;');
                $valueRows[] = $row;

                if ($isLastRow || count($valueRows) >= self::BATCH_SIZE) {
                    $this->executeBatch($pdo, $columnList, $valueRows, $updateClause);
                    $totalRows += count($valueRows);
                    $valueRows = [];
                }

                if ($isLastRow) {
                    $inInsertBlock = false;
                }
            }
        }

        // Flush any remaining rows.
        if (count($valueRows) > 0) {
            $this->executeBatch($pdo, $columnList, $valueRows, $updateClause);
            $totalRows += count($valueRows);
        }

        fclose($handle);

        $this->command->info("tblEmployee seeded (upsert) from SQL dump — {$totalRows} rows processed.");
    }

    /**
     * Execute a batch of value rows as an upsert statement.
     *
     * @param  array<int, string>  $valueRows
     */
    private function executeBatch(\PDO $pdo, string $columnList, array $valueRows, string $updateClause): void
    {
        $values = implode(",\n", $valueRows);
        $sql = "INSERT INTO `tblEmployee` ({$columnList}) VALUES\n{$values}\nON DUPLICATE KEY UPDATE {$updateClause};";
        $pdo->exec($sql);
    }
}
