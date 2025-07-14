<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();
        return view('pages.about-us', compact('about'));
    }

    public function teamMemberTemplate(Request $request)
    {
        $index = $request->get('index', 0);
        return view('pages.partials.team-member', compact('index'));
    }


public function storeOrUpdate(Request $request)
{
    $validated = $request->validate([
        'desktop_video' => 'required|string|max:255',
        'mobile_video' => 'required|string|max:255',
        'description' => 'nullable|string',
        'right_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'team_members.*.photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'team_members.*.description' => 'nullable|string',
        'team_members.*.email' => 'nullable|email',
        'team_members.*.linkedin' => 'nullable|url',
        'team_members.*.website' => 'nullable|url',
        'team_members.*.old_photo' => 'nullable|string',
    ]);

    $about = AboutUs::first() ?? new AboutUs();

    // These are just string values (URLs or paths), no file upload handling needed
    $about->desktop_video = $validated['desktop_video'];
    $about->mobile_video = $validated['mobile_video'];
    $about->description = $request->description;

    // Handle optional image upload for right image
    if ($request->hasFile('right_image')) {
        $about->right_image = $request->file('right_image')->store('aboutus/images', 'public');
    }

    // Process team members
    $team_members = [];
    if ($request->has('team_members')) {
        foreach ($request->team_members as $index => $memberData) {
            $photoPath = null;

            if (isset($memberData['photo']) && $memberData['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $photoPath = $memberData['photo']->store('aboutus/team', 'public');
            } elseif (!empty($memberData['old_photo'])) {
                $photoPath = $memberData['old_photo'];
            }

            $team_members[] = [
                'name' => $memberData['name'] ?? '',
                'photo' => $photoPath,
                'description' => $memberData['description'] ?? '',
                'email' => $memberData['email'] ?? '',
                'linkedin' => $memberData['linkedin'] ?? '',
                'website' => $memberData['website'] ?? '',
            ];
        }
    }

    $about->team_members = $team_members;

    $about->save();

    return back()->with('success', 'About Us page updated successfully.');
}



public function jsonAboutUs()
{
    $about = AboutUs::first();

    if (!$about) {
        return response()->json([
            'status' => 'error',
            'message' => 'About Us content not found.'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'desktop_video' => $about->desktop_video ?? null,
            'mobile_video' => $about->mobile_video ?? null,
            'right_image' => $about->right_image ? url(Storage::url($about->right_image)) : null,
            'description' => $about->description ?? '',
            'team_members' => collect($about->team_members)->map(function ($member) {
                return [
                    'name' => $member['name'] ?? '',
                    'photo' => !empty($member['photo']) ? url(Storage::url($member['photo'])) : null,
                    'description' => $member['description'] ?? '',
                    'email' => $member['email'] ?? '',
                    'linkedin' => $member['linkedin'] ?? '',
                    'website' => $member['website'] ?? '',
                ];
            })->toArray(),
        ]
    ]);
}

    
    
}
