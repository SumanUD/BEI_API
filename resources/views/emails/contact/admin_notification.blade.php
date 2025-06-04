@component('mail::message')
# New Contact Message Received

You have received a new contact message.

**Name:** {{ $contact->name }}  
**Organisation:** {{ $contact->organisation_name ?? 'N/A' }}  
**Email:** {{ $contact->email }}  
**Phone:** {{ $contact->phone_number ?? 'N/A' }}  
**Website/Social:** {{ $contact->website_or_social_link ?? 'N/A' }}

@component('mail::button', ['url' => route('admin.contact.index')])
View Messages
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
