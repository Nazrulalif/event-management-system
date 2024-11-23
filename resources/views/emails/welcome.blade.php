@component('mail::message')
# Welcome, {{ $user->name }}!

Thank you for registering in our system. Your account has been successfully created.

Your login details are:
- **Username**: {{ $user->email }}
- **Password**: {{ $password }}

Please keep your password safe. You can change it at any time from your profile settings.

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
