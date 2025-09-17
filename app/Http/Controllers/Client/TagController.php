<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    // /tag/{slug}
    public function show(string $slug)
    {
        $tag = Tag::query()
            ->where('is_active', true)
            ->where('slug', Str::slug($slug, '-'))
            ->firstOrFail();

        // Đẩy sang trang lọc hiện có, giữ đúng cơ chế filter của bạn
        return redirect()->route('client.products.filterSidebar', [
            'tags[]'    => $tag->slug,
            'tags_mode' => request('tags_mode', 'any'),
        ]);
    }
}
