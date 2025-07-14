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

        $validated = $request->validate([
            'desktop_full_video' => 'required|string|max:255',
            'mobile_full_video' => 'required|string|max:255',
        ]);

        $home->desktop_full_video = $validated['desktop_full_video'];
        $home->mobile_full_video = $validated['mobile_full_video'];
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
            'data' => $home->only(['desktop_full_video', 'mobile_full_video']),
        ]);
    }


}
    