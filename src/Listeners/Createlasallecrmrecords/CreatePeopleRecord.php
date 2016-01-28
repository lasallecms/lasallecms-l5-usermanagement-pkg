<?php

namespace Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords;

// LaSalle Software
use Lasallecrm\Lasallecrmapi\Models\People as Model;
use Lasallecms\Lasallecmsapi\Repositories\BaseRepository;


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

// Laravel software
use Lasallecrm\Lasallecrmapi\Models\People;

// "as Base" due to "Cannot use Lasallecms\Lasallecmsapi\Repositories\BaseRepository as
// BaseRepository because the name is already in use" error
use Lasallecms\Lasallecmsapi\Repositories\BaseRepository as Base;

/**
 * Class CreatePeopleRecord
 * @package Lasallecms\Usermanagement\Listeners\Createlasallecrmrecords
 */
class CreatePeopleRecord
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
    protected $namespaceClassnameModel = "Lasallecrm\Lasallecrmapi\Models\People";

    /**
     * @var Lasallecrm\Lasallecrmapi\Models\People
     */
    protected $model;

    /**
     * @param  Lasallecrm\Lasallecrmapi\Models\People $model
     * @param  Lasallecms\Lasallecmsapi\Repositories\BaseRepository $repository
     * @return void
     */
    public function __construct(People $model, Base $repository)
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
     * @param  object  $eventData
     * @return array
     */
    public function createRecord($eventData) {


       /* echo "<h1>yer event object </h1>";
        echo "name = ".$eventData->data['data']['name'];
        echo "<pre>";
        print_r($eventData);
        dd("CreatePeopleRecord");
*/

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
        $data['composite_title']       = $eventData->data['data']['name'];
        $data['salutation']            = "";
        $data['first_name']            = $this->splitName($eventData->data['data']['name'], 'firstname');
        $data['middle_name']           = "";
        $data['surname']               = $this->splitName($eventData->data['data']['name'], 'surname');
        $data['position']              = "";
        $data['description']           = "";
        $data['comments']              = "Automatically created during front-end registration,";
        $data['profile']               = "";
        $data['featured_image']        = "";
        $data['featured_image_url']    = "";
        $data['featured_image_upload'] = "";
        $data['featured_image_server'] = "";
        $data['birthday']              = "";
        $data['anniversary']           = "";
        $data['user_id']               = $eventData->data['id'];

        // Pivot Tables
        // If there is NO record to INSERT into the pivot table, then the value is null.
        // If there is a record to INSERT into the pivot table, then use an ARRAY since multple values are acceptable,
        // and are specified in an array. For reference, the pivot table INSERTs @
        // Lasallecms\Lasallecmsapi\Repositories\Traits\persist::associateRelatedRecordsToNewRecord()
        $data['companies']            = null;


        $results = [];
        $results['saveok'] = $this->repository->createRecord($data);
        $results['people_id'] = $this->model->orderBy('id', 'desc')->first()->id;

        return $results;
    }

    /**
     * First name and surnames are combined during registration.
     * Split 'em and return either the first name or the suranme
     *
     * @param  string  $name           Name entered during the registration process
     * @param  string  $firstOrSurname Return the "firstname"; or, the "surname"
     * @return string
     */
    public function splitName($name, $firstOrSurname="surname") {

        // The first name is considered to be all the characters to the left of the final space.
        // The surname is considered to be all the characters to the right of the final space.

        // oh, just in case the name ends with a space
        $name = trim($name);

        $positionOfFinalSpace = strrpos($name, " ");

        if ($firstOrSurname == "surname") {
            return substr($name, $positionOfFinalSpace+1, strlen($name));
        }

        return substr($name, 0, $positionOfFinalSpace);
    }

}