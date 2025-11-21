<x-mail::message>
# Hello {{ $permit->full_name }}

Your Lab Use Permit request has been:

# **{{ strtoupper($decision) }}**

@isset($permit->admin_notes)
### Notes from Admin:
{{ $permit->admin_notes }}
@endisset

Thank you for using Applied Informatics Laboratory services.

Regards,  
**AI Lab Polinema**
</x-mail::message>
