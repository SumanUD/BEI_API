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
        // dd('here');

        return view('about.create', compact('about'));
    }

    public function create()
    {

        return view('about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'banner_video' => 'nullable|file|mimes:mp4,webm|max:20000',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'about_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'team_members.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'team_members.*.description' => 'nullable|string|max:1000',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'about_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [];

        // Upload banner video
        if ($request->hasFile('banner_video')) {
            $data['banner_video'] = $request->file('banner_video')->store('about/banner_video', 'public');
        }

        // Upload images
        if ($request->hasFile('about_image')) {
            $data['about_image'] = $request->file('about_image')->store('about/about_image', 'public');
        }

        if ($request->hasFile('about_bg_image')) {
            $data['about_bg_image'] = $request->file('about_bg_image')->store('about/about_bg_image', 'public');
        }

        // Upload team member images
        $team = [];
        if ($request->has('team_members')) {
            foreach ($request->team_members as $member) {
                $imagePath = null;
                if (isset($member['image']) && is_file($member['image'])) {
                    $imagePath = $member['image']->store('about/team_members', 'public');
                }
                $team[] = [
                    'image' => $imagePath,
                    'description' => $member['description'] ?? '',
                ];
            }
        }
        $data['team_members'] = $team;

        // Social links
        $data['email'] = $request->email;
        $data['linkedin'] = $request->linkedin;

        // Upload gallery images
        $gallery = [];
        if ($request->hasFile('about_gallery')) {
            foreach ($request->file('about_gallery') as $image) {
                $gallery[] = $image->store('about/gallery', 'public');
            }
        }
        $data['about_gallery'] = $gallery;

        AboutUs::create($data);

        return redirect()->route('about-us.index')->with('success', 'About Us data saved successfully.');
    }

    public function edit(AboutUs $about_u)
    {
        return view('about.edit', ['about' => $about_u]);
    }

    public function update(Request $request, AboutUs $about_u)
    {
        $request->validate([
            'banner_video' => 'nullable|file|mimes:mp4,webm|max:20000',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'about_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'team_members.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'team_members.*.description' => 'nullable|string|max:1000',
            'email' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'about_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [];

        // Handle optional file replacements
        if ($request->hasFile('banner_video')) {
            $data['banner_video'] = $request->file('banner_video')->store('about/banner_video', 'public');
        }

        if ($request->hasFile('about_image')) {
            $data['about_image'] = $request->file('about_image')->store('about/about_image', 'public');
        }

        if ($request->hasFile('about_bg_image')) {
            $data['about_bg_image'] = $request->file('about_bg_image')->store('about/about_bg_image', 'public');
        }

        // Team members update
        $team = [];
        if ($request->has('team_members')) {
            foreach ($request->team_members as $member) {
                $imagePath = $member['existing_image'] ?? null;
                if (isset($member['image']) && is_file($member['image'])) {
                    $imagePath = $member['image']->store('about/team_members', 'public');
                }
                $team[] = [
                    'image' => $imagePath,
                    'description' => $member['description'] ?? '',
                ];
            }
        }
        $data['team_members'] = $team;

        $data['email'] = $request->email;
        $data['linkedin'] = $request->linkedin;

        $gallery = [];
        if ($request->hasFile('about_gallery')) {
            foreach ($request->file('about_gallery') as $image) {
                $gallery[] = $image->store('about/gallery', 'public');
            }
        }
        $data['about_gallery'] = $gallery;

        $about_u->update($data);

        return redirect()->route('about-us.index')->with('success', 'About Us updated successfully.');
    }
}
