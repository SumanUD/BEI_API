<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsiteLoader;
use Illuminate\Support\Facades\Storage;

class WebsiteLoaderController extends Controller
{
    public function edit()
    {
        $loader = WebsiteLoader::first() ?? new WebsiteLoader();
        return view('website-loader.edit', compact('loader'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'gif' => 'nullable|file|mimes:gif|max:2048',
        ]);

        $loader = WebsiteLoader::first() ?? new WebsiteLoader();

        if ($request->hasFile('gif')) {
            // Delete old file if exists
            if ($loader->gif_path && Storage::disk('public')->exists($loader->gif_path)) {
                Storage::disk('public')->delete($loader->gif_path);
            }

            $path = $request->file('gif')->store('loader', 'public');
            $loader->gif_path = $path;
        }

        $loader->save();

        return redirect()->back()->with('success', 'Loader GIF updated successfully.');
    }

    public function apiLoader()
    {
        $loader = WebsiteLoader::first();

        if (!$loader || !$loader->gif_path) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loader not set.',
                'gif_url' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'gif_url' => asset('storage/' . $loader->gif_path),
        ]);
    }
}
