<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\ADR;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use PHPUnit\Framework\TestCase;

class ADRTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->patient->setName(new Name(initials: "V", own_lastname: "Achternaam", own_prefix: "van"));
        $msg->patient->setAddress(new Address(postcode: "1234AB", city: "Stad", street: "Straatnaam", building: "1a"));
        $edi32->setMsg($msg);
        $this->assertStringContainsString("ADR++1:Straatnaam:1 a+Stad+1234AB+NL", $edi32->write());
    }
}
