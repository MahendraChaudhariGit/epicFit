<?php

/**
 * Frontend Access Controllers
 */
Route::group(['namespace' => 'Auth'], function () {

    /**
     * These routes require the user to be logged in
     */
    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'AuthController@logout')->name('auth.logout');

        // Change Password Routes
        Route::get('password/change', 'PasswordController@showChangePasswordForm')->name('auth.password.change');
        Route::post('password/change', 'PasswordController@changePassword')->name('auth.password.update');
    });

    /**
     * These routes require the user NOT be logged in
     */
    Route::group(['middleware' => 'guest'], function () {

        // Registration Routes
        Route::get('register', 'AuthController@showRegistrationForm')
            ->name('auth.register');
        Route::post('register', 'AuthController@register');

        // Authentication Routes
      //  Route::get('login/{businessId?}', 'AuthController@showLoginForm')->name('auth.login');
        Route::get('login/{businessUrl?}','AuthController@showLoginForm')->name('auth.login');
        /*Route::get('{businessUrl?}','AuthController@showLoginForm')->name('auth.login');*/
        Route::post('login', 'AuthController@login');
        Route::post('checkuser', 'AuthController@checkUserType');

        // Socialite Routes
        Route::get('login/{provider}', 'AuthController@loginThirdParty')
            ->name('auth.provider');
        //Route::get('index', 'FrontendController@index')->name('frontend.index');

        // Confirm Account Routes
        Route::get('account/confirm/{token}', 'AuthController@confirmAccount')
            ->name('account.confirm');
        Route::get('account/confirm/resend/{token}', 'AuthController@resendConfirmationEmail')
            ->name('account.confirm.resend');

        // Password Reset Routes
        Route::get('password/reset/{token?}', 'PasswordController@showResetForm')
            ->name('auth.password.reset');
        Route::post('password/forgot', 'PasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'PasswordController@reset')->name('password.reset');
    });
});