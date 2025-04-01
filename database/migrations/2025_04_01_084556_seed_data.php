<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
        $types_values = [
            \App\Models\Contact\Address::class => [
                'Work',
                'Home',
            ],
            \App\Models\Contact\Category::class => [
                'Supplier',
                'Venue',
                'Staff',
            ],
            \App\Models\Contact\CustomField::class => [
            ],
            \App\Models\Contact\Date::class => [
                'Birthday',
            ],
            \App\Models\Contact\Email::class => [
                'Work',
                'Home',
            ],
            \App\Models\Contact\SocialMedia::class => [
                'LinkedIn',
                'Facebook',
                'Instagram',
                'X/Twitter',
            ],
            \App\Models\Contact\Source::class => [
                'Website',
                'Agent',
                'Tel',
            ],
            \App\Models\Contact\Tel::class => [
                'Home',
                'Work',
                'Mobile',
            ],
            \App\Models\Contact\Website::class => [
                'Work',
                'Home',
            ],
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
