<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use App\Models\InventoryTransaction;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $variantHtml = view('admin.inventory._variant_table', [
                'variants' => $variants = ProductVariant::with(['product', 'options.attribute'])
                    ->when($request->search, function ($query) use ($request) {
                        $query->whereHas('product', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->paginate(20)
                    ->withQueryString()
            ])->render();

            $productHtml = view('admin.inventory._product_table', [
                'productsWithoutVariants' => Product::doesntHave('variants')
                    ->when($request->search, function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->paginate(20, ['*'], 'page2')
                    ->withQueryString()
            ])->render();

            return response()->json([
                'variants' => $variantHtml,
                'products' => $productHtml,
            ]);
        }
        $variants = ProductVariant::with(['product', 'options.attribute'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(20)
            ->withQueryString();

        $productsWithoutVariants = Product::doesntHave('variants')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(20, ['*'], 'page2')
            ->withQueryString();

        return view('admin.inventory.index', compact('variants', 'productsWithoutVariants'));
    }

    public function adjust(Request $request, InventoryService $inventory)
    {
        $request->validate([
            'id' => 'required|integer',
            'type_target' => 'required|in:product,variant',
            'type' => 'required|in:import,export,adjust,return',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $inventory->adjustStockGeneral(
            typeTarget: $request->type_target,
            id: $request->id,
            quantity: $request->quantity,
            type: $request->type,
            note: $request->note,
            userId: auth()->id()
        );

        return redirect()->back()->with('success', 'Cập nhật kho thành công');
    }
    public function history(Request $request)
    {
        if ($request->ajax()) {
            $transactions = InventoryTransaction::with(['product', 'productVariant', 'user'])
                ->when($request->type, fn($q) => $q->where('type', $request->type))
                ->when($request->search, function ($q) use ($request) {
                    $q->whereHas('product', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
                })
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();
            return view('admin.inventory._history_table', compact('transactions'))->render();
        }
        
        $transactions = InventoryTransaction::with(['product', 'productVariant', 'user'])
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('product', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.inventory.history', compact('transactions'));
    }
}
