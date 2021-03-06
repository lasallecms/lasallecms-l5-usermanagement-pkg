<?php

namespace Lasallecms\Usermanagement\Http\Controllers\Frontendauth;

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

// Adapted from https://github.com/laravel/framework/blob/5.1/src/Illuminate/Foundation/Auth/RegistersUsers.php (L5.1)

// LaSalle Software
use Lasallecms\Usermanagement\Http\Controllers\Controller;
use Lasallecms\Usermanagement\CommandBus\CreateRegisterUserCommand;
use Lasallecms\Usermanagement\Events\FrontendRegistrationWasSuccessful;
use Lasallecms\Lasallecmsapi\Repositories\UserRepository;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

// Laravel classes
use Illuminate\Http\Request;


/**
 * Regular Front-end user registration workflow
 *
 * Class Register2faUserController
 * @package Lasallecms\Usermanagement\Http\Controllers\Frontendauth
 */
class RegisterUserController extends Controller
{
    /**
     * The name of the front-end template where the auth views are located
     *
     * @var string
     */
    protected $frontend_template_name;

    /**
     * @var Lasallecms\Lasallecmsapi\Repositories\UserRepository
     */
    protected $userRepository;


    /**
     * Create a new authentication controller instance.
     *
     * @param  Lasallecms\Lasallecmsapi\Repositories\UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository) {

        //$this->middleware('guest', ['except' => 'logout']);

        // If logged in, then do not see the register form
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendLoggedInNoRegisterCheck::class);

        // Run through the frontend middleware checks
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);

        // Run through further custom frontend auth checks
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendCustomLoginChecks::class);

        $this->frontend_template_name = config('lasallecmsfrontend.frontend_template_name');

        $this->userRepository = $userRepository;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister() {
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.register.register', [
            'title'                    => 'Register',
            'two_factor_auth_workflow' => false,
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
    public function postRegister(Request $request) {

        if (config('lasallecmsusermanagement.auth_users_registration_front_end_require_terms_of_service')) {
            if (!$request->input('terms-of-service')) {
                return redirect()->route('auth.register')
                    ->withInput($request->only('name', 'email', 'phone_country_code', 'phone_number'))
                    ->withErrors([
                        'terms-of-service' => 'Please read our Terms of Service',
                    ]);
            }
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


        // Display the registration confirmation view, if the config says so
        if (config('auth.auth_display_register_confirmation_view_after_successful_frontend_registration')) {
            return view('usermanagement::frontend.'.$this->frontend_template_name.'.register_confirmed.register_confirmed', [
                'title'          => 'Register Confirmation',
                'username'       => strtoupper($response['data']['name']),
                'isUserLoggedIn' => Auth::check(),
            ]);
        }


        // $name = strtoupper($response['data']['name']);
        $message = 'You successfully registered!';
        Session::flash('message', $message);

        return Redirect::route('home');
    }
}