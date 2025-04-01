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

        Schema::create('contacts_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('postcode')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('contacts_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->unique(['contact_id', 'type_id']);
            $table->timestamps();
        });

        Schema::create('contacts_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('company_contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->string('job_title');
            $table->boolean('is_primary');
            $table->unique(['contact_id', 'company_contact_id']);
            $table->timestamps();
        });

        Schema::create('contacts_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->string('value');
            $table->unique(['contact_id', 'type_id']);
            $table->timestamps();
        });

        Schema::create('contacts_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->date('value');
            $table->string('notes');
            $table->unique(['contact_id', 'type_id', 'value', 'notes']);
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

        Schema::create('contacts_social_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->string('value');
            $table->unique(['contact_id', 'value']);
            $table->timestamps();
        });

        Schema::create('contacts_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->unique(['contact_id', 'type_id']);
            $table->timestamps();
        });

        Schema::create('contacts_tel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->string('value');
            $table->unique(['contact_id', 'value']);
            $table->boolean('is_primary');
            $table->timestamps();
        });

        Schema::create('contacts_websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('contact_data_types')->onDelete('restrict');
            $table->string('value');
            $table->unique(['contact_id', 'value']);
            $table->timestamps();
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('contacts');
        Schema::dropIfExists('contacts_addresses');
        Schema::dropIfExists('contacts_categories');
        Schema::dropIfExists('contacts_custom_fields');
        Schema::dropIfExists('contacts_dates');
        Schema::dropIfExists('contacts_emails');
        Schema::dropIfExists('contacts_social_media');
        Schema::dropIfExists('contacts_sources');
        Schema::dropIfExists('contacts_tel');
        Schema::dropIfExists('contacts_websites');

    }
};
