@props(['name', 'value' => '1', 'checked' => false])

<div>
    <label class="inline-flex items-center gap-2 text-sm text-gray-200">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            @checked(old($name, $checked))
            {{ $attributes->merge([
                'class' => 'rounded border-gray-600 bg-gray-900 text-red-600 focus:ring-red-500'
            ]) }}
        >
        <span>{{ $slot }}</span>
    </label>

    @error($name)
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>