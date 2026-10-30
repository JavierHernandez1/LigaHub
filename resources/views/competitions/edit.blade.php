<x-app-layout>
    <div class="mx-auto max-w-3xl p-6">
        <h1 class="mb-6 text-2xl font-bold">Editar competición</h1>

        <form method="POST"
              action="{{ route('competitions.update', $competition) }}"
              class="rounded border bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')
            @include('competitions._form', [
                'competition' => $competition,
                'buttonText' => 'Guardar cambios'
            ])
        </form>
    </div>
</x-app-layout>