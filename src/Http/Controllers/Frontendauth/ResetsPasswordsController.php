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

// Fork of Illuminate\Foundation\Auth\ResetsPasswords
// https://github.com/lasallecms/lasallecms-l5-flagship/issues/20

// Updated this trait to L5.1.27

// LaSalle Software classes
use Lasallecms\Usermanagement\Http\Controllers\Controller;

// Laravel facades
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ResetsPasswordsController
 * @package Lasallecms\Usermanagement\Http\Controllers\Frontendauth
 */
class ResetsPasswordsController extends Controller
{
    //use RedirectsUsers;

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

        $this->middleware('guest');

        // If user is already logged in, then cannot see the reset form
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendLoggedInThenNoLoginFormCheck::class);

        // Run through the frontend middleware checks
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);

        // Run through further custom frontend auth checks
        $this->middleware(\Lasallecms\Usermanagement\Http\Middleware\FrontendCustomLoginChecks::class);

        $this->frontend_template_name = Config::get('lasallecmsfrontend.frontend_template_name');
    }


    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function getEmail() {
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.password.password', [
            'title' => 'Password Reset Request'
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postEmail(Request $request) {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                //return redirect()->back()->with('status', trans($response));
                return view('usermanagement::frontend.'.$this->frontend_template_name.'.password.password_email_sent', [
                    'title' => 'Password Reset Email Sent',
                    'email' => $request->input('email'),
                ]);

            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject() {
        return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null) {
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }

        //return view('usermanagement::auth.reset')->with('token', $token);
        return view('usermanagement::frontend.'.$this->frontend_template_name.'.password.reset', [
            'title' => 'Reset Password',
            'token' => $token,
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postReset(Request $request) {

        $this->validate($request, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response)
        {
            case Password::PASSWORD_RESET:
                //return redirect($this->redirectPath())->with('status', trans($response));
                return view('usermanagement::frontend.'.$this->frontend_template_name.'.password.password_reset_confirmation', [
                    'title'     => 'Password Reset Confirmed',
                    'email'     => $request->input('email'),
                    'username'  => Auth::user()->name,
                ]);

            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath() {

        if (property_exists($this, 'redirectPath'))
        {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password) {
        $user->password = bcrypt($password);
        $user->save();

        Auth::login($user);
    }
}