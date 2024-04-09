<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\NAD;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use PHPUnit\Framework\TestCase;

class NADTest extends TestCase
{
    public function test_from_setter()
    {
        $msg = new Msg();
        $msg->receiver->agbcode = "01234567";
        $msg->sender->agbcode = "07654321";
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("NAD+SLA+07654321:CLB:VEK++KS :SALT:::01234567", $edi32->write());
    }
    public function test_to_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->receiver->agbcode = "01234567";
        $msg->receiver->setName(new Name(initials: "V", own_lastname: "Achternaam", own_prefix: "van"));
        $msg->receiver->setAddress(new Address(postcode: "1234AB", city: "Stad", street: "Straatnaam", building: "1a"));
        $edi32->setMsg($msg);
        $this->assertStringContainsString("NAD+PO+01234567:CGP:VEK++Achternaam:V:van+Straatnaam+1:a+Stad+1234AB", $edi32->write());
    }

    public function test_copy_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->order->copy_to->agbcode = "01234567";
        $msg->order->copy_to->setName(new Name(initials: "V", own_lastname: "Achternaam", own_prefix: "van"));
        $msg->order->copy_to->setAddress(new Address(postcode: "1234AB", city: "Stad", street: "Straatnaam", building: "1a"));
        $edi32->setMsg($msg);
        $this->assertStringContainsString("NAD+CCR+01234567:CGP:VEK++Achternaam:V:van+Straatnaam+1:a+Stad+1234AB", $edi32->write());
        $this->assertStringContainsString("S01+3", $edi32->write());
    }
}
