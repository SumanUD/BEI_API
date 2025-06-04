<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
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
    // Validate inputs including brand_order
    $validated = $request->validate([
        'brand_order' => 'required|integer|unique:brands,id',
        'brand_name' => 'required|string|max:255',
        'brand_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'youtube_link' => 'nullable|array',
        'youtube_link.*' => 'nullable|url|max:2048',
        'below_video_text' => 'nullable|string|max:6000',
        'image_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'video_gallery' => 'nullable|array',
        'video_gallery.*' => 'nullable|url|max:2048',
        // no validation of video_gallery_video counts or contents here
    ]);

    // Upload brand logo
    $brandLogoPath = null;
    if ($request->hasFile('brand_logo')) {
        try {
            $brandLogoPath = $request->file('brand_logo')->store('brands/logos', 'public');
        } catch (\Exception $e) {
            return back()->withErrors(['brand_logo' => 'Failed to upload brand logo.'])->withInput();
        }
    }

    // Upload banner images
    $bannerImages = [];
    if ($request->hasFile('banner_images')) {
        foreach ($request->file('banner_images') as $image) {
            try {
                $bannerImages[] = $image->store('brands/banners', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['banner_images' => 'Failed to upload one of the banner images.'])->withInput();
            }
        }
    }

    // Upload image gallery
    $imageGallery = [];
    if ($request->hasFile('image_gallery')) {
        foreach ($request->file('image_gallery') as $image) {
            try {
                $imageGallery[] = $image->store('brands/gallery', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['image_gallery' => 'Failed to upload one of the gallery images.'])->withInput();
            }
        }
    }

    // Retrieve video gallery video inputs (uploaded files), titles, thumbnails
    $videoFiles = $request->file('video_gallery_video.video_input', []);
    $videoTitles = $request->input('video_gallery_video.video_title', []);
    $videoThumbnails = $request->file('video_gallery_video.thumbnail_picture', []);

    $videoGalleryVideos = [];

    $maxCount = max(count($videoFiles), count($videoTitles), count($videoThumbnails));

    for ($i = 0; $i < $maxCount; $i++) {
        $videoFile = $videoFiles[$i] ?? null;
        $videoTitle = $videoTitles[$i] ?? '';
        $thumbnailFile = $videoThumbnails[$i] ?? null;

        $videoPath = null;
        $thumbnailPath = null;

        if ($videoFile && $videoFile->isValid()) {
            try {
                $videoPath = $videoFile->store('brands/videos', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['video_gallery_video' => 'Failed to upload video file #' . ($i + 1) . '.'])->withInput();
            }
        }

        if ($thumbnailFile && $thumbnailFile->isValid()) {
            try {
                $thumbnailPath = $thumbnailFile->store('brands/video_thumbnails', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['video_gallery_video' => 'Failed to upload thumbnail for video #' . ($i + 1) . '.'])->withInput();
            }
        }

        $videoGalleryVideos[] = [
            'video_input' => $videoPath ?? '',
            'video_title' => $videoTitle,
            'thumbnail_picture' => $thumbnailPath ?? '',
        ];
    }

    // Save Brand model with custom ID from brand_order
    Brand::create([
        'id' => $validated['brand_order'], // <- custom ID
        'brand_name' => $validated['brand_name'],
        'brand_logo' => $brandLogoPath,
        'banner_images' => json_encode($bannerImages),
        'youtube_link' => json_encode($validated['youtube_link'] ?? []),
        'below_video_text' => $validated['below_video_text'] ?? '',
        'image_gallery' => json_encode($imageGallery),
        'video_gallery' => json_encode($validated['video_gallery'] ?? []),
        'video_gallery_video' => json_encode($videoGalleryVideos),
    ]);

    return redirect()->route('brands.index')->with('success', 'Brand created successfully!');
}


public function edit(Brand $brand)
{
    // Because of $casts in model, these are already arrays:
    $bannerImages = $brand->banner_images;  
    $videoGalleryVideos = $brand->video_gallery_video;
    $youtubeLinks = $brand->youtube_link;
    $imageGallery = $brand->image_gallery;
    $videoGallery = $brand->video_gallery;

    // Pass these variables directly to the view
    return view('brands.edit', compact('brand', 'bannerImages', 'videoGalleryVideos', 'youtubeLinks', 'imageGallery', 'videoGallery'));
}


