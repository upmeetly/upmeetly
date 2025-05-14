<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict(! $this->app->isProduction());

        Relation::enforceMorphMap([
            'user' => Models\User::class,
            'team' => Models\Team::class,
            'meetup' => Models\Meetup::class,
        ]);
    }
}
