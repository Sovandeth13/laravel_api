<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Cart;
use App\Policies\OrderPolicy;
use App\Policies\CartPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Cart::class => CartPolicy::class,
        // Add more policies here
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Optional: Add gates here
    }
}
