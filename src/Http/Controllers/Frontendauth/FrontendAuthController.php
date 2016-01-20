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
use Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper;
use Lasallecms\Usermanagement\Models\User;
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
     * Two Factor Authorization helper class
     * @var string
     */
    protected $twoFactorAuthHelper;

    /**
     * Create a new authentication controller instance.
     *
     * @param \Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper
     * @return void
     */
    public function __construct(TwoFactorAuthHelper $twoFactorAuthHelper) {
        //$this->middleware('guest', ['except' => 'logout']);

        // If user is already logged in, then cannot see the login form
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendLoggedInThenNoLoginFormCheck::class);

        // Run through the frontend middleware checks
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);

        // Run through further custom frontend auth checks
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendCustomLoginChecks::class);

        $this->frontend_template_name = Config::get('lasallecmsfrontend.frontend_template_name');

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
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.login.login', [
            'title' => 'Login',
            ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }


        $credentials = $this->getCredentials($request);
        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }


        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
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
        $userId = AUTH::user()->id;

        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            //return $this->authenticated($request, Auth::user());
        }

        // Is front-end auth config set for 2FA login?
        if (
            (!$this->twoFactorAuthHelper->isAuthConfigEnableTwoFactorAuthLogin())
            && (!$this->twoFactorAuthHelper->isUserTwoFactorAuthEnabled($userId))
        )
        {
            // Update the user's last_login fields
            $this->twoFactorAuthHelper->updateUserRecordWithLastlogin($userId);
            return redirect()->intended($this->redirectPath());
        }

        // Is this individual user enabled for 2FA?
        if (!$this->twoFactorAuthHelper->isUserTwoFactorAuthEnabled($userId)) {

            // Update the user's last_login fields
            $this->twoFactorAuthHelper->updateUserRecordWithLastlogin($userId);
            return redirect()->intended($this->redirectPath());
        }


        // Does user have their country code and phone number specified?
        if (!$this->twoFactorAuthHelper->existstUserCountryCodeAndPhoneNumber(AUTH::user()->id)) {

            // User is actually logged in at this point, so must log 'em out
            Auth::logout();

            return redirect($this->loginPath())
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

        return view('usermanagement::frontend.'.$this->frontend_template_name.'.login.two_factor_auth', [
            'title' => 'Login Enter 2FA Code',
        ]);
    }

    /**
     * Handle the front-end Two Factor Authorization login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post2FALogin(Request $request)
    {
        $userId = $request->session()->get('user_id');

        // Did the user take too much time to fill out the form?
       if ($this->twoFactorAuthHelper->isTwoFactorAuthFormTimeout($userId)) {
           return view('usermanagement::frontend.'.$this->frontend_template_name.'.login.login', [
               'title' => 'Login',
               ])
               ->withErrors([
                   'Two Factor Authorization' => 'Your two factor authorization code expired. Please re-login.'
               ]);
       }

        // Is the code correct?
        // If not, go back to the 2FA form with an error message
        if (!$this->twoFactorAuthHelper->isInputtedTwoFactorAuthCodeCorrect($userId)) {
            return view('usermanagement::frontend.'.$this->frontend_template_name.'.login.two_factor_auth', [
                'title' => 'Login Enter 2FA Code',
            ])
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

        // Go somewhere!
        return redirect()->intended($this->twoFactorAuthHelper->redirectPathUponSuccessfulFrontendLogin());
     }


    /**
     * Do this if 2FA is enabled in config or for individual user AND user not have country code/phone number.
     *
     * @param $request
     * @return mixed
     */
    public function doThisWhenUserNotHaveCountryCodeOrPhoneNumber($request) {
        Auth::logout();
        return redirect('admin/login')
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'You do not have a country code and/or a phone number for Two Factor Authorization. Please contact your administrator.',
            ]);
    }
}