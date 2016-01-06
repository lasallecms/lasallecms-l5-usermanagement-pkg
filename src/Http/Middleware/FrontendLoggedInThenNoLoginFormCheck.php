<?php 

namespace Lasallecms\Usermanagement\Http\Middleware;

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

// Laravel classes
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Support\Facades\Auth;

// PHP
use Closure;

class FrontendLoggedInThenNoLoginFormCheck implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        // if logged in and requesting to see the login form, display the logout form
        if ((Auth::check()) && ($request->is('login'))) {
            return redirect()->route('auth.logout');
        }

        // if *not* logged in and requesting to see the logout form, display the login form
        if ((!Auth::check()) && ($request->is('logout'))) {
            return redirect()->route('auth.login');
        }

        // if logged in and requesting to see the password/email (reset request) form, then go home
        if ((Auth::check()) && ($request->is('password/email'))) {
            return redirect()->route('home');
        }

        // if logged in and requesting to see the password/reset (reset) form, then go home
        if ((Auth::check()) && ($request->is('password/reset'))) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}