<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    protected bool $async = false;

    protected ?string $tag = 'fix';

    public function process(): void
    {
        if (!Schema::hasTable('bill_prints')) {
            $migration = include database_path('migrations/2018_01_26_230111_create_bill_prints_table.php');
            $migration->up();
        }

        if (DB::table('bill_prints')->count() === 0) {
            DB::table('bill_prints')->insert([
                'id' => 1,
                'name' => 'UltimateCode',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
