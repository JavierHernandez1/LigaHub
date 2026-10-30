<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LigaHub' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; background: #101114; color: #f3f4f6; font-family: Arial, sans-serif;">
    <header style="background: #1b1c21; border-bottom: 1px solid #333; padding: 18px 32px;">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
            <a href="{{ route('dashboard') }}" style="color: #ef4444; font-size: 24px; font-weight: bold; text-decoration: none;">LigaHub</a>

            <nav style="display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" style="color: #f3f4f6; text-decoration: none;">Panel</a>
                <a href="{{ route('competitions.index') }}" style="color: #f3f4f6; text-decoration: none;">Competiciones</a>

                @if(auth()->check())
                    <span style="color: #9ca3af;">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: #ef4444; color: white; border: 0; border-radius: 6px; padding: 9px 14px; cursor: pointer;">
                            Cerrar sesión
                        </button>
                    </form>
                @endif
            </nav>
        </div>
    </header>

    <main style="max-width: 1200px; margin: 0 auto; padding: 32px 20px;">
        @if(session('success'))
            <div style="background: #14532d; color: #dcfce7; padding: 14px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>