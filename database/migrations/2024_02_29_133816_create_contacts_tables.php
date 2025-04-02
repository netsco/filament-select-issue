<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', \App\Enums\ContactType::values());
            $table->string('company')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->virtualAs("CASE WHEN `type`='company' THEN `company` ELSE CONCAT_WS(' ',`title`,`first_name`,`last_name`) END");
            $table->string('job_title')->nullable();
            $table->longText('background_info')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contacts_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->string('value');
            $table->unique(['contact_id', 'value']);
            $table->boolean('is_primary');
            $table->timestamps();
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('contacts');
        Schema::dropIfExists('contacts_emails');

    }
};
