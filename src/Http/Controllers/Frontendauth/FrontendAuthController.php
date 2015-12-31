<?php

namespace Lasallecms\Usermanagement\Http\Controllers\Frontendauth;

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

/// LaSalle Software
use Lasallecms\Usermanagement\Models\User;
use Lasallecms\Usermanagement\Http\Controllers\Controller;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Class FrontendAuthController
 *
 * Manage front-end login/logout
 *
 * @package Lasallecms\Usermanagement\Http\Controllers\Frontendauth
 */
class FrontendAuthController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Where to redirect users when frontend login fails.
     *
     * @var string
     */
    protected $loginPath = 'login';

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * The name of the front-end template where the auth views are located
     *
     * @var string
     */
    protected $frontend_template_name;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('guest', ['except' => 'logout']);

        // Run through the frontend middleware checks
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);

        // Run through further custom frontend auth checks
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendCustomLoginChecks::class);

        $this->frontend_template_name = Config::get('lasallecmsfrontend.frontend_template_name');
    }


    /**
     * Show the application login form.
     *
     * OVERRIDES THE Illuminate\Foundation\Auth\AuthenticatesUsers::getLogin() method
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin() {
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.login.login', [
            'title' => 'Login',
            ]);
    }

    /**
     * Front-end's "are you sure you want to logout?" form
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.logout.logout', [
            'title' => 'Logout',
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogout() {
        Auth::logout();

        return view('usermanagement::frontend.'.$this->frontend_template_name.'.logout_confirmed.logout_confirmed', [
            'title' => 'Logout Confirmation'
        ]);
    }
}