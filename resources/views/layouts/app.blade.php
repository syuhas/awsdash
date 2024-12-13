<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWS Bucket Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">AWS Bucket Manager</a>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="{{ route('cost-explorer') }}" class="nav-link">Cost Explorer</a></li>
            <li class="nav-item"><a href="{{ route('object-explorer') }}" class="nav-link">Object Explorer</a></li>
            <li class="nav-item"><a href="{{ route('object-downloader') }}" class="nav-link">Object Downloader</a></li>
        </ul>
    </header>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>