<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal | LigaHub</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #101114;
            color: #f5f5f5;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 20px 6%;
            background: #1b1c21;
            border-bottom: 1px solid #303138;
        }
        .logo { color: #ef4444; font-size: 26px; font-weight: 800; }
        .logo span { color: white; }
        .user { color: #c4c4cc; font-size: 14px; }
        main { width: min(1100px, 90%); margin: 55px auto; }
        .welcome { color: #a1a1aa; line-height: 1.6; }
        h1 { font-size: clamp(28px, 5vw, 42px); margin-bottom: 10px; }
        h1 span { color: #ef4444; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 35px;
        }
        .card {
            padding: 25px;
            border: 1px solid #303138;
            border-radius: 14px;
            background: #1b1c21;
        }
        .card h2 { margin-top: 0; font-size: 19px; }
        .card p { color: #a1a1aa; line-height: 1.6; }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: #451a1a;
            color: #fca5a5;
            font-size: 12px;
        }
        button {
            padding: 10px 15px;
            border: 0;
            border-radius: 8px;
            background: #ef4444;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }
        button:hover { background: #dc2626; }
        @media (max-width: 550px) {
            header { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">LigaHub<span>.</span></div>
        <div class="user">
            {{ auth()->user()->name }}
            <form method="POST" action="{{ route('logout') }}" style="display:inline; margin-left:12px;">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <main>
        <span class="badge">PANEL PRIVADO</span>
        <h1>¡Bienvenido a <span>LigaHub</span>!</h1>
        <p class="welcome">Tu espacio para gestionar y seguir las competiciones de fútbol amateur.</p>

        <section class="grid">
            <article class="card">
                <h2>Mis equipos</h2>
                <p>Consulta y gestiona tus equipos cuando esté disponible la funcionalidad.</p>
            </article>
            <article class="card">
                <h2>Competiciones</h2>
                <p>Accede a la información de las ligas y sus participantes.</p>
            </article>
            <article class="card">
                <h2>Partidos</h2>
                <p>Consulta los encuentros y sus resultados.</p>
            </article>
        </section>
    </main>
</body>
</html>
