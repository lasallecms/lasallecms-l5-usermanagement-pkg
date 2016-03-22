<?php

namespace Lasallecms\Usermanagement\Http\Controllers\AdminAuth;

/**
 *
 * User Management package for the LaSalle Content Management System, based on the Laravel 5 Framework
 * Copyright (C) 2015 - 2016  The South LaSalle Trading Corporation
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
 * @copyright  (c) 2015 - 2016, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

// LaSalle Software
use Lasallecms\Usermanagement\Helpers\TwoFactorAuthorization\TwoFactorAuthHelper;
use Lasallecms\Usermanagement\Http\Controllers\Controller;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

// Laravel classes
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Cookie\Cookiejar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

// Third party classes
use Carbon\Carbon;


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


    /**
     * Middleware
     *
     * @param \Lasallecms\Usermanagement\Helpers\TwoFactorAuthorization\TwoFactorAuthHelper
     */
    public function __construct(TwoFactorAuthHelper $twoFactorAuthHelper) {
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
    public function getLogin(Request $request, Response $response) {
        return view('usermanagement::admin/login/'.config('lasallecmsusermanagement.admin_login_view_folder').'/login');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request) {
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
    protected function handleUserWasAuthenticated(Request $request, $throttles) {

        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        // I've succeeded avoiding CASE statements and ELSE clauses in conditionals. However, this time is different,
        // because I've had a hard time flowing through my 2FA flow. Turns out that expressing it this way, I've
        // removed some code! Once in a while, gotta bite the bullet, I suppose. Note my guilt!

        // Is config set for admin 2FA login?
        if ($this->twoFactorAuthHelper->isAuthConfigEnableTwoFactorAuthAdminLogin()) {

            // All users must go through the 2FA admin login process, so no skipping 2FA!

        } else {

            // The admin can still assign a user to go through the 2FA process, despite the global setting
            if ($this->twoFactorAuthHelper->isUserTwoFactorAuthEnabled(AUTH::user()->id)) {

                // Ah, this specific user must go through the 2FA admin login process

            } else {

                // We're here? Then no 2FA login required for admin login

                // Update the user's last_login fields
                $this->twoFactorAuthHelper->updateUserRecordWithLastlogin(AUTH::user()->id);

                // Onward to the admin!
                return redirect('admin/');
            }
        }


        // Oh, well, 2FA it is!


        // Um, maybe not. If the cookie exists, then no 2FA
        if ($this->twoFactorAuthHelper->isCookieExists()) {

            // Update the user's last_login fields
            $this->twoFactorAuthHelper->updateUserRecordWithLastlogin(AUTH::user()->id);

            // Onward to the admin!
            return redirect('admin/');
        }


        // Does user have their country code and phone number specified?
        if (!$this->twoFactorAuthHelper->existstUserCountryCodeAndPhoneNumber(AUTH::user()->id)) {

            // User is actually logged in at this point, so must log 'em out
            Auth::logout();

            return redirect('admin/login')
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'You do not have a country code and/or a phone number for Two Factor Authorization. Please contact your administrator.',
                ]);
        }


        // Save the user's ID, as the user is actually logged in at this point
        $this->twoFactorAuthHelper->setUserIdSessionVar(AUTH::user()->id);

        // User is actually logged in at this point, so must log 'em out
        Auth::logout();

        // Perform 2FA for login
        $this->twoFactorAuthHelper->doTwoFactorAuthLogin($request->session()->get('user_id'));

        return view('usermanagement::admin/login/'.config('lasallecmsusermanagement.admin_login_view_folder').'/.two_factor_auth');
    }

    /**
     * Handle the admin Two Factor Authorization login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post2FALogin(Request $request) {

        $userId = $request->session()->get('user_id');

        // Did the user take too much time to fill out the form?
        if ($this->twoFactorAuthHelper->isTwoFactorAuthFormTimeout($userId)) {
            return view('usermanagement::admin/login/'.config('lasallecmsusermanagement.admin_login_view_folder').'/login')
                ->withErrors([
                    'Two Factor Authorization' => 'Your two factor authorization code expired. Please re-login.'
                ]);
        }

        // Is the code correct?
        // If not, go back to the 2FA form with an error message
        if (!$this->twoFactorAuthHelper->isInputtedTwoFactorAuthCodeCorrect($userId)) {
            return view('usermanagement::admin/login/'.config('lasallecmsusermanagement.admin_login_view_folder').'/.two_factor_auth')
                ->withErrors([
                    'Two Factor Authorization' => 'Your entered an incorrect two factor authorization code. Please try again.'
                ]);
        }

        // 2FA validated!

        // Clear the user's 2FA code
        $this->twoFactorAuthHelper->clearUserTwoFactorAuthFields($userId);

        // Update the user's last_login fields
        $this->twoFactorAuthHelper->updateUserRecordWithLastlogin($userId);

        // Manually login user
        Auth::loginUsingId($request->session()->get('user_id'));

        // Clear the 'user_id' session variable
        $this->twoFactorAuthHelper->clearUserIdSessionVar();

        // Set the cookie, and onward and forward to the frontend!

        // Ah ah ah! Instantiating a new response view and returning it is causing a message to display
        // before the actual view is rendered. This message looks like cookie information, but it displays
        // whether or not a cookie is created. So... I'm going to see if we need a cookie, and if not,
        // return the view as usual.

        if ((!$this->twoFactorAuthHelper->isCookieExists()) && (config('lasallecmsusermanagement.auth_2fa_cookie_enable'))) {

            // Create the cookie...
            $view = redirect('admin/');
            $response = new \Illuminate\Http\Response($view);
            $response = $this->twoFactorAuthHelper->setCookie($response);
            return $response;
        }

        // Oh, no cookie writing at all...
        return redirect('admin/');
    }
}