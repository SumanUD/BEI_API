<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\HomePage;
use Illuminate\Support\Facades\URL;


class HomePageController extends Controller
{
    public function form()
    {
        $home = HomePage::first(); // Only one row expected
        return view('home.form', compact('home'));
    }

    public function save(Request $request)
    {
        $home = HomePage::firstOrNew([]);

        $request->validate([
            'desktop_full_video' => 'nullable|mimes:mp4,webm|max:51200',
            'mobile_full_video' => 'nullable|mimes:mp4,webm|max:51200',
        ]);

        if ($request->hasFile('desktop_full_video')) {
            if ($home->desktop_full_video) {
                Storage::disk('public')->delete($home->desktop_full_video);
            }
            $home->desktop_full_video = $request->file('desktop_full_video')->store('home/videos', 'public');
        }

        if ($request->hasFile('mobile_full_video')) {
            if ($home->mobile_full_video) {
                Storage::disk('public')->delete($home->mobile_full_video);
            }
            $home->mobile_full_video = $request->file('mobile_full_video')->store('home/videos', 'public');
        }

        $home->save();

        return back()->with('success', 'Home Page videos saved successfully.');
    }


public function jsonHomePage()
{
    $home = HomePage::first();

    if (!$home) {
        return response()->json([
            'status' => 'error',
            'message' => 'Home page data not found.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'desktop_full_video' => $home->desktop_full_video ? url(Storage::url($home->desktop_full_video)) : null,
            'mobile_full_video' => $home->mobile_full_video ? url(Storage::url($home->mobile_full_video)) : null,
        ]
    ]);
}

}
