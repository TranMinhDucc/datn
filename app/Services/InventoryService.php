<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function adjustStockGeneral(
        string $typeTarget,
        int $id,
        int $quantity,
        string $type,
        ?string $note,
        ?int $userId
    ): void {
        DB::transaction(function () use ($typeTarget, $id, $quantity, $type, $note, $userId) {
            if ($typeTarget === 'variant') {
                $variant = ProductVariant::with('product')->lockForUpdate()->findOrFail($id);

                match ($type) {
                    'import', 'return' => $variant->quantity += $quantity,
                    'export' => $variant->quantity -= $quantity,
                    'adjust' => $variant->quantity = $quantity,
                };

                $variant->save();

                InventoryTransaction::create([
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'note' => $note,
                    'created_by' => $userId,
                ]);
            }

            if ($typeTarget === 'product') {
                $product = Product::lockForUpdate()->findOrFail($id);

                match ($type) {
                    'import', 'return' => $product->stock_quantity += $quantity,
                    'export' => $product->stock_quantity -= $quantity,
                    'adjust' => $product->stock_quantity = $quantity,
                };

                $product->save();

                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'type' => $type,
                    'quantity' => $quantity,
                    'note' => $note,
                    'created_by' => $userId,
                ]);
            }
        });
    }
    public function reserveStock(int $variantId, int $quantity): void
    {
        DB::transaction(function () use ($variantId, $quantity) {
            $variant = ProductVariant::lockForUpdate()->findOrFail($variantId);

            $available = $variant->quantity - $variant->reserved_quantity;

            if ($available < $quantity) {
                throw new \Exception("Không đủ hàng để giữ chỗ.");
            }

            $variant->reserved_quantity += $quantity;
            $variant->save();
        });
    }
    public function confirmReservedStock(int $variantId, int $quantity, int $userId): void
    {
        DB::transaction(function () use ($variantId, $quantity, $userId) {
            $variant = ProductVariant::with('product')->lockForUpdate()->findOrFail($variantId);

            if ($variant->reserved_quantity < $quantity) {
                throw new \Exception("Số lượng giữ chỗ không đủ để xác nhận.");
            }

            // Trừ tồn kho thực
            $variant->quantity -= $quantity;

            // Giảm giữ chỗ
            $variant->reserved_quantity -= $quantity;

            $variant->save();

            // Ghi log giao dịch
            InventoryTransaction::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => 'export',
                'quantity' => $quantity,
                'note' => 'Xác nhận đơn hàng',
                'created_by' => $userId,
            ]);
        });
    }
    public function releaseReservedStock(int $variantId, int $quantity): void
    {
        DB::transaction(function () use ($variantId, $quantity) {
            $variant = ProductVariant::lockForUpdate()->findOrFail($variantId);

            $variant->reserved_quantity = max(0, $variant->reserved_quantity - $quantity);
            $variant->save();
        });
    }
}
