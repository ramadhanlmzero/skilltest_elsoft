<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('item_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('item_account_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('item_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained('companies');
            $table->foreignUuid('item_type_id')->constrained('item_types');
            $table->string('code')->unique();
            $table->string('label');
            $table->foreignUuid('item_group_id')->constrained('item_groups');
            $table->foreignUuid('item_account_group_id')->constrained('item_account_groups');
            $table->foreignUuid('item_unit_id')->constrained('item_units');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'code'], 'items_company_id_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_units');
        Schema::dropIfExists('item_account_groups');
        Schema::dropIfExists('item_groups');
        Schema::dropIfExists('item_types');
    }
};
