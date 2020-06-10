<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class BanksSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'banks';
        $this->csv_delimiter = ',';
        $this->filename = base_path() . '/database/seeds/csv/banks.csv';
        $this->mapping = [
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'xendit_code',
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
