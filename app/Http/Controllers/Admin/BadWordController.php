<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BadWord;

class BadWordController extends Controller
{
    public function index()
    {
        $badwords = BadWord::latest()->get();
        $badwords = BadWord::orderBy('id', 'desc')->paginate(10);
        return view('admin.badwords.index', compact('badwords'));
    }

    public function create()
    {
        return view('admin.badwords.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $words = explode(',', $request->word);

        if (count($words) > 0) {
            foreach ($words as $word) {
                $word = trim($word);
                if ($word == '') continue;
                BadWord::create(['word' => $word]);
            }
        }

        return redirect()->route('admin.badwords.index')->with('success', 'Từ khóa đã được thêm.');
    }

    public function edit(BadWord $badword)
    {
        return view('admin.badwords.edit', compact('badword'));
    }

    public function update(Request $request, BadWord $badword)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:bad_words,word,' . $badword->id,
        ]);

        $badword->update(['word' => $request->word]);

        return redirect()->route('admin.badwords.index')->with('success', 'Từ khóa đã được cập nhật.');
    }

    public function destroy(BadWord $badword)
    {
        $badword->delete();
        return redirect()->route('admin.badwords.index')->with('success', 'Từ khóa đã được xóa.');
    }
}
