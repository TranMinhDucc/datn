<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingFee;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingFee::with(['zone', 'method']);

        // Xử lý lọc theo khu vực (có thể chọn nhiều)
        if ($request->filled('zones') && is_array($request->zones) && !empty(array_filter($request->zones))) {
            $zones = array_filter($request->zones); // Loại bỏ giá trị rỗng
            $query->whereIn('shipping_zone_id', $zones);
        }

        // Xử lý lọc theo phương thức (có thể chọn nhiều)
        if ($request->filled('methods') && is_array($request->methods) && !empty(array_filter($request->methods))) {
            $methods = array_filter($request->methods); // Loại bỏ giá trị rỗng
            $query->whereIn('shipping_method_id', $methods);
        }

        $shippingFees = $query->get();

        // Lấy danh sách zones và methods để hiển thị trong dropdown filter
        $zones = ShippingZone::all();
        $methods = ShippingMethod::all();

        return view('admin.shipping_fees.index', compact('shippingFees', 'zones', 'methods'));
    }

    public function create()
    {
        $zones = ShippingZone::all();
        $methods = ShippingMethod::all();
        return view('admin.shipping_fees.create', compact('zones', 'methods'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'locations' => 'required|json',
            'shipping_methods' => 'required|array|min:1',
            'shipping_methods.*.method_id' => 'required|exists:shipping_methods,id',
            'shipping_methods.*.price' => 'required|numeric|min:0',
            'shipping_methods.*.free_shipping_minimum' => 'nullable|numeric|min:0'
        ]);

        // Parse locations JSON
        $locations = json_decode($request->locations, true);

        DB::beginTransaction();

        try {
            // Lặp qua từng khu vực
            foreach ($locations as $location) {
                // Tạo hoặc lấy shipping_zone từ fullAddress
                $zone = $this->getOrCreateZone($location);

                // Lặp qua từng phương thức giao hàng
                foreach ($request->shipping_methods as $methodData) {
                    // Kiểm tra xem đã tồn tại chưa (tránh trùng lặp)
                    $existingFee = ShippingFee::where([
                        'shipping_zone_id' => $zone->id,
                        'shipping_method_id' => $methodData['method_id']
                    ])->first();

                    if ($existingFee) {
                        // Cập nhật nếu đã tồn tại
                        $existingFee->update([
                            'price' => $methodData['price'],
                            'free_shipping_minimum' => $methodData['free_shipping_minimum'] ?? null
                        ]);
                    } else {
                        // Tạo mới nếu chưa tồn tại
                        ShippingFee::create([
                            'shipping_zone_id' => $zone->id,
                            'shipping_method_id' => $methodData['method_id'],
                            'price' => $methodData['price'],
                            'free_shipping_minimum' => $methodData['free_shipping_minimum'] ?? null
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.shipping-fees.index')
                ->with('success', 'Thêm phí vận chuyển thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo hoặc lấy shipping_zone từ thông tin location
     */
    private function getOrCreateZone($location)
    {
        // Sử dụng fullAddress làm name cho zone
        $zoneName = $location['fullAddress'];

        // Tạo description chi tiết hơn
        $description = $this->buildZoneDescription($location);

        // Tìm hoặc tạo zone
        $zone = ShippingZone::firstOrCreate(
            ['name' => $zoneName], // Tìm theo name
            ['description' => $description] // Tạo mới với description
        );

        return $zone;
    }

    /**
     * Tạo description cho zone
     */
    private function buildZoneDescription($location)
    {
        $parts = [];

        if (!empty($location['ward'])) {
            $parts[] = "Phường/Xã: " . $location['ward'];
        }

        if (!empty($location['district'])) {
            $parts[] = "Quận/Huyện: " . $location['district'];
        }

        $parts[] = "Tỉnh/TP: " . $location['province'];

        return implode(' | ', $parts);
    }

    public function edit(ShippingFee $shippingFee)
    {
        $zones = ShippingZone::all();
        $methods = ShippingMethod::all();
        return view('admin.shipping_fees.edit', compact('shippingFee', 'zones', 'methods'));
    }

    public function update(Request $request, ShippingFee $shippingFee)
    {
        $request->validate([
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'price' => 'required|integer|min:0',
            'free_shipping_minimum' => 'nullable|integer|min:0',
        ]);

        $shippingFee->update($request->only(['shipping_zone_id', 'shipping_method_id', 'price', 'free_shipping_minimum']));

        return redirect()->route('admin.shipping-fees.index')->with('success', 'Cập nhật phí vận chuyển thành công.');
    }

    public function destroy(ShippingFee $shippingFee)
    {
        $shippingFee->delete();
        return redirect()->route('admin.shipping-fees.index')->with('success', 'Xoá phí vận chuyển thành công.');
    }
}
