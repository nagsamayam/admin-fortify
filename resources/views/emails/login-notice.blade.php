@component('mail::message')
# Hello {{ $admin->getFullName() }},

Your {{ config('app.name') }} Admin Account was just signed in on **{{ now()->format('l, j F Y, h:i:s A T') }}:**


- **Device/Type:** {{ Agent::device(). '/'. ucfirst(Agent::deviceType()) }}

- **Browser:** {{ Agent::browser() }}

- **System:** {{ Agent::platform() }}

- **IP Address:** {{ request()->ip() }}

If you did not log in, you should change your {{ config('app.name') }} password immediately since whoever did this has your correct password. You should also check for any changes to your settings.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
