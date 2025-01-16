<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    public function truncate(string $table): bool
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::table($table)->truncate();
                break;

            case 'pgsql':
                DB::statement('TRUNCATE TABLE ' . $table . ' RESTART IDENTITY CASCADE');
                break;

            case 'sqlite':
                DB::statement('DELETE FROM ' . $table);
                break;
        }

        return true;
    }
}
