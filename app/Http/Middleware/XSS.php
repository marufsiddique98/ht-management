<?php

namespace App\Http\Middleware;

use App\Models\LandingPageSection;
use App\Models\Utility;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class XSS
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
        if (Auth::check()) {
            App::setLocale(Auth::user()->lang);

            if (Auth::user()->type == 'admin') {


                $messengerMigration = Utility::get_messenger_packages_migration();
                $Modulemigrations = glob(base_path() . '/Modules/LandingPage/Database' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . '*.php');
                $numberOfUpdatesPending = (count($migrations) + count($Modulemigrations) + $messengerMigration) - count($dbMigrations);
                if ($numberOfUpdatesPending > 0) {
                    return redirect()->route('LaravelUpdater::welcome');
                }
            }
        }
        $input = $request->all();
        // array_walk_recursive($input, function (&$input){
        //     $input = strip_tags($input);
        // });
        $request->merge($input);

        return $next($request);
    }
}
