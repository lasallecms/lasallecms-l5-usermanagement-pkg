<?php

namespace Lasallecms\Usermanagement\Http\Controllers\Auth;

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
 * @version    1.0.0
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

// LaSalle Software
use Lasallecms\Usermanagement\Validation\ForbiddenTLD;

// Laravel classes
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;


trait AuthenticatesAndRegistersUsers
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The registrar implementation.
     *
     * @var Registrar
     */
    protected $registrar;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        //return view('auth.register');
        //https://github.com/lasallecms/lasallecms-l5-flagship/issues/16
        return view( $this->registerPathView() );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request, ForbiddenTLD $ForbiddenTLD)
    {
        $validator = $this->registrar->validator($request->all());

        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }

        // Custom forbidden top level domain validation.
        // If this validation fails then induce WSOD
        // first, convert the request array to a string
        $email = implode(" ",$request->only('email'));
        if ( !$ForbiddenTLD->validateForbiddenTLD($email) ) return;

        $this->auth->login($this->registrar->create($request->all()));

        // assign this newly registered (and logged in) user the "Registered" group
        $this->registrar->createusergroup($this->auth->user()->id);

        return redirect($this->redirectPath());
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        //return view('auth.login');
        // https://github.com/lasallecms/lasallecms-l5-flagship/issues/16
        return view( $this->loginPathView() );
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
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember')))
        {
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPathController())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect('/');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath'))
        {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Get the view path to the login route.
     *
     * @return string
     */
    public function loginPathView()
    {
        return property_exists($this, 'loginPathView') ? $this->loginPathView : 'auth.login';
    }

    /**
     * Get the controller path to the login route.
     *
     * @return string
     */
    public function loginPathController()
    {
        return property_exists($this, 'loginPathController') ? $this->loginPathController : '/auth/login';
    }

    /**
     * Get the path to the register route.
     *
     * @return string
     */
    public function registerPathView()
    {
        return property_exists($this, 'registerPathView') ? $this->registerPathView : '/auth/register';
    }

}