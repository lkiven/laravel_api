<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //记录SQl日志
        $this->sqlListen();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**记录SQl
     * @return void
     */
    public function sqlListen()
    {
        if (config('database.log_listen') === true) {
            DB::listen(function ($query) {
                $tmp = str_replace('?', '"'.'%s'.'"', $query->sql);
                $qBindings = [];
                foreach ($query->bindings as $key => $value) {
                    if (is_numeric($key)) {
                        $qBindings[] = $value;
                    } else {
                        $tmp = str_replace(':'.$key, '"'.$value.'"', $tmp);
                    }
                }
                $tmp = vsprintf($tmp, $qBindings);
                $tmp = str_replace("\\", "", $tmp);
                Log::channel('sql_listen')->info('SQL执行时间: '.$query->time .'ms'."\n".'  SQL: '.$tmp."\n\t");
            });
        }
    }
}
