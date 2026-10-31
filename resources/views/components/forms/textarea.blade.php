@props(['name', 'value' => null, 'rows' => 4])

<textarea
    name="{{ $name }}"
    id="{{ $name }}"
    rows="{{ $rows }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-white placeholder-gray-500 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500'
    ]) }}
    @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
>{{ old($name, $value) }}</textarea>

@error($name)
    <p id="{{ $name }}-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
@enderror
