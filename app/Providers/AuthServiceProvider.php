<?php

namespace App\Providers;

use App\OAuth\Grant\PasswordOverrideGrant;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        app()->afterResolving(AuthorizationServer::class, function (AuthorizationServer $server) {
            $server->enableGrantType($this->makePasswordOverrideGrant(), Passport::tokensExpireIn());
        });

        Passport::routes();
    }

    /**
     * Create and configure a PasswordOverrideGrant instance.
     *
     * @return PasswordOverrideGrant
     */
    private function makePasswordOverrideGrant()
    {
        $grant = new PasswordOverrideGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
