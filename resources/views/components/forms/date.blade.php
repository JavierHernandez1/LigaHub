@props(['name', 'value' => null, 'min' => null, 'max' => null])

<x-forms.input
    :name="$name"
    type="date"
    :value="$value"
    :min="$min"
    :max="$max"
    {{ $attributes }}
/>