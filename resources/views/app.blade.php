<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical" href="https://ultrashots.net/">

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <meta
    name="description"
    content="My professional background in a CV/Resume format, highlighting skills, experiences, and achievements.">

  <title inertia>{{ config('app.name', 'Ultrashots') }}</title>

  <!-- Theme Check and Update -->
{{--  <script>--}}
{{--    const html = document.querySelector('html');--}}
{{--    const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);--}}
{{--    const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);--}}

{{--    if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');--}}
{{--    else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');--}}
{{--    else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');--}}
{{--    else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');--}}
{{--  </script>--}}

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">

  <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @routes
  @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
  @inertiaHead
</head>

<body class="font-sans antialiased dark:bg-neutral-900">
  @inertia
</body>
</html>
