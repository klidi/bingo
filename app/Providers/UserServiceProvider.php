<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 9:50 PM
 */

namespace App\Providers;

use App\Services\GameService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\Interfaces\GameServiceInterface', function() {
            return new GameService($this->app->get('App\Services\Interfaces\BingoServiceInterface'));
        });
    }
}

