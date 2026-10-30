<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use App\Models\Competition;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompetitionController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Competition::class);

        $competitions = Competition::query()
            ->latest()
            ->paginate(10);

        return view('competitions.index', compact('competitions'));
    }

    public function create(): View
    {
        Gate::authorize('create', Competition::class);

        return view('competitions.create');
    }

    public function store(StoreCompetitionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Competition::class);

        $competition = Competition::create($request->validated());

        return redirect()
            ->route('competitions.show', $competition)
            ->with('success', 'Competición creada correctamente.');
    }

    public function show(Competition $competition): View
    {
        Gate::authorize('view', $competition);

        return view('competitions.show', compact('competition'));
    }

    public function edit(Competition $competition): View
    {
        Gate::authorize('update', $competition);

        return view('competitions.edit', compact('competition'));
    }

    public function update(
        UpdateCompetitionRequest $request,
        Competition $competition
    ): RedirectResponse {
        Gate::authorize('update', $competition);

        $competition->update($request->validated());

        return redirect()
            ->route('competitions.show', $competition)
            ->with('success', 'Competición actualizada correctamente.');
    }

    public function destroy(Competition $competition): RedirectResponse
    {
        Gate::authorize('delete', $competition);

        $competition->delete();

        return redirect()
            ->route('competitions.index')
            ->with('success', 'Competición eliminada correctamente.');
    }
}