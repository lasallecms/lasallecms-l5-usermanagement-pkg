<?php

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



// *** THIS FILE COMES FROM THE LASALLECMS USER MANGEMENT PACKAGE FOR LARAVEL 5 *** //
// ***          THIS FILE DOES NOT ORIGINATE IN THE LARAVEL APP ITSELF          *** //



return [

	/*
	|--------------------------------------------------------------------------
	| Default Authentication Driver
	|--------------------------------------------------------------------------
	|
	| This option controls the authentication driver that will be utilized.
	| This driver manages the retrieval and authentication of the users
	| attempting to get access to protected areas of your application.
	|
	| Supported: "database", "eloquent"
	|
	*/

	'driver' => 'eloquent',

	/*
	|--------------------------------------------------------------------------
	| Authentication Model
	|--------------------------------------------------------------------------
	|
	| When using the "Eloquent" authentication driver, we need to know which
    | Eloquent model should be used to retrieve your users. Of course, it
    | is often just the "User" model but you may use whatever you like.
	|
	| NOTE: No quotes around namespace + class (new L5.1)
	|
	*/

	'model' => Lasallecms\Usermanagement\Models\User::class,

	/*
	|--------------------------------------------------------------------------
	| Authentication Table
	|--------------------------------------------------------------------------
	|
	| When using the "Database" authentication driver, we need to know which
	| table should be used to retrieve your users. We have chosen a basic
	| default value but you may easily change it to any table you like.
	|
	*/

	'table' => 'users',



    /****************************************************************************************/
    /*                       START: FRONT-END REGISTRATION                                  */
    /****************************************************************************************/

    /*
    |--------------------------------------------------------------------------
    | Allow users to log into the front-end
    |--------------------------------------------------------------------------
    |
    | Do you want users to log into the front-end?
    | Has nothing to do with logging into the admin -- pertains to the front-end only.
    |
    */
    'auth_allow_users_to_log_into_front_end' => 'false',

    /*
    |--------------------------------------------------------------------------
    | User ID for created_by and updated_by when allow front-end user registration
    |--------------------------------------------------------------------------
    |
    | In the admin, a logged in user creates and updates user table records. This
    | user id appears in the "created_by" and/or "updated_by" fields. Someone
    | registering via the front-end is initiating the INSERT themselves. Nevertheless,
    | a user id still needs to be in the "created_by" and "updated_by" fields. So,
    | what user id should be INSERTed into these fields for front-end registration?.
    |
    */
	'auth_user_id_for_created_by_for_frontend_user_registration' => 1,

    /*
    |--------------------------------------------------------------------------
    | Display the register confirmation view after successful frontend registration
    |--------------------------------------------------------------------------
    |
    | Do you want to display the front-end registration confirmation form after
    | a successful front-end registration?
    |
    */
    'auth_display_register_confirmation_view_after_successful_frontend_registration' => true,

    /****************************************************************************************/
    /*                        END: FRONT-END REGISTRATION                                   */
    /****************************************************************************************/


	/*
	|--------------------------------------------------------------------------
	| Password Reset Settings
	|--------------------------------------------------------------------------
	|
	| Here you may set the options for resetting passwords including the view
	| that is your password reset e-mail. You can also set the name of the
	| table that maintains all of the reset tokens for your application.
	|
	| The expire time is the number of minutes that the reset token should be
	| considered valid. This security feature keeps tokens short-lived so
	| they have less time to be guessed. You may change this as needed.
	|
	*/

	'password' => [
		'email' => 'usermanagement::emails.password',
		'table' => 'password_resets',
		'expire' => 60,
	],


    /*
	|--------------------------------------------------------------------------
	| Forbidden Top Level Domains
	|--------------------------------------------------------------------------
	|
	| Email addresses with these top level domains will not be registered
	|
	| Examples: '.ru', '.cn'
	|
	*/

    'forbiddenTLDs' => [
        '.ru',
        '.cn',
    ],


    /*
	|--------------------------------------------------------------------------
	| Admin Login Form View Folder
	|--------------------------------------------------------------------------
	|
	| The login form is created independently of the admin template. It's just something
    | on its own.
    |
    | The login blade file is located at:
    | resources/views/vendor/usermanagement/admin/login/default
    |
    | The css file for the login blade file is located at:
    | public/packages/usermanagement/admin/login/default/
    |
	*/
    'admin_login_view_folder' => 'default',


    /*
	|--------------------------------------------------------------------------
	| Admin Logout Form View Folder
	|--------------------------------------------------------------------------
	|
	| The logout form is created independently of the admin template. It's just something
    | on its own.
    |
    | The login blade file is located at:
    | resources/views/vendor/usermanagement/admin/logout/default
    |
    | The css file for the login blade file is located at:
    | public/packages/usermanagement/admin/logout/default/
    |
    | The logout confirmation blade file is located at:
    | resources/views/vendor/usermanagement/admin/logout_confirmation/
    |
    | The css file for the logout confirmation blade file is located at:
    | public/packages/usermanagement/admin/logout_confirmation/default/
	|
	*/
    'admin_logout_view_folder' => 'default',


    /*
    |--------------------------------------------------------------------------
    | Administrator, first among equals
    |--------------------------------------------------------------------------
    |
    | The "Super Administrator" who can *NOT* be deleted in the user table.
    |
    | Use the email address.
    |
    */
    'administrator_first_among_equals_email' => '',


    /*
    |--------------------------------------------------------------------------
    | Admin IP Addresses allowed
    |--------------------------------------------------------------------------
    |
    | Users from the following IP addresses are permitted to login to the admin.
    | Leave blank if not applicable.
    | If you use a package for user/login/registration, then this may not work!
    |
    */
    'perform_the_ip_addresses_for_admin_check' => true,
    'admin_ip_addresses_allowed' => [
        '127.0.0.1',
    ],


    /*
    |--------------------------------------------------------------------------
    | Users who are allowed to access the admin
    |--------------------------------------------------------------------------
    |
    | These users are allowed to access the admin.
    |
    | ** These users are still subject to other custom admin checks.**
    |
    | The reason for this setting is that, in my experience, only a handful of people
    | are allowed access to the admin -- especially Super Admins. So, my thinking goes,
    | list these peoples' emails in the config, and then any other emails trying to access
    | the admin will be rejected.
    |
    | Use in conjunction with the "Admin IP Addresses allowed" setting.
    |
    | Please note that "admin@example.org" is the user initial set up so you can log into the admin. Once you have
    | logged into the admin and changed your user details, then update this setting -- if this user is not the
    | "first among equals" user.
    |
    | Use email addresses.
    |
    */
    'perform_the_users_allowed_to_access_admin_check' => true,
    'users_allowed_to_access_the_admin' => [
        'admin@example.org',
    ],

    /*
    |--------------------------------------------------------------------------
    | User groups that are allowed to access the admin
    |--------------------------------------------------------------------------
    |
    | These user groups are allowed to access the admin.
    |
    | Sure, this specification is *supposed* to be buried in the code somewhere. But..
    | a quick way to prevent "Administrators" from accessing the admin during a testing
    | phase or maintenance or during some brief time period, without messing around with
    | the permanent group assignments.
    |
    */
    'perform_the_usergroups_allowed_to_access_admin_check' => 'true',
    'usergroups_allowed_to_access_the_admin' => [
        'Super Administrator',
        'Administrator',
    ],
];
