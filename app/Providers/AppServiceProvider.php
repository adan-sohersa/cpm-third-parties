<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
			Inertia::share([
				'appName', config('app.name'),
				'ecosystem' => [
					'domain' =>  env('MAIN_DOMAIN'),
				]
			]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
