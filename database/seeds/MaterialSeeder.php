<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class MaterialSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'materials';
        $this->csv_delimiter = ',';
        $this->filename = base_path() . '/database/seeds/csv/materials.csv';
        $this->mapping = [
            0 => 'id',
            1 => 'id_pos',
            2 => 'matg_id',
            3 => 'sisa',
            4 => 'masuk',
            5 => 'distribusi',
            6 => 'status',
            7 => 'status_medis',
            8 => 'is_show',
            9 => 'created_at',
            10 => 'updated_at'
        ];
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        \Illuminate\Support\Facades\DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        \Illuminate\Support\Facades\DB::table($this->table)->truncate();

        parent::run();
    }
}
