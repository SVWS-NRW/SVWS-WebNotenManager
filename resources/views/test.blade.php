<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @csrf

</head>
<body class="font-sans antialiased">
    {{ print_r(\App\Http\Resources\Export\DatenResource::collection(\App\Models\Note::all())) }}


</body>
</html>
