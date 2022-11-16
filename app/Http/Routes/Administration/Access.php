<?php

Route::group([
    'prefix'     => 'access',
    'namespace'  => 'Access',
    'middleware' => 'access.routeNeedsPermission:view-access-management',
], function() {
    /**
     * User Management
     */
    Route::group(['namespace' => 'User'], function() {
        Route::resource('users', 'UserController', ['except' => ['show']]);

        Route::get('users/deactivated', 'UserController@deactivated')->name('administration.access.users.deactivated');
        Route::get('users/deleted', 'UserController@deleted')->name('administration.access.users.deleted');
        Route::get('account/confirm/resend/{user_id}', 'UserController@resendConfirmationEmail')->name('administration.account.confirm.resend');

        /**
         * Specific User
         */
        Route::group(['prefix' => 'user/{id}', 'where' => ['id' => '[0-9]+']], function() {
            Route::get('delete', 'UserController@delete')->name('administration.access.user.delete-permanently');
            Route::get('restore', 'UserController@restore')->name('administration.access.user.restore');
            Route::get('mark/{status}', 'UserController@mark')->name('administration.access.user.mark')->where(['status' => '[0,1]']);
            Route::get('password/change', 'UserController@changePassword')->name('administration.access.user.change-password');
            Route::post('password/change', 'UserController@updatePassword')->name('administration.access.user.change-password');
        });
    });

    /**
     * Role Management
     */
    Route::group(['namespace' => 'Role'], function() {
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
    });

    /**
     * Permission Management
     */
    Route::group(['prefix' => 'roles', 'namespace' => 'Permission'], function() {
        Route::resource('permission-group', 'PermissionGroupController', ['except' => ['index', 'show']]);
        Route::resource('permissions', 'PermissionController', ['except' => ['show']]);

        Route::group(['prefix' => 'groups'], function() {
            Route::post('update-sort', 'PermissionGroupController@updateSort')->name('administration.access.roles.groups.update-sort');
        });
    });
});