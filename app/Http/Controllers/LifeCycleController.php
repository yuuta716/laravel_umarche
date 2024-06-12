<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleController extends Controller
{
    //
public function showServiceContainer() {
    app()->bind("lifeCycleTest",function() {
        return "ライフサイクルテスト";
    });
    $test = app()->make("lifeCycleTest");
    dd($test, app());
}
}
