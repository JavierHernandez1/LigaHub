<?php

namespace App\Http\Requests;

use App\Models\Competition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCompetitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && !$this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        $competition = $this->route('competition');
        $competitionId = $competition instanceof Competition
            ? $competition->id
            : $competition;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('competitions', 'slug')->ignore($competitionId),
            ],
            'description' => ['nullable', 'string', 'max:10000'],
            'type' => ['required', 'string', 'max:50', Rule::in(['league', 'cup', 'tournament'])],
            'organizer' => ['nullable', 'string', 'max:255'],
        ];
    }
}