public function update(Request $request, Brand $brand)
{
    // Validate main inputs
    $validated = $request->validate([
        'brand_name' => 'required|string|max:255',
        'brand_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'banner_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'youtube_link' => 'nullable|array',
        'youtube_link.*' => 'nullable|url|max:2048',
        'below_video_text' => 'nullable|string|max:6000',
        'image_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        'video_gallery' => 'nullable|array',
        'video_gallery.*' => 'nullable|url|max:2048',
        'brand_order' => 'nullable|integer',
    ]);

    // Upload brand logo
    if ($request->hasFile('brand_logo')) {
        if ($brand->brand_logo) {
            Storage::disk('public')->delete($brand->brand_logo);
        }
        try {
            $brand->brand_logo = $request->file('brand_logo')->store('brands/logos', 'public');
        } catch (\Exception $e) {
            return back()->withErrors(['brand_logo' => 'Failed to upload brand logo.'])->withInput();
        }
    }

    // Upload banner images
    if ($request->hasFile('banner_images')) {
        $oldBanners = [];
        if ($brand->banner_images) {
            $oldBanners = is_string($brand->banner_images) ? json_decode($brand->banner_images, true) : $brand->banner_images;
            foreach ($oldBanners as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $bannerImages = [];
        foreach ($request->file('banner_images') as $image) {
            try {
                $bannerImages[] = $image->store('brands/banners', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['banner_images' => 'Failed to upload one of the banner images.'])->withInput();
            }
        }
        $brand->banner_images = json_encode($bannerImages);
    }

    // Upload image gallery
    if ($request->hasFile('image_gallery')) {
        $oldGallery = [];
        if ($brand->image_gallery) {
            $oldGallery = is_string($brand->image_gallery) ? json_decode($brand->image_gallery, true) : $brand->image_gallery;
            foreach ($oldGallery as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $imageGallery = [];
        foreach ($request->file('image_gallery') as $image) {
            try {
                $imageGallery[] = $image->store('brands/gallery', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['image_gallery' => 'Failed to upload one of the gallery images.'])->withInput();
            }
        }
        $brand->image_gallery = json_encode($imageGallery);
    }

    // Handle video_gallery_video
    $videoFiles = $request->file('video_gallery_video.video_input', []);
    $videoTitles = $request->input('video_gallery_video.video_title', []);
    $videoThumbnails = $request->file('video_gallery_video.thumbnail_picture', []);

    $videoGalleryVideos = [];
    $maxCount = max(count($videoFiles), count($videoTitles), count($videoThumbnails));

    $oldVideoGalleryVideos = [];
    if ($brand->video_gallery_video) {
        $oldVideoGalleryVideos = is_string($brand->video_gallery_video) ? json_decode($brand->video_gallery_video, true) : $brand->video_gallery_video;
    }

    for ($i = 0; $i < $maxCount; $i++) {
        $videoPath = null;
        $thumbnailPath = null;

        if (isset($videoFiles[$i]) && $videoFiles[$i]->isValid()) {
            try {
                $videoPath = $videoFiles[$i]->store('brands/videos', 'public');
                if (isset($oldVideoGalleryVideos[$i]['video_input']) && $oldVideoGalleryVideos[$i]['video_input']) {
                    Storage::disk('public')->delete($oldVideoGalleryVideos[$i]['video_input']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['video_gallery_video' => 'Failed to upload video file #' . ($i + 1) . '.'])->withInput();
            }
        } else {
            $videoPath = $oldVideoGalleryVideos[$i]['video_input'] ?? '';
        }

        if (isset($videoThumbnails[$i]) && $videoThumbnails[$i]->isValid()) {
            try {
                $thumbnailPath = $videoThumbnails[$i]->store('brands/video_thumbnails', 'public');
                if (isset($oldVideoGalleryVideos[$i]['thumbnail_picture']) && $oldVideoGalleryVideos[$i]['thumbnail_picture']) {
                    Storage::disk('public')->delete($oldVideoGalleryVideos[$i]['thumbnail_picture']);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['video_gallery_video' => 'Failed to upload thumbnail for video #' . ($i + 1) . '.'])->withInput();
            }
        } else {
            $thumbnailPath = $oldVideoGalleryVideos[$i]['thumbnail_picture'] ?? '';
        }

        $videoTitle = $videoTitles[$i] ?? ($oldVideoGalleryVideos[$i]['video_title'] ?? '');

        $videoGalleryVideos[] = [
            'video_input' => $videoPath ?? '',
            'video_title' => $videoTitle,
            'thumbnail_picture' => $thumbnailPath ?? '',
        ];
    }

    $brand->video_gallery_video = json_encode($videoGalleryVideos);

    $brand->brand_name = $validated['brand_name'];
    $brand->youtube_link = json_encode($validated['youtube_link'] ?? []);
    $brand->below_video_text = $validated['below_video_text'] ?? '';
    $brand->video_gallery = json_encode($validated['video_gallery'] ?? []);

    // Add brand_order if present
    if (isset($validated['brand_order'])) {
        $brand->id = $validated['brand_order'];
    }

    $brand->save();

    return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
}



public function jsonIndex()
{
    $brands = Brand::latest()->get()->map(function ($brand) {

        $brand->brand_logo = $brand->brand_logo ? url(Storage::url($brand->brand_logo)) : null;

        $bannerImages = json_decode($brand->banner_images, true) ?: [];
        $brand->banner_images = collect($bannerImages)->map(function ($image) {
            return url(Storage::url($image));
        })->toArray();

        $imageGallery = json_decode($brand->image_gallery, true) ?: [];
        $brand->image_gallery = collect($imageGallery)->map(function ($image) {
            return url(Storage::url($image));
        })->toArray();

        $youtubeLinks = json_decode($brand->youtube_link, true) ?: [];
        $brand->youtube_link = collect($youtubeLinks)->filter()->values()->toArray();

        $videoGallery = json_decode($brand->video_gallery, true) ?: [];
        $brand->video_gallery = collect($videoGallery)->filter()->values()->toArray();

        $videoGalleryVideos = json_decode($brand->video_gallery_video, true) ?: [];
        $brand->video_gallery_video = collect($videoGalleryVideos)->map(function ($video) {
            return [
                'video_input' => !empty($video['video_input']) ? url(Storage::url($video['video_input'])) : null,
                'video_title' => $video['video_title'] ?? null,
                'thumbnail_picture' => !empty($video['thumbnail_picture']) ? url(Storage::url($video['thumbnail_picture'])) : null,
            ];
        })->toArray();

        return $brand;
    });

    return response()->json([
        'status' => 'success',
        'data' => $brands,
    ]);
}


}
