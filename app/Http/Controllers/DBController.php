<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DBController extends Controller
{
    public function  dbMigration()
    {

        // Artisan::call('migrate:fresh', [
        //     '--force' => true
        // ]);

        // Artisan::call('db:seed', [
        //     '--force' => true
        // ]);

        //         Artisan::call('migrate', [
        //     '--force' => true
        // ]);

        // Artisan::call('db:seed');

        return Artisan::output();
    }
}
