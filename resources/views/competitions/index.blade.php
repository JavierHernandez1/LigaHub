<x-app-layout>
    <div class="mx-auto max-w-7xl p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Competiciones</h1>

            @can('create', App\Models\Competition::class)
                <a href="{{ route('competitions.create') }}"
                   class="rounded bg-blue-600 px-4 py-2 text-white">
                    Nueva competición
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($competitions as $competition)
            <article class="mb-4 rounded border bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">
                    <a href="{{ route('competitions.show', $competition) }}">
                        {{ $competition->name }}
                    </a>
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tipo: {{ $competition->type }}
                    @if ($competition->organizer)
                        · Organizador: {{ $competition->organizer }}
                    @endif
                </p>

                @can('update', $competition)
                    <a class="mt-3 inline-block text-blue-700"
                       href="{{ route('competitions.edit', $competition) }}">
                        Editar
                    </a>
                @endcan

                @can('delete', $competition)
                    <form class="mt-2"
                          method="POST"
                          action="{{ route('competitions.destroy', $competition) }}"
                          onsubmit="return confirm('¿Seguro que quieres eliminar esta competición?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-700" type="submit">Eliminar</button>
                    </form>
                @endcan
            </article>
        @empty
            <p class="rounded border bg-white p-5">
                Todavía no hay competiciones registradas.
            </p>
        @endforelse

        <div class="mt-6">
            {{ $competitions->links() }}
        </div>
    </div>
</x-app-layout>