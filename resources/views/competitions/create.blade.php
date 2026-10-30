<x-app-layout>
    <div class="mx-auto max-w-3xl p-6">
        <h1 class="mb-6 text-2xl font-bold">Crear competición</h1>

        <form method="POST" action="{{ route('competitions.store') }}"
              class="rounded border bg-white p-6 shadow-sm">
            @csrf
            @include('competitions._form', [
                'competition' => null,
                'buttonText' => 'Crear competición'
            ])
        </form>
    </div>
</x-app-layout>