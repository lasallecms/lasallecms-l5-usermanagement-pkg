<?php namespace Lasallecms\Usermanagement\Validation;

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

// Laravel facades
use Config;


/**
 * Class ForbiddenTLD
 *
 * New users cannot register if they use an email address with a forbidden Top Level Domain.
 * Forbidden TLD's are specified in this package's config file.
 * This validation exists to thwart bots.
 *
 * @package Lasallecms\Usermanagement\Validation
 */
class ForbiddenTLD
{
    /**
     * Validate Top Level Domains
     *
     * @param  string $email
     * @param  array  $specifiedTLDs  So unit test can specify its own array of forbidden TLDs
     * @return bool
     */
    public function validateForbiddenTLD($email, $specifiedTLDs = array()) {

        $forbiddenTLDs = $this->getTheForbiddenTLDs($specifiedTLDs);

        // if no TLD's specified in the parameters; and, no TLD's specified in the config,
        // then deem the validation as kosher
        if (empty($forbiddenTLDs)) return true;

        // initialize var
        $validation = true;

        foreach ($forbiddenTLDs as $forbiddenTLD)
        {
            $lengthOfForbiddenTLD = $this->lengthForbiddenTLD($forbiddenTLD);
            $lastCharactersOfEmail = $this->lastCharactersOfEmail($email, $lengthOfForbiddenTLD);

            // validate
            if ($this->compareEmailWithForbiddenTLD($lastCharactersOfEmail, $forbiddenTLD) )
            {
                $validation = false;
            }
        }

        return $validation;
    }

    /**
     * Get the forbidden TLD's
     *
     * @param  array $specifiedTLDs
     * @return array
     */
    public function getTheForbiddenTLDs($specifiedTLDs = array()) {
        // so the unit test can specify its own list of forbidden TLDs
        if (!empty($specifiedTLDs)) return $specifiedTLDs;

        return Config::get('lasallecmsusermanagement.forbiddenTLDs');

    }

    /**
     * Length of a forbidden Top Level Domain
     *
     * Forbidden TLD must include the period!
     *
     * @param  int    $ForbiddenTLD
     * @return bool
     */
    public function lengthForbiddenTLD($ForbiddenTLD) {
        return strlen($ForbiddenTLD);
    }

    /**
     * Substring of email corresponding to the last few characters of the address
     *
     * @param  string  $email
     * @param  int     $length
     * @return string
     */
    public function lastCharactersOfEmail($email, $length) {
        return substr($email, -$length, $length);
    }

    /**
     * Compare an email's TLD with a Forbidden TLD
     *
     * @param  string $lastCharactersOfEmail
     * @param  string $forbiddenTLD
     * @return bool
     */
    public function compareEmailWithForbiddenTLD($lastCharactersOfEmail, $forbiddenTLD) {

        if ($lastCharactersOfEmail == $forbiddenTLD)
        {
            return true;
        }

        return false;
    }
}
