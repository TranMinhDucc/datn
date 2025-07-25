<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');

            $url = asset('storage/' . $path);

            // Lấy tham số CKEditorFuncNum mà CKEditor truyền lên
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');

            return response("<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>")
                ->header('Content-Type', 'text/html');
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'Không nhận được file']
        ]);
    }
}
