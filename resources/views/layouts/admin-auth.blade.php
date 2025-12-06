<!DOCTYPE html>
<html lang="en">
<head>
    @include('auth.comic-style')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Overwatch Admin Login</title>
</head>

<body class="min-h-screen bg-comic-page flex items-center justify-center p-6">

    <!-- Page Content -->
    <main class="w-full max-w-md">
        @yield('content')
    </main>

</body>
</html>
