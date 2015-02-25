<?php

use Lasallecms\Usermanagement\Validation\ForbiddenTLD;

class RegistrationValidationTest extends PHPUnit_Framework_TestCase  {

    public function __construct()
    {
        $this->validation = new ForbiddenTLD;
    }

    /*
     * Testing that two character Top Level Domains is returned as having 3 chars (including the period)
     */
    public function testLengthOfTwoCharacterTLD()
    {
        $tld = ".ca";
        $this->assertEquals(3, $this->validation->lengthForbiddenTLD($tld));
    }

    /*
     * Testing that three character Top Level Domains is returned as having 4 chars (including the period)
     */
    public function testLengthOfThreeCharacterTLD()
    {
        $tld = ".com";
        $this->assertEquals(4, $this->validation->lengthForbiddenTLD($tld));
    }

    public function testLastCharactersOfEmail()
    {
        $email = "name@example.com";
        $length = 4;
        $this->assertEquals(".com", $this->validation->lastCharactersOfEmail($email, $length));
    }

    public function testCompareEmailWithForbiddenTLDWhenTrue()
    {
        $lastCharactersOfEmail = ".com";
        $forbiddenTLD = ".com";
        $this->assertTrue($this->validation->compareEmailWithForbiddenTLD($lastCharactersOfEmail, $forbiddenTLD));
    }

    public function testCompareEmailWithForbiddenTLDWhenFalse()
    {
        $lastCharactersOfEmail = ".ca";
        $forbiddenTLD = ".com";
        $this->assertFalse($this->validation->compareEmailWithForbiddenTLD($lastCharactersOfEmail, $forbiddenTLD));
    }

    /**
     * Test the actual validation, now that we've tested the individual elements going into that validation.
     * "True" means that the email TLD is *not* forbidden.
     */
    public function testValidateForbiddenTLDWhenTrue()
    {
        $email = "name@example.org";
        $specifiedForbiddenTLDs = array('.ca', '.com', '.gov');

        $this->assertTrue($this->validation->validateForbiddenTLD($email, $specifiedForbiddenTLDs));
    }

    /**
     * Test the actual validation, now that we've tested the individual elements going into that validation.
     * "False" means that the email TLD *is* forbidden.
     */
    public function testValidateForbiddenTLDWhenFalse()
    {
        $email = "name@example.gov";
        $specifiedForbiddenTLDs = array('.ca', '.com', '.gov');

        $this->assertFalse($this->validation->validateForbiddenTLD($email, $specifiedForbiddenTLDs));
    }


}