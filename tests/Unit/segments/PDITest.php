<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\PDI;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;
use PHPUnit\Framework\TestCase;

class PDITest extends TestCase
{
    public function test_man_setter(){
        $msg = new Msg();
        $msg->patient->bsn = "123456782";
        $msg->patient->setName(new Name(initials: "J", lastname: "Jansen", prefix: "van", own_lastname: "Groot", own_prefix: "de"));
        $msg->patient->setSex("M");
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        //var_dump($edi32->segments[26]->data);
        //exit();
        $this->assertStringContainsString("PDI+1", $edi32->write());
    }
    public function test_female_setter(){
        $msg = new Msg();
        $msg->patient->bsn = "123456782";
        $msg->patient->setName(new Name(initials: "J", lastname: "Jansen", prefix: "van", own_lastname: "Groot", own_prefix: "de"));
        $msg->patient->setSex("F");
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        //var_dump($edi32->segments[26]->data);
        //exit();
        $this->assertStringContainsString("PDI+2", $edi32->write());
    }
}
