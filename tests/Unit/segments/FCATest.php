<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\FCA;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Insurance;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use PHPUnit\Framework\TestCase;

class FCATest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->patient->setInsurance(new Insurance(uzovi: "1234", policy_nr: "01234567"));
        $edi32->setMsg($msg);
        $this->assertStringContainsString("FCA+PU+1234:ZZ:VEK:01234567", $edi32->write());
    }
}
