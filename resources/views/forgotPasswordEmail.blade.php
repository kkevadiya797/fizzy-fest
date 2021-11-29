@component('mail::message')
    Hello! **{{ $email }}**
{{-- @component('mail::panel') --}}
   <p> You are receiving this email because we received a password reset request for your account.</p>
{{-- @endcomponent --}}
@component('mail::button', ['url' => $url,'color'=>'primary'])
Click button to Create New Password
@endcomponent

<span>If you did not request a password reset, no further action is required.</span>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
