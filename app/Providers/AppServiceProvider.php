<?php

namespace App\Providers;

use Faker\Generator as FakerGenerator;
use Faker\Factory as FakerFactory;
use Illuminate\Pagination\Paginator;
use App\Models\Declaration;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create('ja_JP');
        });
        Paginator::useBootstrap();

        // 認可
        $this->registerPolicies();
        Gate::define('admin_gate', function(User $user) {
            return $user->admin == 1;
        });
        Gate::define('edit_gate', function(User $user, Declaration $declaration) {
            return $user->id == $declaration->user_id && strtotime(date('Y/m/d')) < strtotime($declaration->start_date);
        });
        Gate::define('delete_gate', function(User $user, Declaration $declaration) {
            return $user->id == $declaration->user_id && (strtotime($declaration->start_date) > strtotime(date('Y/m/d')) || strtotime(date('Y/m/d')) > strtotime($declaration->end_date));
        });
        Gate::define('report_gate', function(User $user, Declaration $declaration) {
            return $user->id == $declaration->user_id && $declaration->report == null && strtotime(date('Y/m/d')) > strtotime($declaration->end_date);
        });
    }
}
