<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==== Bảng return_request_items ====
        Schema::table('return_request_items', function (Blueprint $table) {
            if (!Schema::hasColumn('return_request_items', 'qty_exchange')) {
                $table->unsignedInteger('qty_exchange')->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('return_request_items', 'qty_refund')) {
                $table->unsignedInteger('qty_refund')->default(0)->after('qty_exchange');
            }
            if (!Schema::hasColumn('return_request_items', 'qty_reject')) {
                $table->unsignedInteger('qty_reject')->default(0)->after('qty_refund');
            }
            if (!Schema::hasColumn('return_request_items', 'exchange_variant_id')) {
                $table->foreignId('exchange_variant_id')->nullable()->after('order_item_id')->constrained('product_variants');
            }
            if (!Schema::hasColumn('return_request_items', 'item_status')) {
                $table->string('item_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('return_request_items', 'unit_price_paid')) {
                $table->decimal('unit_price_paid', 12, 2)->nullable()->after('attachments');
            }
            if (!Schema::hasColumn('return_request_items', 'refund_amount')) {
                $table->decimal('refund_amount', 12, 2)->default(0)->after('unit_price_paid');
            }

            $table->index(['return_request_id']);
            $table->index(['order_item_id']);
            $table->index(['exchange_variant_id']);
        });

        // ==== Bảng return_requests ====
        Schema::table('return_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('return_requests', 'total_refund_amount')) {
                $table->decimal('total_refund_amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('return_requests', 'exchange_order_id')) {
                $table->foreignId('exchange_order_id')->nullable()->constrained('orders');
            }
        });

        // ==== Backfill dữ liệu cũ ====
        DB::statement("
            UPDATE return_request_items rri
            JOIN order_items oi ON oi.id = rri.order_item_id
            SET rri.unit_price_paid = ROUND(oi.total_price / GREATEST(oi.quantity,1), 2)
            WHERE rri.unit_price_paid IS NULL
        ");

        DB::statement("
            UPDATE return_request_items
            SET item_status='approved_exchange',
                qty_exchange=IF(approved_quantity>0,approved_quantity,quantity),
                qty_refund=0, qty_reject=0
            WHERE status='approved'
        ");

        DB::statement("
            UPDATE return_request_items
            SET item_status='approved_refund',
                qty_refund=IF(approved_quantity>0,approved_quantity,quantity),
                qty_exchange=0, qty_reject=0
            WHERE status='returned'
        ");

        DB::statement("
            UPDATE return_request_items
            SET item_status='rejected',
                qty_reject=IF(approved_quantity>0,approved_quantity,quantity),
                qty_exchange=0, qty_refund=0
            WHERE status='rejected'
        ");

        DB::statement("
            UPDATE return_request_items
            SET refund_amount = ROUND(unit_price_paid * qty_refund, 2)
            WHERE qty_refund > 0 AND (refund_amount = 0 OR refund_amount IS NULL)
        ");
    }

    public function down(): void
    {
        Schema::table('return_request_items', function (Blueprint $table) {
            if (Schema::hasColumn('return_request_items', 'refund_amount')) {
                $table->dropColumn('refund_amount');
            }
            if (Schema::hasColumn('return_request_items', 'unit_price_paid')) {
                $table->dropColumn('unit_price_paid');
            }
            if (Schema::hasColumn('return_request_items', 'exchange_variant_id')) {
                $table->dropConstrainedForeignId('exchange_variant_id');
            }
            if (Schema::hasColumn('return_request_items', 'item_status')) {
                $table->dropColumn('item_status');
            }
            if (Schema::hasColumn('return_request_items', 'qty_reject')) {
                $table->dropColumn('qty_reject');
            }
            if (Schema::hasColumn('return_request_items', 'qty_refund')) {
                $table->dropColumn('qty_refund');
            }
            if (Schema::hasColumn('return_request_items', 'qty_exchange')) {
                $table->dropColumn('qty_exchange');
            }
        });

        Schema::table('return_requests', function (Blueprint $table) {
            if (Schema::hasColumn('return_requests', 'exchange_order_id')) {
                $table->dropConstrainedForeignId('exchange_order_id');
            }
            if (Schema::hasColumn('return_requests', 'total_refund_amount')) {
                $table->dropColumn('total_refund_amount');
            }
        });
    }
};
