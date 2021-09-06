@component('mail::message')
# Hello {{ $admin->getFullName() }},

Your Admin Account was just created on **{{ now()->format('l, j F Y, h:i:s A T') }}:**


- **Full Name:** {{ $admin->getFullName() }}

- **Email:** {{ $admin->getEmail() }}

- **Password:** {{ $password }}

- **For Admin Login:** [Click Here]({{ route('admin.login') }})


Thanks,<br>
{{ config('app.name') }}
@endcomponent
