<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
        $types_values = [
            \App\Models\Contact\Email::class => [
                'Work',
                'Home',
            ]
        ];

        foreach ($types_values as $model => $values) {
            foreach ($values as $value) {
                DB::table('contact_data_types')->insert([
                    'model' => $model,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
	}

	public function down()
	{

	}
};
