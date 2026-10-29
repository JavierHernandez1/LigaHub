@props(['for', 'required' => false])

<label for="{{ $for }}" {{ $attributes->merge(['class' => 'mb-2 block text-sm font-medium text-gray-200']) }}>
    {{ $slot }}
    @if ($required)
        <span class="text-red-400" aria-hidden="true">*</span>
    @endif
</label>