<?php

namespace Lasallecms\Usermanagement\Listeners;

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

// LaSalle Software
use Lasallecms\Usermanagement\Events\FrontendRegistrationWasSuccessful;
use Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreatePeopleRecord;
use Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateEmailRecord;
use Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateTelephoneRecord;

// Laravel classes
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * Class CreateLaSalleCRMRecordsNewUser
 * @package Lasallecms\Usermanagement\Listeners
 */
class CreateLaSalleCRMRecordsNewUser implements ShouldQueue
{
    /**
     * @var Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreatePeopleRecord
     */
    protected $createPeoplesRecord;

    /**
     * @var Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateEmailRecord
     */
    protected $createEmailsRecord;

    /**
     * @var Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateTelephoneRecord
     */
    protected $createTelephonesRecord;


    /*
     * Create the event listener.
     *
     * @param Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreatePeopleRecord     $createPeoplesRecord
     * @param Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateEmailRecord      $createEmailsRecord
     * @param Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords\CreateTelephoneRecord  $createTelephonesRecord
     * @return void
     */
    public function __construct(
        CreatePeopleRecord     $createPeoplesRecord,
        CreateEmailRecord      $createEmailsRecord,
        CreateTelephoneRecord  $createTelephonesRecord)
    {
        $this->createPeoplesRecord    = $createPeoplesRecord;
        $this->createEmailsRecord     = $createEmailsRecord;
        $this->createTelephonesRecord = $createTelephonesRecord;
    }


    /**
     * Handle the event.
     *
     * @param  FrontendRegistrationWasSuccessful  $event
     * @return void
     */
    //public function handle(FrontendRegistrationWasSuccessful $event)
    public function handle(FrontendRegistrationWasSuccessful $event)
    {
        if (!config('auth.auth_frontend_registration_successful_create_lasallecrm_records')) {
            return;
        }

        // If LaSalleCRM is not installed, abort!
        if ( !class_exists(\Lasallecrm\Lasallecrmadmin\Version::class) ) {
            return;
        }


        // INSERT into the "peoples" database table first, because we need the people_id for subsequent LaSalleCRM INSERTs
        $peoplesResults = $this->createPeoplesRecord->createRecord($event);

        // INSERT into the "emails" database table, and its pivot table
        $emailsResults = $this->createEmailsRecord->createRecord($event, $peoplesResults['people_id']);

        // INSERT into the "telephones" database table, and its pivot table
        if ($event->data['data']['phone_number']) {
            $telephonesResults = $this->createTelephonesRecord->createRecord($event, $peoplesResults['people_id']);
        }
    }
}
