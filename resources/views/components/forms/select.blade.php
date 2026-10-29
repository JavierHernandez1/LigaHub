@props(['name', 'options' => [], 'selected' => null, 'placeholder' => 'Selecciona una opción'])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-white focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500'
    ]) }}
    @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
>
    <option value="">{{ $placeholder }}</option>

    @foreach ($options as $value => $label)
        <option
            value="{{ $value }}"
            @selected((string) old($name, $selected) === (string) $value)
        >
            {{ $label }}
        </option>
    @endforeach
</select>

@error($name)
    <p id="{{ $name }}-error" class="mt-1 text-sm text-red-400">{{ $message }}</p>
@enderror