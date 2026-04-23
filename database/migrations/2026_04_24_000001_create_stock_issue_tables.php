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
        Schema::create('stock_issue_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable()->constrained('companies');
            $table->string('name');
            $table->timestamps();

            $table->unique(['company_id', 'name'], 'accounts_company_id_name_unique');
        });

        Schema::create('stock_issues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained('companies');
            $table->string('code')->unique();
            $table->date('date');
            $table->foreignUuid('account_id')->constrained('accounts');
            $table->foreignUuid('status_id')->constrained('stock_issue_statuses');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_issue_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('stock_issue_id')->constrained('stock_issues');
            $table->foreignUuid('item_id')->constrained('items');
            $table->decimal('quantity', 18, 4);
            $table->foreignUuid('item_unit_id')->constrained('item_units');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issue_details');
        Schema::dropIfExists('stock_issues');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('stock_issue_statuses');
    }
};
