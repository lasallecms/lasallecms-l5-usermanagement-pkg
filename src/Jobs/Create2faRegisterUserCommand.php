<?php

namespace Lasallecms\Usermanagement\Jobs;

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


// Laravel Software
use Lasallecms\Usermanagement\Jobs\Command;
use Lasallecms\Lasallecmsapi\Users\CreateUserFormProcessing;

// Laravel classes
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * This is the command used to validate ONLY front-end user registration data. No data persist here.
 *
 * Class Create2faRegisterUserCommand
 * @package Lasallecms\Usermanagement\Jobs
 */
class Create2faRegisterUserCommand extends Command implements SelfHandling
{
    use DispatchesJobs;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $activated;
    public $enabled;
    public $groups;
    public $two_factor_auth_enabled;
    public $phone_country_code;
    public $phone_number;
    public $sms_token;
    public $sms_token_created_at;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password, $password_confirmation, $activated=1, $enabled=1, $groups=[1], $two_factor_auth_enabled=0, $phone_country_code=null, $phone_number=null)
    {
        $this->name                  = $name;
        $this->email                 = $email;
        $this->password              = $password;
        $this->password_confirmation = $password_confirmation;
        $this->activated             = $activated;
        $this->enabled               = $enabled;
        $this->groups                = $groups;
        $this->two_factor_auth_enabled  = $two_factor_auth_enabled;
        $this->phone_country_code    = $phone_country_code;
        $this->phone_number          = $phone_number;
        $this->sms_token             = null;
        $this->sms_token_created_at  = null;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(CreateUserFormProcessing $createUserFormProcessing)
    {
        // call the 2FA method --> the one that validates only (does *not* persist)!
        return $createUserFormProcessing->quarterback2fa($this);
    }
}