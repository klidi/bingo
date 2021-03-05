<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 10:29 PM
 */

namespace App\Providers;

use App\Services\BingoService;
use Illuminate\Support\ServiceProvider;

class BingoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\Interfaces\BingoServiceInterface', function() {
            return new BingoService();
        });
    }
}
