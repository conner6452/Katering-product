<?php

namespace App\Providers;

use App\Contracts\Interface\ActivityLogInterface;
use App\Contracts\Interface\AuthInterface;
use App\Contracts\Interface\IngredientRepositoryInterface;
use App\Contracts\Interface\NotificationInterface;
use App\Contracts\Interface\PreOrderInterface;
use App\Contracts\Interface\RuleInterface;
use App\Contracts\Repository\AuthRepository;
use App\Contracts\Repository\IngredientRepository;
use App\Contracts\Repository\NotificationRepository;
use App\Contracts\Repository\RuleRepository;
use App\Contracts\Interface\SupplierInterface;
use App\Contracts\Interface\TransactionInterface;
use App\Contracts\Repository\ActivityLogRepository;
use App\Contracts\Repository\PreOrderRepository;
use App\Contracts\Repository\SupplierRepository;
use App\Contracts\Repository\TransactionRepository;
use App\Models\Ingredient;
use App\Models\PreOrder;
use App\Models\Supplier;
use App\Observers\IngredientObserver;
use App\Observers\PreOrderObserver;
use App\Observers\SupplierObserver;
use App\Models\Rule;
use App\Models\Transaction;
use App\Observers\RuleObserver;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $bindings = [
            IngredientRepositoryInterface::class => IngredientRepository::class,
            AuthInterface::class => AuthRepository::class,
            RuleInterface::class => RuleRepository::class,
            SupplierInterface::class => SupplierRepository::class,
            TransactionInterface::class => TransactionRepository::class,
            ActivityLogInterface::class => ActivityLogRepository::class,
            PreOrderInterface::class => PreOrderRepository::class,
            NotificationInterface::class => NotificationRepository::class
        ];

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ingredient::observe(IngredientObserver::class);
        Supplier::observe(SupplierObserver::class);
        Rule::observe(RuleObserver::class);
        Transaction::observe(TransactionObserver::class);
        PreOrder::observe(PreOrderObserver::class);
    }
}
