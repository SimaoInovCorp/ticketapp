<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Entity;
use App\Models\Inbox;
use App\Models\Ticket;
use App\Policies\ContactPolicy;
use App\Policies\EntityPolicy;
use App\Policies\InboxPolicy;
use App\Policies\TicketPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->configurePolicies();
    }

    protected function configurePolicies(): void
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Entity::class, EntityPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(Inbox::class, InboxPolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
