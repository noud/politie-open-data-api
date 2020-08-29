<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillTablesTable extends Migration
{
    private $language = 'nl';

    private $tables = [
        
        'adres',
        'afbeelding',
        'locaties',
        'politiebureaus',
        'twitter',
        'wijkagenten',
        // @todo distractable
        'politiebureaus_locaties',
        'wijkagenten_links',
        'wijkagenten_locaties',
        'wijkagenten_translations',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            DB::table('tables')->insert([
                'name' => $table,
                'language' => $this->language,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
