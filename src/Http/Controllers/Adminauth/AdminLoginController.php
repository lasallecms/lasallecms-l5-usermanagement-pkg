<?php

namespace Lasallecms\Usermanagement\Http\Controllers\AdminAuth;

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

// LaSalle Software
use Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper;
use Lasallecms\Usermanagement\Http\Controllers\Controller;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Validator;

/**
 * Class AdminLoginController
 *
 * Manage admin login/logout
 *
 * @package Lasallecms\Usermanagement\Http\Controllers\Adminauth\AdminLoginController
 */
class AdminLoginController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Two Factor Authorization helper class
     * @var string
     */
    protected $twoFactorAuthHelper;


    /*
     * Middleware
     *
     * @param \Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper
     */
    public function __construct(TwoFactorAuthHelper $twoFactorAuthHelper)
    {
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\Admin\AdminDoNotDisplayLoginFormWhenLoggedInCheck::class, ['only' => 'displayLoginForm']);

        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\Admin\CustomAdminAuthChecks::class, ['only' => 'post']);

        $this->twoFactorAuthHelper = $twoFactorAuthHelper;
    }


    /**
     * Show the application login form.
     *
     * OVERRIDES THE Illuminate\Foundation\Auth\AuthenticatesUsers::getLogin() method
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin(Request $request) {
        return view('usermanagement::admin/login/'.config('auth.admin_login_view_folder').'/login');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember')))
        {
            //return redirect('admin/');
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
dd("postLogin after handleuser call");

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }



        return redirect('admin/login')
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Your login did not succeed. Please try again',
            ]);
    }


    /**
     * Send the response after the user was authenticated.
     *
     * Over-riding the method in Illuminate/Foundation/Auth/AuthenticatesUsers.php
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        // Is front-end auth config set for 2FA login?
        if (!$this->twoFactorAuthHelper->isAuthConfigEnableTwoFactorAuthAdminLogin()) {
            return redirect('admin/');
        }

        // Is this individual user enabled for 2FA?
        if (!$this->twoFactorAuthHelper->isUserTwoFactorAuthEnabled(AUTH::user()->id)) {
            return redirect('admin/');
        }

        // The user is actually logged in already, as standard login is performed first; then, the
        // Two Factor Authorization is performed. So, logout!
        if (Auth::check()) {
            $this->twoFactorAuthHelper->setUserIdSessionVar(AUTH::user()->id);
            Auth::logout();
        }

        // Perform 2FA for login
        $this->twoFactorAuthHelper->doTwoFactorAuthLogin($request->session()->get('user_id'));

        return view('usermanagement::admin/login/'.config('auth.admin_login_view_folder').'/.two_factor_auth');
    }


    /**
     * Handle the admin Two Factor Authorization login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post2FALogin(Request $request)
    {
        $userId = $request->session()->get('user_id');

        // Did the user take too much time to fill out the form?
        if ($this->twoFactorAuthHelper->isTwoFactorAuthFormTimeout($userId)) {
            return view('usermanagement::admin/login/'.config('auth.admin_login_view_folder').'/login')
                ->withErrors([
                    'Two Factor Authorization' => 'Your two factor authorization code expired. Please re-login.'
                ]);
        }

        // Is the code correct?
        // If not, go back to the 2FA form with an error message
        if (!$this->twoFactorAuthHelper->isInputtedTwoFactorAuthCodeCorrect($userId)) {
            return view('usermanagement::admin/login/'.config('auth.admin_login_view_folder').'/.two_factor_auth')
                ->withErrors([
                    'Two Factor Authorization' => 'Your entered an incorrect two factor authorization code. Please try again.'
                ]);
        }

        // 2FA successful!

        // Clear the user's 2FA code
        $this->twoFactorAuthHelper->clearUserTwoFactorAuthFields($userId);

        // Update the user's last_login fields
        $this->twoFactorAuthHelper->updateUserRecordWithLastlogin($userId);

        // Manually login user
        Auth::loginUsingId($request->session()->get('user_id'));

        // Clear the 'user_id' session variable
        $this->twoFactorAuthHelper->clearUserIdSessionVar();

        // Onward and forward to the admin!
        return redirect('admin/');
    }
}