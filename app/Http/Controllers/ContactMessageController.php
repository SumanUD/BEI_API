<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactFormAdminNotification;
use App\Mail\ContactFormUserConfirmation;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{

    public function index()
    {
        return view('contact.index');
    }

    public function show($id)
    {
        $contact = ContactMessage::findOrFail($id);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = ContactMessage::findOrFail($id);
        $contact->delete();

        return response()->json(['status' => true, 'message' => 'Message deleted successfully.']);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'organisation_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|digits:10',
            'website_or_social_link' => 'nullable|url|max:255',
        ]);


        // Return errors if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the contact message
        $contact = ContactMessage::create($validator->validated());
        
        // Send email to admin
        Mail::to(config('mail.from.address'))->send(new ContactFormAdminNotification($contact));

        // Send confirmation email to user
        Mail::to($contact->email)->send(new ContactFormUserConfirmation($contact));


        return response()->json([
            'status' => true,
            'message' => 'Message submitted successfully!',
            'data' => $contact,
        ]);
    }
}
