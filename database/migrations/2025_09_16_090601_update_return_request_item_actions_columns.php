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
        Schema::table('return_request_item_actions', function (Blueprint $t) {
            // Nếu trước đây bảng còn cột `type`, đổi sang `action`
            // (Cần cài doctrine/dbal để rename cột: composer require doctrine/dbal)
            if (
                Schema::hasColumn('return_request_item_actions', 'type') &&
                !Schema::hasColumn('return_request_item_actions', 'action')
            ) {
                $t->renameColumn('type', 'action');
            }

            if (!Schema::hasColumn('return_request_item_actions', 'created_by')) {
                $t->unsignedBigInteger('created_by')->nullable()->after('note');
            }
            if (!Schema::hasColumn('return_request_item_actions', 'updated_by')) {
                $t->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_request_item_actions', function (Blueprint $t) {
            if (Schema::hasColumn('return_request_item_actions', 'updated_by')) {
                $t->dropColumn('updated_by');
            }
            if (Schema::hasColumn('return_request_item_actions', 'created_by')) {
                $t->dropColumn('created_by');
            }
            // Không rollback rename để an toàn
        });
    }
};
