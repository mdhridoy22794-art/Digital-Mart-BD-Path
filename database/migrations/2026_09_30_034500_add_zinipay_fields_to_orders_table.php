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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'invoice_id')) {
                $table->string('invoice_id')->nullable()->after('trx_id')->index();
            }
            if (!Schema::hasColumn('orders', 'val_id')) {
                $table->string('val_id')->nullable()->after('invoice_id')->index();
            }
            if (!Schema::hasColumn('orders', 'payment_gateway_response')) {
                $table->text('payment_gateway_response')->nullable()->after('admin_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['customer_email', 'invoice_id', 'val_id', 'payment_gateway_response'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
