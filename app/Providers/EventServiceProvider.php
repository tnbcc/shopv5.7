<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            \App\Listeners\RegisteredListener::class,
        ],
        \App\Events\OrderPaid::class => [
            \App\Listeners\UpdateProductSoldCount::class,
            \App\Listeners\SendOrderPaidMail::class,
            \App\Listeners\UpdateCrowdfundingProductProgress::class,

        ],
        \App\Events\OrderReviewd::class => [
            \App\Listeners\UpdateProductRating::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
