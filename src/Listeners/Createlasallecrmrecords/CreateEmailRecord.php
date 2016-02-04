<?php

namespace Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords;

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

// Laravel software
use Lasallecrm\Lasallecrmapi\Models\Email;
use Lasallecms\Lasallecmsapi\Repositories\BaseRepository;

/**
 * Class CreateEmailRecord
 * @package Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords
 */
class CreateEmailRecord
{
    /**
     * Instance of the BASE repository
     *
     * @var Lasallecms\Lasallecmsapi\Repositories\BaseRepository
     */
    protected $repository;

    /**
     * Namespace and class name of the model
     *
     * @var string
     */
    protected $namespaceClassnameModel = "Lasallecrm\Lasallecrmapi\Models\Email";

    /**
     * @var Lasallecrm\Lasallecrmapi\Models\Email
     */
    protected $model;

    /**
     * @param  Lasallecrm\Lasallecrmapi\Models\Email $model
     * @param  Lasallecms\Lasallecmsapi\Repositories\BaseRepository $repository
     * @return void
     */
    public function __construct(Email $model, BaseRepository $repository)
    {
        // Inject the model
        $this->model = $model;

        // Inject repository
        $this->repository = $repository;

        // Inject the relevant model into the repository
        $this->repository->injectModelIntoRepository($this->model->model_namespace."\\".$this->model->model_class);
    }

    /**
     * INSERT the database record
     *
     * @param  array  $eventData
     * @param  int    $peopleId   Primary ID of the peoples database table
     * @return bool
     */
    public function createRecord($eventData, $peopleId) {

        // Basically, just want to throw the data to the "API", and have the database magically updated.
        // Don't want to do the Command Bus thing, although has worked out well! The data's ready, just
        // want to get to the persist.

        // Yeah, how good it would be to just get some DB statements to do the INSERTs, but... it is
        // important to see if I can use the "API" (the API Package) for the persist, because this
        // type of thing will be common in future development

        // We need to assemble the data needed for the persist, so let's create the data array
        $data = [];

        // We need the model's field list from the model
        $data['field_list'] = $this->model->field_list;

        // data from the event command bus DTO
        $data['email_type_id']         = 1;   // "Primary", per lookup_email_types
        $data['title']                 = $eventData->data['data']['email'];
        $data['description']           = "";
        $data['comments']              = "Automatically created during front-end registration,";

        // Pivot Tables
        // If there is NO record to INSERT into the pivot table, then the value is null.
        // If there is a record to INSERT into the pivot table, then use an ARRAY since multple values are acceptable,
        // and are specified in an array. For reference, the pivot table INSERTs @
        // Lasallecms\Lasallecmsapi\Repositories\Traits\persist::associateRelatedRecordsToNewRecord()
        $data['companies']            = null;
        $data['peoples'][]            = $peopleId;

        $saveok = $this->repository->createRecord($data);

        return $saveok;
    }
}