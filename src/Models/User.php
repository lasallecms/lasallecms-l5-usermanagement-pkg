<?php
namespace Lasallecms\Usermanagement\Models;

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



///////////////////////////////////////////////////////////////////
//// USER MANAGEMENT AND AUTHENTICATION IS SO BESPOKE THAT     ////
////      IT IS NOT PART OF LASALLE's FORM AUTOMATION          ////
///////////////////////////////////////////////////////////////////



// Please note: this file is modified from the original that comes with the L5.0 app

// LaSalle Software
use Lasallecms\Lasallecmsapi\Models\BaseModel;

// Laravel classes
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword;


    // LARAVEL MODEL CLASS PROPERTIES

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


    // SANITATION RULES PROPERTIES

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


    // VALIDATION RULES PROPERTIES

    /**
     * Validation rules for  Create (INSERT)
     *
     * @var array
     */
    public $validationRulesForCreate = [
        'name'       => 'required|max:255',
        'email'      => 'required|email|max:255|unique:users',
        'password'   => 'required|confirmed|min:6',
        'confirmed'  => 'in:1,0'
    ];

    /**
     * Validation rules for user when the user is being updated but the password is left intact
     *
     * @var array
     */
    public $validationRulesForUpdateNoPassword = [
        'name'       => 'required|max:255',
        'email'      => 'required|email|max:255'
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


    // USER GROUPS & ROLES PROPERTIES

    /*
     * User groups that are allowed to execute each controller action
     *
     * @var array
     */
    public $allowed_user_groups = [
        ['index'   => ['Super Administrator']],
        ['create'  => ['Super Administrator']],
        ['store'   => ['Super Administrator']],
        ['edit'    => ['Super Administrator']],
        ['update'  => ['Super Administrator']],
        ['destroy' => ['Super Administrator']],
    ];



    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

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
