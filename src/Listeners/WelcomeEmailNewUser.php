<?php

namespace Lasallecms\Usermanagement\Listeners;

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
use Lasallecms\Usermanagement\Events\FrontendRegistrationWasSuccessful;

// Laravel facades
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

// Laravel classes
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class WelcomeEmailNewUser
 * @package Lasallecms\Usermanagement\Listeners
 */
class WelcomeEmailNewUser implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Handle the event.
     *
     * @param  FrontendRegistrationWasSuccessful  $event
     * @return void
     */
    public function handle(FrontendRegistrationWasSuccessful $event)
    {
        if (!config('lasallecmsusermanagement.auth_frontend_registration_successful_send_welcome_email')) {
            return;
        }

        // create data array from the event DTO
        $data = $event->data;

        // data needed to send the email
        $from            = config('mail.from'); // this config setting is itself an array
        $data['replyTo'] = $from['address'];    // need just the 'address' array element
        $data['subject'] = 'Welcome to ' . config('lasallecmsfrontend.site_name');

        // data used in the body of the email, in the blade file
        $data['data']['site_name'] = config('lasallecmsfrontend.site_name');

        Mail::queue('usermanagement::emails.RegisterNewUserWelcome', $data, function ($message) use ($data) {
            $message->subject($data['subject'])
                    ->to($data['data']['email'])
                    ->replyTo($data['replyTo']);
        });

    }
}
