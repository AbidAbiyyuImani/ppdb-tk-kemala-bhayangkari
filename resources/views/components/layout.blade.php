<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('storage/logo.png') }}" type="image/x-icon">
    <title>PPDB TK Kemala Bhayangkari 24</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
</head>
<body class="min-h-dvh bg-gray-100 flex flex-col">
    <x-logo class="{{ $logoClass ?? '' }}" />
    <x-navbar/>
    <main {{ $attributes->merge(['class' => 'flex-1 p-4']) }}>
        <x-alert redirect="{{ $redirect ?? '' }}"/>
        <div class="container mx-auto">
            {{ $slot }}
        </div>
    </main>
    <x-footer/>
    <script src="{{ asset('plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    @stack('scripts')
    <script>
        $(document).ready(function () {
            const $navToggle = $('#nav-toggle');
            const $toggleButton = $('#toggle-nav');
            const $icon = $('#icon');

            $toggleButton.on('click', function () {
                const isActive = $navToggle.hasClass('active');
                $navToggle.toggleClass('opacity-100 z-50 active', !isActive);
                $navToggle.toggleClass('opacity-0 -z-50', isActive);
                $icon.toggleClass('fa-bars fa-times');
            });

            $(window).on('scroll', function () {
                const isScrolled = $(this).scrollTop() > 10;

                if ($navToggle.hasClass('active')) {
                    $navToggle.removeClass('opacity-100 z-50 active').addClass('opacity-0 -z-50');
                    $icon.removeClass('fa-times').addClass('fa-bars');
                } else {
                    $navToggle.removeClass('opacity-100 z-50').addClass('opacity-0 -z-50');
                }
            });

            $('form').on('submit', function () {
                $(this).find('button[type="submit"], input[type="submit"]').prop('disabled', true);
            });
        });
    </script>
</body>
</html>