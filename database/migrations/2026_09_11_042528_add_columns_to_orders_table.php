<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->nullable()->after('id');
            }
            if (! Schema::hasColumn('orders', 'order_amount')) {
                $table->decimal('order_amount', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('orders', 'order_change')) {
                $table->decimal('order_change', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('orders', 'order_status')) {
                $table->string('order_status')->default('pending');
            }
        });

        Schema::table('order_details', function (Blueprint $table) {
            if (! Schema::hasColumn('order_details', 'order_stock')) {
                $table->integer('order_stock')->default(0);
            }
            if (! Schema::hasColumn('order_details', 'order_price')) {
                $table->decimal('order_price', 15, 2)->default(0);
            }
            if (! Schema::hasColumn('order_details', 'order_subtotal')) {
                $table->decimal('order_subtotal', 15, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_code', 'order_amount', 'order_change', 'order_status']);
        });
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['order_stock', 'order_price', 'order_subtotal']);
        });
    }
};
