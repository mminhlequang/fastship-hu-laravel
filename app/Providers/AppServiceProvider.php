<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Knuckles\Scribe\Scribe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    \Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        if (class_exists(\Knuckles\Scribe\Scribe::class)) {
            Scribe::beforeResponseCall(function (Request $request, ExtractedEndpointData $endpointData) {
                $token = User::first()->api_token;
                $request->headers->add(["Authorization" => "Bearer $token"]);
            });
        }
    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

	    if ($this->app->environment() !== 'production') {
		    $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
	    }
    }
}
