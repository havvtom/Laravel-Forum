@component('mail::message')
# One Last Step

Click on the link below to confirm your email.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
