<?php

namespace Lasallecms\Usermanagement\Jobs;

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


// Laravel Software
use Lasallecms\Usermanagement\Jobs\Command;
use Lasallecms\Lasallecmsapi\Users\CreateUserFormProcessing;

// Laravel classes
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;


class CreateRegisterUserCommand extends Command implements SelfHandling
{
    use DispatchesJobs;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $activated;
    public $enabled;
    public $groups;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password, $password_confirmation, $activated=1, $enabled=1, $groups=[1])
    {
        $this->name                  = $name;
        $this->email                 = $email;
        $this->password              = $password;
        $this->password_confirmation = $password_confirmation;
        $this->activated             = $activated;
        $this->enabled               = $enabled;
        $this->groups                = $groups;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(CreateUserFormProcessing $createUserFormProcessing)
    {
        //echo "<pre>";
        //print_r($this);

        // ==> YES, USE CreateUserFormProcessing

        // ==> THE CreateUserCommand / CreateRegisterUserCommand (THIS FILE!!!!) calls CreateUserFormProcessing


        //dd("post register COMMAND HANDLER!");

        //$testing = $createUserFormProcessing->quarterback($this);

        //echo "<pre>";
        //print_r($testing);
        //dd("post register COMMAND HANDLER! AFTER QUARTERBACK!!");


        return $createUserFormProcessing->quarterback($this);

        // LOG USER IN
        // $this->auth->login($user);

        // create LaSalleCRM "people"

        // email new registered user

        // email super administrators

        // return to a new registration welcome form
    }
}