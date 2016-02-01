<?php

//namespace Illuminate\Foundation\Auth;

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

// Adapted from https://github.com/laravel/framework/blob/5.1/src/Illuminate/Foundation/Auth/RegistersUsers.php (L5.1)

// LaSalle Software
use Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper;
use Lasallecms\Usermanagement\Http\Controllers\Controller;
use Lasallecms\Usermanagement\Jobs\CreateRegisterUserCommand;
use Lasallecms\Usermanagement\Jobs\Create2faRegisterUserCommand;
use Lasallecms\Usermanagement\Events\FrontendRegistrationWasSuccessful;
use Lasallecms\Lasallecmsapi\Repositories\UserRepository;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

// Laravel classes
use Illuminate\Http\Request;

// Third party classes
use Carbon\Carbon;

/**
 * Front-end user registration with Two Factor Authorization workflow
 *
 * Class Register2faUserController
 * @package Lasallecms\Usermanagement\Http\Controllers\Frontendauth
 */
class Register2faUserController extends Controller
{
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
     * @var Lasallecms\Lasallecmsapi\Repositories\UserRepository
     */
    protected $userRepository;


    /**
     * @param  Lasallecms\Helpers\TwoFactorAuth\TwoFactorAuthHelper $twoFactorAuthHelper
     * @param  Lasallecms\Lasallecmsapi\Repositories\UserRepository $userRepository
     * @return void
     */
    public function __construct(TwoFactorAuthHelper $twoFactorAuthHelper, UserRepository $userRepository) {
        //$this->middleware('guest', ['except' => 'logout']);

        // If logged in, then do not see the register form
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendLoggedInNoRegisterCheck::class);

        // Run through the frontend middleware checks
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);

        // Run through further custom frontend auth checks
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendCustomLoginChecks::class);

        $this->frontend_template_name = config('lasallecmsfrontend.frontend_template_name');

        $this->twoFactorAuthHelper = $twoFactorAuthHelper;

        $this->userRepository = $userRepository;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function get2faRegister() {
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.register.register', [
            'title'                    => 'Register',
            'two_factor_auth_workflow' => true,
        ]);
    }

    /**
     * The front-end 2FA registration form comes here.
     *
     * This method validates the form data only. This validation occurs via the command bus as usual; however,
     * I modified the command bus flow to accommodate this special validation-only. So far (v1.01), this is the
     * one-and-only time where any changes to the command bus are happening. It's happening here because all the
     * validation goodness needs to happen, but there is one extra step to interject before the persist. When this
     * second 2FA is successful, the original command bus, that is used for the regular non-2FA front-end user
     * registration, is used in all its original glory. Meaning, the entire validation happens again. The assumption
     * is that the sanitation and validation will always process flawlessly the second time around.
     *isTwoFactorAuthFormTimeout()
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post2faRegisterDisplayForm(Request $request) {

        // Go through the special validation-only command bus
        $response = $this->dispatchFrom(Create2faRegisterUserCommand::class, $request);

        //Session::flash('status_code', $response['status_code'] );

        if ($response['status_text'] == "validation_failed") {
            Session::flash('message', $response['errorMessages']->first());
            // Return to the edit form with error messages
            return Redirect::back()
                ->withInput($response['data'])
                ->withErrors($response['errorMessages']);
        }


        // The validation is ok. Now, onto the Two Factor Authorization.
        $this->twoFactorAuthHelper->doTwoFactorAuthRegistration($response['data']);


        // The initial validation is ok, but we do not want to set up an actual database record until the 2FA
        // is complete. Otherwise, the bots could create actual records, which is something we are trying to avoid
        // with the 2FA process.

        // The 2FA code session var is set in $this->twoFactorAuthHelper->doTwoFactorAuthRegistration($response['data']);

        return view('usermanagement::frontend.' . $this->frontend_template_name . '.register.two_factor_auth', [
            'title'                    => 'Register',
            'two_factor_auth_workflow' => true,
            'name'                     => $response['data']['name'],
            'email'                    => $response['data']['email'],
            'password'                 => $response['data']['password'],
            'password_confirmation'    => $response['data']['password_confirmation'],
            'phone_country_code'       => $response['data']['phone_country_code'],
            'phone_number'             => $response['data']['phone_number'],
            'formStartDateTime'        => Carbon::now(),
        ]);
    }

    /**
     * Register a new user from the front-end registration form
     *
     * Use the LaSalle Command Bus
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post2faRegister(Request $request) {

        // Did the user take too much time to fill out the form?
        if ($this->twoFactorAuthHelper->isTwoFactorAuthFormTimeout(null, $request->input('formStartDateTime'))) {

            $this->twoFactorAuthHelper->clearTwoFactorAuthCodeToInput();

            return view('usermanagement::frontend.'.$this->frontend_template_name.'.register.register', [
                'title'                    => 'Register',
                'two_factor_auth_workflow' => true,
            ])
            ->withInput($request->only('name', 'email', 'phone_country_code', 'phone_number'))
            ->withErrors([
                'Two Factor Authorization' => 'Your two factor authorization code expired. Please re-start your registration.'
            ]);
        }

        if (!$this->twoFactorAuthHelper->isInputtedTwoFactorAuthCodeCorrect()) {

            $this->twoFactorAuthHelper->clearTwoFactorAuthCodeToInput();

            return view('usermanagement::frontend.' . $this->frontend_template_name . '.register.two_factor_auth', [
                'title'                    => 'Register',
                'two_factor_auth_workflow' => true,
                'name'                     => $request->input('name'),
                'email'                    => $request->input('email'),
                'password'                 => $request->input('password'),
                'password_confirmation'    => $request->input('password_confirmation'),
                'phone_country_code'       => $request->input('phone_country_code'),
                'phone_number'             => $request->input('phone_number'),
                'formStartDateTime'        => $request->input('formStartDateTime'),
            ])
            ->withErrors([
                'Two Factor Authorization' => 'Your entered an incorrect two factor authorization code. Please try again.'
            ]);
        }

        $response = $this->dispatchFrom(CreateRegisterUserCommand::class, $request);

        Session::flash('status_code', $response['status_code'] );

        if ($response['status_text'] == "validation_failed") {
            Session::flash('message', $response['errorMessages']->first());
            // Return to the edit form with error messages
            return Redirect::back()
                ->withInput($response['data'])
                ->withErrors($response['errorMessages']);
        }

        if ($response['status_text'] == "persist_failed") {
            $message = "Processing error. Please try again.";
            Session::flash('message', $message);
            // Return to the edit form with error messages
            return Redirect::back()
                ->withInput($response['data'])
                ->withErrors($message);
        }

        // User registration was successful


        // Login registrant
        if (config('lasallecmsusermanagement.auth_frontend_registration_successful_auto_login')) {
            $userId = $this->userRepository->findUserIdByEmail($response['data']['email']);
            Auth::loginUsingId($userId);
        }


        // Fire the custom event
        event(new FrontendRegistrationWasSuccessful($response));


        if (config('auth.auth_display_register_confirmation_view_after_successful_frontend_registration')) {
            return view('usermanagement::frontend.'.$this->frontend_template_name.'.register_confirmed.register_confirmed', [
                'title'          => 'Register Confirmation',
                'username'       => strtoupper($response['data']['name']),
                'isUserLoggedIn' => Auth::check(),
            ]);
        }

        $name = strtoupper($response['data']['name']);
        $message = 'You successfully registered!';
        Session::flash('message', $message);

        return Redirect::route('users.index');
    }
}