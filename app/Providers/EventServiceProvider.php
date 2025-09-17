<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Order;
use App\Observers\OrderObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // ...
    ];

    public function boot(): void
    {
        parent::boot();

        Order::observe(OrderObserver::class);
    }
}
