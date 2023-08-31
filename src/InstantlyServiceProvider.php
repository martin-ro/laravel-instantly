<?php

namespace MartinRo\Instantly;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class InstantlyServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/instantly.php' => config_path('instantly.php'),
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            InstantlyClient::class,
            'instantly',
        ];
    }
}
