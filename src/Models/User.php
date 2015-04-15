<?php namespace Lasallecms\Usermanagement\Models;

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

// Please note: this file is modified from the original that comes with the L5 app

use Lasallecms\Usermanagement\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


    /**
     * Sanitation rules for Create (INSERT)
     *
     * @var array
     */
    public $sanitationRulesForCreate = [
        'name'       => 'trim|strip_tags',
    ];

    /**
     * Sanitation rules for UPDATE
     *
     * @var array
     */
    public $sanitationRulesForUpdate = [
        'name'       => 'trim|strip_tags',
    ];


    /**
     * Validation rules for  Create (INSERT)
     *
     * @var array
     */
    public $validationRulesForCreate = [
        'name'       => 'required',
        'email'      => 'required|email|unique:users',
        'password'   => 'required|confirmed|min:6',
        'confirmed'  => 'in:1,0'
    ];

    /**
     * Validation rules for user when the user is being updated but the password is left intact
     *
     * @var array
     */
    public $validationRulesForUpdateNoPassword = [
        'name'       => 'required',
        'email'      => 'required|email'
    ];

    /**
     * Validation rules for user when the password is created for the first time; or, is being updated
     *
     * @var array
     */
    public $validationRulesForUpdateWithPassword = [
        'name'       => 'required',
        'email'      => 'required|email',
        'password'   => 'required|confirmed|min:6',
    ];


    /*
     * Many to many relationship with groups
     *
     * @return Eloquent
     */
    public function group()
    {
        return $this->belongsToMany('Lasallecms\Usermanagement\Models\Group', 'user_group');
    }

}
