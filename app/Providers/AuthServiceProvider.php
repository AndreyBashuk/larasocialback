<?php

namespace App\Providers;

use App\Models\Message;
use App\Policies\MessagePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Message::class => MessagePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('message.view', 'App\\Policies\\MessagePolicy@view');
        Gate::define('message.create', 'App\\Policies\\MessagePolicy@create');
        Gate::define('message.delete', 'App\\Policies\\MessagePolicy@delete');
        Gate::define('chat.create', 'App\\Policies\\ChatPolicy@create');

        Passport::routes();
        //
    }
}
