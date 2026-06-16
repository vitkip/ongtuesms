<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix year_level ສຳລັບທຸກນັກສຶກສາ:
     * year_level = (ປີ start ສົກຮຽນປັດຈຸບັນ) - (ປີ start enrollment) + 1
     * ສົກຮຽນໃໝ່ເລີ່ມເດືອນ 6 (ມິຖຸນາ) ຂຶ້ນໄປ
     */
    public function up(): void
    {
        $now = now();
        $currentStart = $now->month >= 6 ? $now->year : $now->year - 1;

        DB::statement("
            UPDATE students s
            JOIN academic_years ay ON s.academic_year_id = ay.id
            SET s.year_level = GREATEST(1, ? - CAST(SUBSTRING(ay.year, 1, 4) AS UNSIGNED) + 1)
        ", [$currentStart]);
    }

    public function down(): void
    {
        DB::statement('UPDATE students SET year_level = 1');
    }
};
