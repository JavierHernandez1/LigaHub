<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | LigaHub</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #101114;
            color: #f5f5f5;
        }
        .card {
            width: 100%;
            max-width: 440px;
            padding: 36px;
            background: #1b1c21;
            border: 1px solid #303138;
            border-radius: 16px;
        }
        .logo { margin: 0 0 8px; color: #ef4444; font-size: 30px; font-weight: 800; letter-spacing: -1px; }
        .subtitle { margin: 0 0 28px; color: #a1a1aa; line-height: 1.5; }
        label { display: block; margin: 17px 0 8px; font-size: 14px; font-weight: 600; }
        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #41414b;
            border-radius: 8px;
            background: #111216;
            color: white;
            font-size: 15px;
        }
        input:focus { outline: 2px solid #ef4444; border-color: transparent; }
        button {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            border: 0;
            border-radius: 8px;
            background: #ef4444;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        button:hover { background: #dc2626; }
        .footer { margin-top: 24px; text-align: center; color: #a1a1aa; font-size: 14px; }
        a { color: #f87171; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { margin-top: 6px; color: #fca5a5; font-size: 13px; }
        .alert { padding: 12px; border-radius: 8px; background: #451a1a; color: #fecaca; font-size: 14px; }
    </style>
</head>
<body>
    <main class="card">
        <p class="logo">LigaHub<span style="color:white">.</span></p>
        <p class="subtitle">Únete a la comunidad y empieza a gestionar tu equipo.</p>

        <h1>Crear una cuenta</h1>

        @if ($errors->any())
            <div class="alert">Hay errores en el formulario. Revisa los campos indicados.</div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Nombre completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required>
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" autocomplete="new-password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
            @error('password_confirmation')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit">Crear cuenta</button>
        </form>

        <p class="footer">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </main>
</body>
</html>
