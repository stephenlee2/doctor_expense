<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('payment', 'PaymentCrudController');

    Route::post('import', array('as' => 'import', 'uses' => 'PaymentCrudController@import'));
    Route::post('export', array('as' => 'export', 'uses' => 'PaymentCrudController@export'));

    Route::get('payment/autocomplete', array('as' => 'autocomplete', 'uses' => 'PaymentCrudController@autocomplete'));
}); // this should be the absolute last line of this file