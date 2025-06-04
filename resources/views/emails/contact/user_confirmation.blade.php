@component('mail::message')
# Thank you for contacting us!

Dear {{ $contact->name }},

Thank you for reaching out. We have received your message and will get back to you soon.

Here is a summary of your submission:

**Organisation:** {{ $contact->organisation_name ?? 'N/A' }}  
**Email:** {{ $contact->email }}  
**Phone:** {{ $contact->phone_number ?? 'N/A' }}  
**Website/Social:** {{ $contact->website_or_social_link ?? 'N/A' }}

Thanks for contacting us!  
Best regards,  
{{ config('app.name') }} Team
@endcomponent
