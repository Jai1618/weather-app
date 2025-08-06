<x-mail::message>
# Welcome to LaraDash, {{ $user->name }}!

We are excited to have you on board. You can now log in and start managing your notes.

<x-mail::button :url="route('login')">
Login Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>