<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>@yield('title', 'LaunchPad Market')</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('partials.navbar')

@if(session('success'))
<div class="flash flash-success">{{ session('success') }}<button onclick="this.parentElement.remove()">×</button></div>
@endif
@if(session('error'))
<div class="flash flash-error">{{ session('error') }}<button onclick="this.parentElement.remove()">×</button></div>
@endif

<main>@yield('content')</main>

@include('partials.footer')
</body>
</html>