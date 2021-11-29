@component('mail::message')
Hello! **{{ $email }}**

<p>Thanks for joining {{ Config('app.name') }} to gain all the free promtion to gain more fiture.Your account is created but has to be verified before you can use it.</p>

@component('mail::button', ['url' => $url,'color'=>'primary'])
Verified Account 
@endcomponent

<span>After verified you may login to  {{ Config('app.name') }}</span>
<br>Thanks,<br>
{{ config('app.name') }}
@endcomponent
