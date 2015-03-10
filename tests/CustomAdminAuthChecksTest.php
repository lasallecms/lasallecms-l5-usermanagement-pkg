<?php

//use Lasallecms\Usermanagement\Models\User;

use Lasallecms\Usermanagement\Http\Middleware\Admin\CustomAdminAuthChecks;



class CustomAdminAuthChecksTest extends PHPUnit_Framework_TestCase  {

    public function setUp() {

        parent::setUp(); // Don't forget this!

        $this->model = $this->getMock('Lasallecms\Usermanagement\Models\User');

        $this->customChecks = new CustomAdminAuthChecks();
    }


    /*
     * Test allowed IP Addresses check
     */
    public function testIPAddressCheckTrue()
    {
        $allowedIPAddresses = ['127.0.0.0', '127.0.0.1', '99.999.999.999'];
        $requestIPAddress = '99.999.999.999';
        $this->assertTrue($this->customChecks->ipAddressCheck($allowedIPAddresses, $requestIPAddress));
    }

    public function testIPAddressCheckFalse()
    {
        $allowedIPAddresses = ['127.0.0.0', '127.0.0.1', '99.999.999.999'];
        $requestIPAddress = '127.0.0.2';
        $this->assertFalse($this->customChecks->ipAddressCheck($allowedIPAddresses, $requestIPAddress));
    }


    // Allowed users
    public function testAllowedUsersCheckTrue()
    {
        $allowedUsers = ['info@southlasalle.com', 'info@lasallecms.com', 'info@lasallemart.com'];
        $requestEmail = 'info@lasallemart.com';
        $this->assertTrue( $this->customChecks->allowedUsersCheck( $allowedUsers, $requestEmail) );
    }

    public function testAllowedUsersCheckFalse()
    {
        $allowedUsers = ['info@southlasalle.com', 'info@lasallecms.com', 'info@lasallemart.com'];
        $requestEmail = 'info@lasallecast.com';
        $this->assertFalse( $this->customChecks->allowedUsersCheck( $allowedUsers, $requestEmail) );
    }


    // Allowed user groups
    public function testAllowedUserGroupsCheckTrue()
    {
        $allowedUserGroups = ['Super Administrator'];
        $requestUserGroup  = 'Super Administrator';
        $this->assertTrue( $this->customChecks->allowedUserGroupCheck($allowedUserGroups, $requestUserGroup) );
    }

    public function testAllowedUserGroupsCheckFalse()
    {
        $allowedUserGroups = ['Administrator', 'Super Administrator', 'Super Duper Administrator'];
        $requestUserGroup  = 'Registered';
        $this->assertFalse( $this->customChecks->allowedUserGroupCheck($allowedUserGroups, $requestUserGroup) );
    }


}