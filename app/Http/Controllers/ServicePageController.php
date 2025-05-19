<?php

namespace App\Http\Controllers;

use App\Models\ServicePage;
use Illuminate\Http\Request;
class ServicePageController extends Controller
{
    public function index()
    {
        $service = ServicePage::first();
        return view('pages.services', compact('service'));
    }
    
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'brand_strategy' => 'nullable|string',
            'creative' => 'nullable|string',
            'packaging' => 'nullable|string',
            'social_media' => 'nullable|string',
            'digital_media' => 'nullable|string',
            'seo_website_ecommerce' => 'nullable|string',
        ]);
    
        $service = ServicePage::first() ?? new ServicePage();
    
        $service->brand_strategy = $request->brand_strategy;
        $service->creative = $request->creative;
        $service->packaging = $request->packaging;
        $service->social_media = $request->social_media;
        $service->digital_media = $request->digital_media;
        $service->seo_website_ecommerce = $request->seo_website_ecommerce;
        
        $service->save();
    
        return back()->with('success', 'Service Page updated successfully.');
    }
    public function jsonServicePage()
    {
        $service = ServicePage::first();
    
        if (!$service) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service page content not found.'
            ], 404);
        }
    
        $data = [
            'status' => 'success',
            'data' => [
                'brand_strategy' => $service->brand_strategy,
                'creative' => $service->creative,
                'packaging' => $service->packaging,
                'social_media' => $service->social_media,
                'digital_media' => $service->digital_media,
                'seo_website_ecommerce' => $service->seo_website_ecommerce,
            ]
        ];
    
        // Use this trick: return the JSON as a string with header and disable JSON escaping by encoding yourself
        return response(
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            200,
            ['Content-Type' => 'application/json']
        );
    }

}