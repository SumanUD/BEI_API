<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'string',
            'brand_logo' => 'image|mimes:jpeg,png,jpg,webp',
            'banner_images.*' => 'image|mimes:jpeg,png,jpg,webp',
            'youtube_link' => 'nullable|array',
            'youtube_link.*' => 'nullable|url',
            'below_video_text' => 'nullable|string|max:6000',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,webp',
            'video_gallery' => 'nullable|array',
            'video_gallery.*' => 'nullable|url',
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
            'brand_name' => $validated['brand_name'],
            'brand_logo' => $validated['brand_logo'],
            'banner_images' => $bannerImages,
            'youtube_link' => $validated['youtube_link'] ?? [],
            'below_video_text' => $validated['below_video_text'] ?? '',
            'image_gallery' => $imageGallery,
            'video_gallery' => $validated['video_gallery'] ?? [],
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
            'brand_name' => 'string',
            'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'youtube_link' => 'nullable|array',
            'youtube_link.*' => 'nullable|url',
            'below_video_text' => 'nullable|string|max:6000',
            'image_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'video_gallery' => 'nullable|array',
            'video_gallery.*' => 'nullable|url',
        ]);

        if ($request->hasFile('banner_images')) {
            $bannerPaths = [];
            foreach ($request->file('banner_images') as $file) {
                $bannerPaths[] = $file->store('brands/banners', 'public');
            }
            $brand->banner_images = $bannerPaths;
        }

        if ($request->hasFile('image_gallery')) {
            $imageGalleryPaths = [];
            foreach ($request->file('image_gallery') as $file) {
                $imageGalleryPaths[] = $file->store('brands/gallery', 'public');
            }
            $brand->image_gallery = $imageGalleryPaths;
        }

        $brand->youtube_link = $validated['youtube_link'] ?? [];
        $brand->below_video_text = $validated['below_video_text'] ?? '';
        $brand->video_gallery = $validated['video_gallery'] ?? [];
        $brand->brand_name = $validated['brand_name'] ?? '';

        $brand->save();

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
    }


    public function jsonIndex()
    {
        $brands = Brand::latest()->get()->map(function ($brand) {

            $brand->brand_logo = $brand->brand_logo ? url(Storage::url($brand->brand_logo)) : null;
    

            $brand->banner_images = collect($brand->banner_images)->map(function ($image) {
                return url(Storage::url($image));
            });
    

            $brand->image_gallery = collect($brand->image_gallery)->map(function ($image) {
                return url(Storage::url($image));
            });
    
            return $brand;
        });
    
        return response()->json([
            'status' => 'success',
            'data' => $brands
        ]);
    }
    

}
