<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_data_types', function (Blueprint $table) {
            $table->id();
            $table->string('model')->index();
            $table->string('value');
            $table->string('colour')->nullable();
            $table->softDeletes();
            $table->unique(['model', 'value', 'deleted_at']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_data_types');
    }
};
