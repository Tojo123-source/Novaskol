<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BootController extends Controller
{
    public function init()
    {
        Artisan::call('config:cache', ['--quiet' => true]);
        Artisan::call('route:cache', ['--quiet' => true]);
        return response('ok');
    }

    public function migrate()
    {
        Artisan::call('migrate', ['--force' => true, '--quiet' => true]);
        return response('ok');
    }
}
