<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::latest()->get();
        $editNews = null;

        if ($request->has('edit')) {
            $editNews = News::findOrFail($request->edit);
        }

        return view('news.index', compact('news', 'editNews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'news_title' => 'required|string|max:255',
            'thumbnail_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'news_link' => 'required|url',
        ]);

        if ($request->hasFile('thumbnail_picture')) {
            $data['thumbnail_picture'] = $request->file('thumbnail_picture')->store('news/thumbnails', 'public');
        }

        News::create($data);
        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'news_title' => 'required|string|max:255',
            'thumbnail_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'news_link' => 'required|url',
        ]);

        if ($request->hasFile('thumbnail_picture')) {
            if ($news->thumbnail_picture) {
                Storage::disk('public')->delete($news->thumbnail_picture);
            }
            $data['thumbnail_picture'] = $request->file('thumbnail_picture')->store('news/thumbnails', 'public');
        }

        $news->update($data);
        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->thumbnail_picture) {
            Storage::disk('public')->delete($news->thumbnail_picture);
        }

        $news->delete();
        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }

    public function allNews()
    {
        $news = News::latest()->get()->map(function ($item) {
            return [
                'news_title' => $item->news_title,
                'news_link' => $item->news_link,
                'thumbnail_picture' => $item->thumbnail_picture ? url(Storage::url($item->thumbnail_picture)) : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ]);
    }
}
