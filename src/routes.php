<?php

/**
 * Package routing file specifies all of this package routes.
 */

use Wabasta\PanelBuilder\Models\Menu;
use Illuminate\Support\Facades\View;

if (config('panelbuilder.standaloneRoutes')) {
    return;
}

if (Schema::hasTable('menus')) {
    $menus = Menu::with('children')->where('menu_type', '!=', 0)->orderBy('position')->get();
    View::share('menus', $menus);
    if (! empty($menus)) {
        Route::group([
            'middleware' => ['web', 'auth', 'role'],
            'prefix'     => config('panelbuilder.route'),
            'as'         => config('panelbuilder.route') . '.',
            'namespace'  => 'App\Http\Controllers',
        ], function () use ($menus) {
            foreach ($menus as $menu) {
                switch ($menu->menu_type) {
                    case 1:
                        Route::post(strtolower($menu->name) . '/massDelete', [
                            'as'   => strtolower($menu->name) . '.massDelete',
                            'uses' => 'Admin\\' . ucfirst(camel_case($menu->name)) . 'Controller@massDelete'
                        ]);
                        Route::resource(strtolower($menu->name),
                            'Admin\\' . ucfirst(camel_case($menu->name)) . 'Controller', ['except' => 'show']);
                        break;
                    case 3:
                        Route::get(strtolower($menu->name), [
                            'as'   => strtolower($menu->name) . '.index',
                            'uses' => 'Admin\\' . ucfirst(camel_case($menu->name)) . 'Controller@index',
                        ]);
                        break;
                }
            }
        });
    }
}

Route::group([
    'namespace'  => 'Wabasta\PanelBuilder\Controllers',
    'middleware' => ['web', 'auth']
], function () {
    // Dashboard home page route
    Route::get(config('panelbuilder.homeRoute'), config('panelbuilder.homeAction','PanelBuilderController@index'));
    Route::group([
        'middleware' => 'role'
    ], function () {
        // Menu routing
        Route::get(config('panelbuilder.route') . '/menu', [
            'as'   => 'menu',
            'uses' => 'PanelBuilderMenuController@index'
        ]);
        Route::post(config('panelbuilder.route') . '/menu', [
            'as'   => 'menu',
            'uses' => 'PanelBuilderMenuController@rearrange'
        ]);

        Route::get(config('panelbuilder.route') . '/menu/edit/{id}', [
            'as'   => 'menu.edit',
            'uses' => 'PanelBuilderMenuController@edit'
        ]);
        Route::post(config('panelbuilder.route') . '/menu/edit/{id}', [
            'as'   => 'menu.edit',
            'uses' => 'PanelBuilderMenuController@update'
        ]);

        Route::get(config('panelbuilder.route') . '/menu/crud', [
            'as'   => 'menu.crud',
            'uses' => 'PanelBuilderMenuController@createCrud'
        ]);
        Route::post(config('panelbuilder.route') . '/menu/crud', [
            'as'   => 'menu.crud.insert',
            'uses' => 'PanelBuilderMenuController@insertCrud'
        ]);

        Route::get(config('panelbuilder.route') . '/menu/parent', [
            'as'   => 'menu.parent',
            'uses' => 'PanelBuilderMenuController@createParent'
        ]);
        Route::post(config('panelbuilder.route') . '/menu/parent', [
            'as'   => 'menu.parent.insert',
            'uses' => 'PanelBuilderMenuController@insertParent'
        ]);

        Route::get(config('panelbuilder.route') . '/menu/custom', [
            'as'   => 'menu.custom',
            'uses' => 'PanelBuilderMenuController@createCustom'
        ]);
        Route::post(config('panelbuilder.route') . '/menu/custom', [
            'as'   => 'menu.custom.insert',
            'uses' => 'PanelBuilderMenuController@insertCustom'
        ]);

        Route::get(config('panelbuilder.route') . '/actions', [
            'as'   => 'actions',
            'uses' => 'UserActionsController@index'
        ]);
        Route::get(config('panelbuilder.route') . '/actions/ajax', [
            'as'   => 'actions.ajax',
            'uses' => 'UserActionsController@table'
        ]);
    });
});

Route::group([
    'namespace'  => 'App\Http\Controllers',
    'middleware' => ['web']
], function () {
    // Point to App\Http\Controllers\UsersController as a resource
    Route::group([
        'middleware' => 'role'
    ], function () {
        Route::resource('users', 'UsersController');
        Route::resource('roles', 'RolesController');
    });
    Route::auth();
});
