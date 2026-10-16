<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('league')->index();
            $table->string('organizer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('age_group')->nullable()->index();
            $table->string('gender')->default('mixed')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->index();
            $table->string('address')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('status')->default('draft')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['competition_id', 'category_id', 'name']);
            $table->index(['competition_id', 'status']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city')->index();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city', 'name']);
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('document_number')->nullable()->unique();
            $table->date('birth_date')->nullable()->index();
            $table->string('preferred_foot')->default('right');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['last_name', 'first_name']);
        });

        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('home_team_id')->constrained('teams')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('matchday');
            $table->dateTime('scheduled_at')->index();
            $table->string('status')->default('scheduled')->index();
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['season_id', 'matchday', 'home_team_id', 'away_team_id'], 'fixtures_round_teams_unique');
            $table->index(['season_id', 'matchday']);
            $table->index(['home_team_id', 'away_team_id']);
        });

        Schema::create('player_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('season_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedTinyInteger('squad_number');
            $table->string('position')->index();
            $table->date('joined_on');
            $table->date('left_on')->nullable();
            $table->boolean('is_captain')->default(false)->index();
            $table->timestamps();

            $table->unique(['player_id', 'season_id'], 'player_single_team_per_season_unique');
            $table->unique(['team_id', 'season_id', 'squad_number'], 'squad_number_per_team_season_unique');
            $table->index(['team_id', 'season_id']);
        });

        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('player_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('type')->index();
            $table->unsignedTinyInteger('minute');
            $table->unsignedTinyInteger('extra_minute')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['fixture_id', 'minute']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_events');
        Schema::dropIfExists('player_team');
        Schema::dropIfExists('fixtures');
        Schema::dropIfExists('players');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('competitions');
    }
};
