<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function create()
    {
        return view('brands.create');
    }

    public function index()
    {
        $brands = Brand::latest()->get();
        return view('brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'banner_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'youtube_link' => 'nullable|url',
            'below_video_text' => 'nullable|string|max:1000',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_gallery.*' => 'url|nullable',
        ]);

        $bannerImages = [];
        if ($request->hasFile('banner_images')) {
            foreach ($request->file('banner_images') as $image) {
                $bannerImages[] = $image->store('brands/banners', 'public');
            }
        }

        $imageGallery = [];
        if ($request->hasFile('image_gallery')) {
            foreach ($request->file('image_gallery') as $image) {
                $imageGallery[] = $image->store('brands/gallery', 'public');
            }
        }

        Brand::create([
            'banner_images' => $bannerImages,
            'youtube_link' => $validated['youtube_link'],
            'below_video_text' => $validated['below_video_text'],
            'image_gallery' => $imageGallery,
            'video_gallery' => $request->video_gallery, // already array of URLs
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully!');
    }

    public function edit(Brand $brand)
{
    return view('brands.edit', compact('brand'));
}

public function update(Request $request, Brand $brand)
{
    $validated = $request->validate([
        'youtube_link' => 'nullable|url',
        'below_video_text' => 'nullable|string',
        'banner_images.*' => 'nullable|image|mimes:jpg,jpeg,png',
        'image_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png',
        'video_gallery.*' => 'nullable|url',
    ]);

    // Handle file uploads if new files are uploaded
    if ($request->hasFile('banner_images')) {
        $bannerPaths = [];
        foreach ($request->file('banner_images') as $file) {
            $bannerPaths[] = $file->store('brands/banner_images', 'public');
        }
        $brand->banner_images = $bannerPaths;
    }

    if ($request->hasFile('image_gallery')) {
        $imageGalleryPaths = [];
        foreach ($request->file('image_gallery') as $file) {
            $imageGalleryPaths[] = $file->store('brands/image_gallery', 'public');
        }
        $brand->image_gallery = $imageGalleryPaths;
    }

    $brand->youtube_link = $validated['youtube_link'];
    $brand->below_video_text = $validated['below_video_text'];
    $brand->video_gallery = $request->video_gallery ?? [];

    $brand->save();

    return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
}

}
