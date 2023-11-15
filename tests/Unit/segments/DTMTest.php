<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\DTM;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use PHPUnit\Framework\TestCase;

class DTMTest extends TestCase
{
    public function test_send_date_setter(){
        $msg = new Msg();
        $nu = \Carbon\Carbon::now();
        $msg->datetime = $nu;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("DTM+137:".$nu->format('YmdHi').":203", $edi32->write());
    }

    public function test_dob_setter(){
        $msg = new Msg();
        $msg->patient->setDob("03-10-1999");
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("DTM+329:19991003:102", $edi32->write());
    }
    public function test_monsert_setter(){
        $msg = new Msg();
        $nu = \Carbon\Carbon::now();
        $msg->order->dt_of_observation = $nu;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("DTM+SCO:".$nu->format('YmdHi').":203", $edi32->write());
    }
    public function test_dt_test_setter(){
        $msg = new Msg();
        $nu = \Carbon\Carbon::now();
        $msg->order->dt_of_observation = $nu;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("DTM+ISO:".$nu->format('YmdHi').":203", $edi32->write());
    }
}
