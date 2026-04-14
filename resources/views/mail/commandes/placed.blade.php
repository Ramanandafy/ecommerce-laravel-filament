<x-mail::message>
# Commande placer avec success

Merci pour votre commande. Votre numero de commande est : {{ $commande->id }} .

<x-mail::button :url="$url">
vue commande
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
