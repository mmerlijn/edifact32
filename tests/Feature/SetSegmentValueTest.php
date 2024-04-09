<?php

namespace mmerlijn\msgEdifact32\tests\Feature;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use setSegementTest;
use PHPUnit\Framework\TestCase;

class SetSegmentValueTest extends TestCase
{

    public function test_setter()
    {
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->patient->setName(new Name(initials: "V", own_lastname: "Achternaam", own_prefix: "van"));
        $msg->patient->setAddress(new Address(postcode: "1234AB", city: "Stad", street: "Straatnaam", building: "1a"));
        $edi32->setMsg($msg);
        $edi32->setSegmentValue('UNB', 0, '50009046', 2);
        $this->assertStringContainsString("UNB+UNOA:1+50009046", $edi32->write());
        $this->assertStringContainsString("ADR++1:Straatnaam:1 a+Stad+1234AB+NL", $edi32->write());
    }
}
