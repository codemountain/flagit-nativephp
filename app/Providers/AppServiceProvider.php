<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        Relation::morphMap([
            'report' => \App\Models\Report::class,
            'note'   => \App\Models\Note::class,
            'attachment' => \App\Models\Attachment::class,
            'worklog' => \App\Models\Worklog::class,
            // add others as needed
        ]);
    }
}
