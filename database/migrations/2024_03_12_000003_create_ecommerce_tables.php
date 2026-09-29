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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->decimal('regular_price', 10, 2)->default(400.00);
            $table->decimal('offer_price', 10, 2)->default(200.00);
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->string('badge')->default('HOT DEAL');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('digital_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->default(1);
            $table->text('link_url');
            $table->string('status')->default('available'); // available, sold, reserved
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('delivered_to_phone')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'product_id']);
            $table->index('delivered_to_phone');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('product_id')->default(1);
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('bkash'); // bkash, nagad, uddoktapay
            $table->string('sender_phone')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->string('status')->default('completed'); // completed, pending, cancelled
            $table->unsignedBigInteger('digital_link_id')->nullable();
            $table->text('delivered_link')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('customer_phone');
            $table->index('trx_id');
            $table->index('status');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('digital_links');
        Schema::dropIfExists('products');
    }
};
