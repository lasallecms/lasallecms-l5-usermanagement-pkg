<?php

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


return [

    /****************************************************************************************/
    /*                        START: FIRST AMONG EQUALS                                     */
    /****************************************************************************************/

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

    /****************************************************************************************/
    /*                          END: FIRST AMONG EQUALS                                     */
    /****************************************************************************************/




    /****************************************************************************************/
    /*                     START: FRONT-END TERMS OF SERVICE                                */
    /****************************************************************************************/

    /*
    |-----------------------------------------------------------------------------------------
    | Require front-end users to click "I have read the Terms of Service" when logging in?
    |-----------------------------------------------------------------------------------------
    |
    | Do you want to require users logging into your front-end to check that they have read
    | your Terms of Service?
    |
    */
    'auth_users_log_into_front_end_require_terms_of_service' => true,

    /*
    |-----------------------------------------------------------------------------------------
    | Require front-end users to click "I have read the Terms of Service" when registering?
    |-----------------------------------------------------------------------------------------
    |
    | Do you want to require new front-end registrants to check that they have read
    | your Terms of Service?
    |
    */
    'auth_users_registration_front_end_require_terms_of_service' => true,

    /*
    |--------------------------------------------------------------------------
    | Terms of Service URL
    |--------------------------------------------------------------------------
    |
    | What is the URL of your Terms of Service?
    |
    */
    'auth_users_log_into_front_end_require_terms_of_service_url' => 'about/terms-of-service',

    /****************************************************************************************/
    /*                        END: FRONT-END TERMS OF SERVICE                               */
    /****************************************************************************************/




    /****************************************************************************************/
    /*                       START: FRONT-END REGISTRATION                                  */
    /****************************************************************************************/

    /*
    |--------------------------------------------------------------------------
    | Allow users to register, and to log into, the front-end
    |--------------------------------------------------------------------------
    |
    | Do you want users to log into the front-end?
    | Has nothing to do with logging into the admin -- pertains to the front-end only.
    |
    */
    'auth_allow_users_to_log_into_front_end' => env('APP_AUTH_ALLOW_USERS_TO_LOG_INTO_FRONT_END'),

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
    | For these automated INSERTs, probably should use first-among-equals user ID
    |
    */
    'auth_user_id_for_created_by_for_frontend_user_registration' => 1,

    /*
    |--------------------------------------------------------------------------
    | Upon successful front-end registration, do you want to automatically login?
    |--------------------------------------------------------------------------
    |
    | If the user successfully registers via the front-end, do you want to
    | automatically login that user?
    |
    */
    'auth_frontend_registration_successful_auto_login' => true,

    /*
    |--------------------------------------------------------------------------
    | Upon successful front-end registration, do you want to send a welcome email?
    |--------------------------------------------------------------------------
    |
    | If the user successfully registers via the front-end, do you want to
    | send them a welcome email?
    |
    */
    'auth_frontend_registration_successful_send_welcome_email' => true,

    /*
    |--------------------------------------------------------------------------
    | Upon successful front-end registration, do you want to send admins an email?
    |--------------------------------------------------------------------------
    |
    | If the user successfully registers via the front-end, do you want to
    | send the super administrators a notification email?
    |
    */
    'auth_frontend_registration_successful_send_admins_email' => true,

    /*
    |--------------------------------------------------------------------------
    | Send the admin notification emails to which super administrators?
    |--------------------------------------------------------------------------
    |
    | If the user successfully registers via the front-end, and you want to
    | send the super administrators a notification email, then which super
    | administrators do you want to receive this notification email?
    |
    | List all the applicable super administrators, including the "first
    | among equals" if so applicable.
    |
    | Users must be super administrators.
    |
    | If this array is empty, then will use the first-among-equals email.
    |
    | An array.
    |
    */
    'auth_frontend_registration_successful_admins_who_receive_notification_email' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Upon successful front-end registration, do you want to create LaSalleCRM records?
    |--------------------------------------------------------------------------
    |
    | If the user successfully registers via the front-end, do you want to
    | create the LaSalleCRM records?
    |
    | If LaSalleCRM is not installed, this step is ignore, even when set to true.
    |
    */
    'auth_frontend_registration_successful_create_lasallecrm_records' => true,

    /****************************************************************************************/
    /*                        END: FRONT-END REGISTRATION                                   */
    /****************************************************************************************/




    /****************************************************************************************/
    /*                     START: TWO FACTOR AUTHORIZATION                                  */
    /****************************************************************************************/

    /*
    |--------------------------------------------------------------------------
    | Enable Two Factor Authorization for front-end REGISTRATION
    |--------------------------------------------------------------------------
    |
    | Do you want to enable 2FA for front-end registration?
    |
    */
    'auth_enable_two_factor_authorization_frontend_registration' => env('APP_AUTH_ENABLE_2FA_FRONTEND_REGISTRATION'),

    /*
    |--------------------------------------------------------------------------
    | Enable Two Factor Authorization for front-end LOGIN
    |--------------------------------------------------------------------------
    |
    | Do you want to enable 2FA for front-end login?
    |
    */
    'auth_enable_two_factor_authorization_frontend_login' => env('APP_AUTH_ENABLE_2FA_FRONTEND_LOGIN'),

    /*
    |--------------------------------------------------------------------------
    | Enable Two Factor Authorization for admin LOGIN
    |--------------------------------------------------------------------------
    |
    | Do you want to enable 2FA for admin login?
    |
    */
    'auth_enable_two_factor_authorization_admin_login' => env('APP_AUTH_ENABLE_2FA_ADMIN_LOGIN'),

    /*
    |--------------------------------------------------------------------------
    | How many minutes does the user have to enter their 2FA code?
    |--------------------------------------------------------------------------
    |
    | The code that is sent to the user's cell phone via sms is live for only a few minutes.
    | After which, the code cannot be used anymore.
    |
    | How many minutes is the code "live"?
    |
    */
    'auth_2fa_minutes_smscode_is_live' => env('APP_AUTH_2FA_MINUTES_SMSCODE_IS_LIVE'),

    /*
    |--------------------------------------------------------------------------
    | Enable a cookie to excuse 2FA?
    |--------------------------------------------------------------------------
    |
    | Do you want to enable a cookie that disables 2FA for users who have successfully
    | logged in already via 2FA?
    |
    | True or false
    |
    */
    'auth_2fa_cookie_enable' => true,

    /*
    |--------------------------------------------------------------------------
    | 2FA cookie's lifetime?
    |--------------------------------------------------------------------------
    |
    | How many DAYS do you want the "auth_2fa_cookie_enable" to last?
    |
    | The maximum number of days is 30.
    |
    | The minimum number of days is 1.
    |
    | The recommended number of days is 7.
    |
    | FYI: Cookies are set in Lasallecms\Helpers\TwoFactorAuth->setCookie($response)
    |
    */
    'auth_2fa_cookie_lifetime_days' => 7,

    /****************************************************************************************/
    /*                      END: TWO FACTOR AUTHORIZATION                                   */
    /****************************************************************************************/




    /****************************************************************************************/
    /*                 START: RESTRICT WHO CAN SEE YOUR WEB APP                             */
    /****************************************************************************************/

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

    /****************************************************************************************/
    /*                   END: RESTRICT WHO CAN SEE YOUR WEB APP                             */
    /****************************************************************************************/




    /****************************************************************************************/
    /*                            START: FOLDERS                                            */
    /****************************************************************************************/

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

    /****************************************************************************************/
    /*                              END: FOLDERS                                            */
    /****************************************************************************************/

];
