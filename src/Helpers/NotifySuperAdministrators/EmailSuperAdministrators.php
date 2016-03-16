<?php

namespace Lasallecms\Usermanagement\Helpers\NotifySuperAdministrators;

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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class EmailSuperAdministrators
 * @package Lasallecms\Usermanagement\Helpers\TwoFactorAuthorization
 */
class EmailSuperAdministrators
{

    /**
     * Send email notifications to the super admins
     *
     * @param  text   $subject         Email subject
     * @param  object $event           Data object from event DTO
     * @param  text   $emailBladeFile  Full package::path..blade.file
     * @return void
     */
    public function sendEmailToSuperAdmins($subject, $event, $emailBladeFile) {

        // Grab the super administrators who need to be notified from the config settting
        $superAdminEmails = config('auth_frontend_registration_successful_admins_who_receive_notification_email');

        // If there are no super administrators who are specified in the config setting, use the first-among-equals admin
        if (count($superAdminEmails) == 0) {
            $superAdminEmails[] = config('lasallecmsusermanagement.administrator_first_among_equals_email');
        }

        // create data array from the event DTO
        $data = $event->data;

        // Set the data needed to send the email
        $data['subject']   = $subject;
        $data['site_name'] = config('lasallecmsfrontend.site_name');

        // data used in the body of the email, in the blade file
        $data['data']['site_name'] = config('lasallecmsfrontend.site_name');
        $data['data']['id']        = $event->data['id'];

        // Send the notification email to each specified super administrator
        foreach ($superAdminEmails as $superAdminEmail) {

            // ensure that this email is a super admins
            if (!$this->isSuperAdministrator($superAdminEmail)) {
                continue;
            }

            // set the "to" email field
            $data['to'] = $superAdminEmail;

            Mail::queue($emailBladeFile, $data, function ($message) use ($data) {
                $message->subject($data['subject'])
                        ->to($data['to'])
            ;

                //echo "<hr>".$message->getBody();

            });
        }
    }


    /**
     * Does this email belong to a super administrator?
     *
     * @param  text  $email
     * @return bool
     */
    public function isSuperAdministrator($email) {

        // get the user id
        $userId = $this->findUserIdByEmail($email);

        // if there is no user ID for that email, then zero is returned
        if ($userId == 0) {
            return;
        }


        // does this user id belong to the usergroup "super administrator"
        if ($this->isUserSuperAdministrator($userId)) {
            return true;
        }

        return false;
    }


    // I know I am supposed to inject the UserRepository class from the LaSalleCMSAPI; but, what
    // I *really* want to do is put all that is needed for this job in this one single file.


    /**
     * Find the user's ID from their email address.
     *
     * Return 0 if no ID is found.
     *
     * @param  string  $email
     * @return int
     */
    public function findUserIdByEmail($email) {
        $userId = DB::table('users')
            ->where('email', $email)
            ->value('id')
        ;

        if (!$userId) {
            return 0;
        }

        return $userId;
    }

    /**
     * Does the user belong to the Super Administrator user group?
     *
     * It is assumed that Super Administrator is ID=3 in the groups database table.
     *
     * @param  int $userId
     * @return bool
     */
    public function isUserSuperAdministrator($userId) {
        $result = DB::table('user_group')
            ->where('user_id', $userId)
            ->where('group_id', 3)
            ->first()
        ;

        if (!$result) {
            return false;
        }

        return true;
    }
}