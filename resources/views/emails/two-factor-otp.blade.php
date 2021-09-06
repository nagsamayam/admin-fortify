@component('mail::message')
# Hello {{ $admin->getFullName() }},

This email contains your Two Factor Authentication code to complete your login at {{ ucfirst(request()->getHttpHost()) }}.

- Code: {{ $admin->getOtp() }}

If you did not make this login attempt you should change your {{ ucfirst(request()->getHttpHost()) }} password immediately since whoever made this request has your correct password.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
