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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->decimal('payment_amount', 10, 2)->nullable()->after('final_amount');
            $table->decimal('change_amount', 10, 2)->nullable()->after('payment_amount');
            $table->dateTime('transaction_date')->nullable()->after('change_amount');
            
            // Update existing fields to be nullable for backward compatibility
            $table->string('invoice_number')->nullable()->change();
            $table->decimal('tax', 10, 2)->nullable()->change();
            $table->decimal('discount', 10, 2)->nullable()->change();
            $table->decimal('final_amount', 10, 2)->nullable()->change();
            $table->integer('item_count')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'payment_amount', 'change_amount', 'transaction_date']);
        });
    }
};