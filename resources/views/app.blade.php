<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(
            [
                'csrfToken' => csrf_token(),
                'baseUrl' => url('/'),
            ],
            JSON_UNESCAPED_UNICODE,
        ) !!};
    </script>
    @routes
    @if (isset($isAdmin) && $isAdmin)
        @vite(['resources/js/admin.js', "resources/js/Pages/{$page['component']}.vue"])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
            integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @elseif(isset($isUser) && $isUser)
        @vite(['resources/js/user.js', "resources/js/Pages/{$page['component']}.vue"])
    @elseif(isset($isLp) && $isLp)
        @vite(['resources/js/lp.js', "resources/js/Pages/{$page['component']}.vue"])
    @else
        @vite(['resources/js/guest.js', "resources/js/Pages/{$page['component']}.vue"])
    @endif
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
