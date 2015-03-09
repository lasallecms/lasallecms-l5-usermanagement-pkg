<?php namespace Lasallecms\Usermanagement\Http\Middleware\Admin;

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

use Illuminate\Http\Request;
use Closure;

use Lasallecms\Usermanagement\Models\User;


// https://github.com/lasallecms/lasallecms-l5-usermanagement-pkg/issues/26
/*
 * Perform custom login checks.
 * These checks occur before the usual login processing commences (middleware)
 */
class CustomAdminAuthChecks  {

    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Allowed IP Addresses
        if ($this->isAllowedIPAddressesCheck()) {
            if (!$this->ipAddressCheck( $this->getAllowedIPAddresses(), $this->getRequestIPAddress($request)) )
                return redirect('admin/login')
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'You are not authorized to login.',
                    ]);
        }

        // User must be enabled
        // This test is mandatory! So, no setting in the config
        if (!$this->UserEnabledCheck($request) ) {
            return redirect('admin/login')
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Your email address is incorrect; or, you are not allowed to login to the admin.',
                ]);
        }

        // User must be activated
        // This test is mandatory! So, no setting in the config
        if (!$this->UserActivatedCheck($request) ) {
            return redirect('admin/login')
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'You are not yet activated to login to the admin.',
                ]);
        }

        // Allowed users
        if ($this->isAllowedUsersCheck()) {
            if (!$this->allowedUsersCheck( $this->getAllowedUsers(), $this->getRequestEmail($request)) )
                return redirect('admin/login')
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'You are not allowed to login.',
                    ]);
        }

        // Allowed user groups
        if ($this->isUserGroupCheck()) {
            if (!$this->allowedUserGroupCheck($this->getAllowedUserGroups(), $this->getRequestUserGroups($request)) )
                return redirect('admin/login')
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'You are not authorized to login to the admin.',
                    ]);
        }

        return $next($request);
    }



    // ARE CHECKS PERFORMED?

    /*
     * Is the allowed IP addresses check performed?
     * From the config.
     *
     * @return bool
     */
    public function isAllowedIPAddressesCheck() {
        if (config('auth.perform_the_ip_addresses_for_admin_check'))
        {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Is the allowed users check performed?
     * From the config.
     *
     * @return bool
     */
    public function isAllowedUsersCheck() {
        if (config('auth.perform_the_usergroups_allowed_to_access_admin_check'))
        {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Is the user group checkpublic function allowedUsersCheck performed?
     * From the config.
     *
     * @return bool
     */
    public function isUserGroupCheck() {
        if (config('auth.perform_the_usergroups_allowed_to_access_admin_check'))
        {
            return true;
        } else {
            return false;
        }
    }


    // FETCH DATA

    /*
    * Get the IP addresses that are allowed to access the admin.
    * From the config.
    *
    * @return array
    */
    public function getAllowedIPAddresses() {
        return config('auth.admin_ip_addresses_allowed');
    }

    /*
     * Get the IP Address of the user asking to be logged into the admin.
     * From the Request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getRequestIPAddress($request) {
        return $request->server('REMOTE_ADDR');
    }

    /*
     * Get the users allowed to login to the admin.
     * From the config.
     *
     * @return array
     */
    public function getAllowedUsers() {
        return config('auth.users_allowed_to_access_the_admin');
    }

    /*
     * Get the email specified in by the user asking to be logged into the admin.
     * From the form request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getRequestEmail($request) {
        $emailRequest = $request->only('email');
        return $emailRequest['email'];
    }


    /*
     * Get the user groups allowed to access the admin.
     * From the config.
     *
     * @return array
     */
    public function getAllowedUserGroups() {
        return config('auth.usergroups_allowed_to_access_the_admin');
    }

    /*
     * Get the user group of the user asking to be logged into the admin.
     * From the Request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getRequestUserGroups($request) {

        // user id of the user trying to log in
        $user_id = $this->user->where('email', '=', $this->getRequestEmail($request) )->get()->first()->id;

        // the user's user groups (actually, the records as a GROUP (model) object
        $user_groups = $this->user->find($user_id)->group;

        // create an array
        $usergroupArray = [];
        foreach ($user_groups as $user_group) {
            $usergroupArray[] = $user_group->title;
        }

        return $usergroupArray;
    }


    // PERFORM CHECKS

    /*
     * Is the user's IP address ok?
     *
     * @param   array   $allowedIPAddresses
     * @param   string  $requestIPAddress
     * @return  bool
     */
    public function ipAddressCheck($allowedIPAddresses, $requestIPAddress) {
        if ( in_array($requestIPAddress, $allowedIPAddresses) )
        {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Is the user enabled?
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function UserEnabledCheck($request) {

        $user = $this->user->where('email', '=', $this->getRequestEmail($request) )->get()->first();

        if ( $user ) {
            return $user->enabled;
        } else {
            return false;
        }
    }

    /*
     * Is the user activated?
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function UserActivatedCheck($request) {

        $user = $this->user->where('email', '=', $this->getRequestEmail($request) )->get()->first();

        if ( $user ) {
            return $user->activated;
        } else {
            return false;
        }
    }

    /*
     * Is the user allowed to access the admin?
     *
     * @param   array   $allowedUsers
     * @param   string  $requestUser
     * @return  bool
     */
    public function allowedUsersCheck($allowedUsers, $requestUser) {
        if ( in_array($requestUser, $allowedUsers) )
        {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Does this user belong to a user group that is allowed to login to the admin?
     *
     * @param   array   $allowedUserGroups
     * @param   string  $requestUserGroup
     * @return bool
     */
    public function allowedUserGroupCheck($allowedUserGroups, $requestUserGroup) {
        $allowedUserGroupCheck = false;

        foreach ($requestUserGroup as $loginUserGroup) {
            if ( in_array($loginUserGroup, $allowedUserGroups) )
            {
                $allowedUserGroupCheck = true;
            }
        }
        return $allowedUserGroupCheck;
    }

}