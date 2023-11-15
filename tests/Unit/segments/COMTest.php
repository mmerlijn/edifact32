<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\COM;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class COMTest extends TestCase
{
    public function test_to_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->receiver->setPhone("0612345678");
        $msg->patient->addPhone("0611223344");
        $msg->sender->setPhone("0687654321");
        $edi32->setMsg($msg);
        //$this->assertStringContainsString("COM+0612345678:TE", $edi32->write()); //not set in message
        $this->assertStringContainsString("COM+0611223344:TE", $edi32->write());
        $this->assertStringContainsString("COM+0687654321:TE", $edi32->write());
    }
}
