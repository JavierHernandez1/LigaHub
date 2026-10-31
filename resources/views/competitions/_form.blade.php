<div class="space-y-5">
    <div>
        <x-forms.label for="name" value="Nombre" />
        <x-forms.input id="name" name="name" type="text"
                       :value="old('name', $competition?->name)"
                       required maxlength="255" class="mt-1 block w-full" />
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-forms.label for="slug" value="Slug (URL)" />
        <x-forms.input id="slug" name="slug" type="text"
                       :value="old('slug', $competition?->slug)"
                       required maxlength="255" class="mt-1 block w-full" />
        <p class="mt-1 text-sm text-gray-500">
            Identificador único, por ejemplo: liga-municipal.
        </p>
        @error('slug')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-forms.label for="type" value="Tipo de competición" />
        <x-forms.select id="type" name="type" required class="mt-1 block w-full">
            <option value="league" @selected(old('type', $competition?->type ?? 'league') === 'league')>Liga</option>
            <option value="cup" @selected(old('type', $competition?->type) === 'cup')>Copa</option>
            <option value="tournament" @selected(old('type', $competition?->type) === 'tournament')>Torneo</option>
        </x-forms.select>
        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-forms.label for="organizer" value="Organizador" />
        <x-forms.input id="organizer" name="organizer" type="text"
                       :value="old('organizer', $competition?->organizer)"
                       maxlength="255" class="mt-1 block w-full" />
        @error('organizer')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <x-forms.label for="description" required>Descripción</x-forms.label>
        <x-forms.textarea
            name="description"
            :value="$competition?->description"
            rows="4"
            maxlength="10000"
            class="mt-1 block w-full"
        />
    </div>

    <div class="flex gap-3">
        <button type="submit"
                class="rounded bg-blue-600 px-4 py-2 text-white">
            {{ $buttonText }}
        </button>

        <a href="{{ route('competitions.index') }}"
           class="rounded border px-4 py-2">
            Cancelar
        </a>
    </div>
</div>