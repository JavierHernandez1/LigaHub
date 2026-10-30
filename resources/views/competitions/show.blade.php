<x-app-layout>
    <div class="mx-auto max-w-3xl p-6">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <article class="rounded border bg-white p-6 shadow-sm">
            <h1 class="mb-4 text-2xl font-bold">{{ $competition->name }}</h1>

            <p><strong>Slug:</strong> {{ $competition->slug }}</p>
            <p><strong>Tipo:</strong> {{ $competition->type }}</p>
            <p><strong>Organizador:</strong> {{ $competition->organizer ?: 'Sin especificar' }}</p>

            <div class="mt-4">
                <h2 class="font-semibold">Descripción</h2>
                <p class="mt-1 whitespace-pre-line">{{ $competition->description ?: 'Sin descripción.' }}</p>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('competitions.index') }}"
                   class="rounded border px-4 py-2">Volver al listado</a>

                @can('update', $competition)
                    <a href="{{ route('competitions.edit', $competition) }}"
                       class="rounded bg-blue-600 px-4 py-2 text-white">Editar</a>
                @endcan
            </div>
        </article>
    </div>
</x-app-layout>