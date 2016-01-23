<?php

/**
 *
 * User Management package for the LaSalle Content Management System, based on the Laravel 5 Framework
 * Copyright (C) 2015  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    User Management package for the LaSalle Content Management System
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

/*
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/

/*
 * Admin auth routes
 */
$router->get('admin/login', [
    'as'   => 'admin.login',
    'uses' => 'AdminAuth\AdminLoginController@getLogin'
]);
$router->post('admin/login', [
    'as'   => 'admin.login',
    'uses' => 'AdminAuth\AdminLoginController@postLogin'
]);

$router->get('admin/logout', [
    'as'   => 'admin.logout',
    'uses' => 'AdminAuth\AdminLogoutController@logout'
]);
$router->post('admin/logout', [
    'as'   => 'admin.logout',
    'uses' => 'AdminAuth\AdminLogoutController@destroy'
]);
$router->post('admin/login2fa','AdminAuth\AdminLoginController@post2FALogin');

/*
 * Front-end auth routes
 */
$router->get('login', [
    'as'   => 'auth.login',
    'uses' => 'Frontendauth\FrontendAuthController@getLogin'
]);
$router->post('login', [
    'as'   => 'auth.login',
    'uses' => 'Frontendauth\FrontendAuthController@postLogin'
]);
$router->post('login2fa','Frontendauth\FrontendAuthController@post2FALogin');

$router->get('logout', [
    'as'   => 'auth.logout',
    'uses' => 'Frontendauth\FrontendAuthController@logout'
]);
$router->post('logout','Frontendauth\FrontendAuthController@postLogout');

/*
 * Front-end registration routes
 */
if (config('auth.auth_enable_two_factor_authorization_frontend_registration')) {
    // Two Factor Authorization workflow
    Route::get('register', [
        'as'   => 'auth.register',
        'uses' => 'Frontendauth\Register2faUserController@get2faRegister'
    ]);
    Route::post('register2fa', 'Frontendauth\Register2faUserController@post2faRegisterDisplayForm');
    Route::post('register', 'Frontendauth\Register2faUserController@post2faRegister');

} else {

    // Regular workflow
    Route::get('register', [
        'as'   => 'auth.register',
        'uses' => 'Frontendauth\RegisterUserController@getRegister'
    ]);
    Route::post('register', 'Frontendauth\RegisterUserController@postRegister');
}



// Password reset link request routes...
Route::get('password/email', 'Frontendauth\ResetsPasswordsController@getEmail');
Route::post('password/email', 'Frontendauth\ResetsPasswordsController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Frontendauth\ResetsPasswordsController@getReset');
Route::post('password/reset', 'Frontendauth\ResetsPasswordsController@postReset');

