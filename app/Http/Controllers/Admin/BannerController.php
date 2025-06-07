<?php
// Controller: BannerController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('buttons')->orderBy('thu_tu')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten' => ['required', 'string', 'max:255'],
            'hinh_anh' => ['required', 'image'],
            'mo_ta' => ['required', 'string'],
            'ngon_ngu' => ['required', 'in:vi,en'],
            'thu_tu' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('banners', 'thu_tu'),
            ],
            'buttons' => ['array'],
            'buttons.*.ten' => ['required_with:buttons.*.duong_dan', 'string'],
            'buttons.*.duong_dan' => ['required_with:buttons.*.ten', 'url'],
        ], [
            'ten.required' => 'Vui lòng điền tên.',
            'hinh_anh.required' => 'Vui lòng chọn hình ảnh.',
            'hinh_anh.image' => 'Tệp phải là hình ảnh.',
            'mo_ta.required' => 'Vui lòng ghi mô tả.',
            'thu_tu.required' => 'Hãy chọn thứ tự hiển thị.',
            'thu_tu.integer' => 'Thứ tự phải là số.',
            'thu_tu.min' => 'Thứ tự không được âm.',
            'thu_tu.unique' => 'Thứ tự đã tồn tại.',
            'ngon_ngu.required' => 'Vui lòng chọn ngôn ngữ.',
            'ngon_ngu.in' => 'Ngôn ngữ không hợp lệ.',
            'buttons.*.ten.required_with' => 'Hãy ghi tên nút bấm.',
            'buttons.*.duong_dan.required_with' => 'Vui lòng điền link Url.',
            'buttons.*.duong_dan.url' => 'Link Url không đúng định dạng.',
        ]);
        $data['status'] = $request->has('status') ? 1 : 0;

        // Upload hình ảnh nếu có
        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('banners', 'public');
            $data['hinh_anh'] = '/storage/' . $path;
        }
        $max = Banner::max('thu_tu');
        if ((int) $request->thu_tu > ($max + 1)) {
            return back()->withErrors(['thu_tu' => 'Thứ tự phải liên tục. Giá trị hợp lệ tiếp theo là ' . ($max + 1) . '.'])->withInput();
        }

        // Tạo banner
        $banner = Banner::create($data);

        // Tạo nút bấm nếu có
        // Nếu có nút bấm (checkbox đã bật)
        if ($request->has('buttons')) {
            foreach ($request->buttons as $btn) {
                if (!empty($btn['ten']) && !empty($btn['duong_dan'])) {
                    $banner->buttons()->create($btn);
                }
            }
        }

        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'ten' => ['required', 'string', 'max:255'],
            'mo_ta' => ['nullable', 'string'],
            'ngon_ngu' => ['required', 'string', 'max:10'],
            'thu_tu' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('banners', 'thu_tu')->ignore($banner->id)->where(function ($query) use ($request) {
                    return $query->where('ngon_ngu', $request->ngon_ngu);
                }),
            ],
        ], [
            'thu_tu.unique' => 'Đã có thứ tự hiển thị này.',
        ]);
        $max = Banner::where('id', '!=', $banner->id)->max('thu_tu');
        if ((int) $request->thu_tu > ($max + 1)) {
            return back()->withErrors(['thu_tu' => 'Thứ tự phải liên tục. Giá trị hợp lệ tiếp theo là ' . ($max + 1) . '.'])->withInput();
        }
        $data = $validated;

        // Nếu có cập nhật hình ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa hình ảnh cũ nếu tồn tại
            if ($banner->hinh_anh && Storage::disk('public')->exists(str_replace('/storage/', '', $banner->hinh_anh))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $banner->hinh_anh));
            }

            // Lưu hình ảnh mới
            $path = $request->file('hinh_anh')->store('banners', 'public');
            $data['hinh_anh'] = '/storage/' . $path;
        }

        // Cập nhật trạng thái
        $data['status'] = $request->has('status') ? 1 : 0;

        $oldOrder = $banner->thu_tu;
        $newOrder = (int)$request->thu_tu;
        $data['thu_tu'] = $newOrder;

        if ($newOrder != $oldOrder) {
            // Dời các bản ghi để đảm bảo thứ tự liên tục
            if ($newOrder > $oldOrder) {
                // Giảm thứ tự của tất cả bản ghi sau old (trừ chính nó)
                Banner::where('ngon_ngu', $request->ngon_ngu)
                    ->where('id', '!=', $banner->id)
                    ->where('thu_tu', '>', $oldOrder)
                    ->decrement('thu_tu');

                // Sau đó đẩy banner này xuống max + 1
                $data['thu_tu'] = Banner::where('ngon_ngu', $request->ngon_ngu)->max('thu_tu') + 1;
            } else {
                // Nếu kéo lên, cần dời các bản ghi xuống
                Banner::where('ngon_ngu', $request->ngon_ngu)
                    ->where('id', '!=', $banner->id)
                    ->whereBetween('thu_tu', [$newOrder, $oldOrder - 1])
                    ->increment('thu_tu');
            }
        }



        // Cập nhật banner
        $banner->update($data);

        // Cập nhật nút bấm
        $banner->buttons()->delete();
        if ($request->has('buttons')) {
            foreach ($request->buttons as $btn) {
                if (!empty($btn['ten']) && !empty($btn['duong_dan'])) {
                    $banner->buttons()->create($btn);
                }
            }
        }

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy banner']);
        }

        $banner->status = $request->input('status') ? 1 : 0;
        $banner->save();

        return response()->json(['success' => true]);
    }
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Đã xóa');
    }
}
