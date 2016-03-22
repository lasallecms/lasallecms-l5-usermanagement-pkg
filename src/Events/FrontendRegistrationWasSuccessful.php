<?php

namespace Lasallecms\Usermanagement\Events;

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
use Lasallecms\Usermanagement\Events\Event;

// Laravel facades
use Illuminate\Support\Facades\DB;

// Laravel classes
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class FrontendRegistrationWasSuccessful
 * @package Lasallecms\Usermanagement\Events
 */
class FrontendRegistrationWasSuccessful extends Event
{
    use SerializesModels;

    /**
     * @var array
     */
    public $data;


    /**
     * Create a new event instance.
     *
     * @param  array   $data   The data returned by the create user process
     * @param  Lasallecms\Lasallecmsapi\Repositories\UserRepository $userRepository
     * @return void
     */
    public function __construct($data) {

        // Add the user's ID to the data array
        $data['id'] = $this->findUserIdByEmail($data['data']['email']);
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn() {
        return [];
    }


    /**
     * I want to use Lasallecms/Lasallecmsapi/Repositories/UserRepository, but I'm getting an
     * exception. It worked, but now it does not work. So, I'm going to just do the ID retrieval
     * here, because it's time to move on and just get it done. So much for DRY...
     *
     * Find the user's ID from the user's email address.
     *
     * @param  string  $email
     * @return int
     */
    public function findUserIdByEmail($email) {
        return DB::table('users')
            ->where('email', '=', $email)
            ->value('id')
            ;
    }
}
