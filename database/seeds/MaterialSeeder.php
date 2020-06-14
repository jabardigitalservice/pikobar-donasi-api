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
        $this->csv_delimiter = '|';
        $this->filename = base_path() . '/database/seeds/csv/materials.csv';
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
