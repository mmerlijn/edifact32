<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\RFF;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class RFFTest extends TestCase
{
    //hoeft niet in bericht
    //public function test_bsn_setter(){
    //    $msg = new Msg();
    //    $edi32 = new Edifact32();
    //    $msg->patient->bsn = "123456782";
    //    $edi32->setMsg($msg);
    //    $this->assertStringContainsString("RFF+LZB:123456782", $edi32->write());
    //}
    public function test_labnr_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->order->lab_nr = "123456";
        $edi32->setMsg($msg);
        $this->assertStringContainsString("RFF+SRI:123456", $edi32->write());
        $this->assertStringContainsString("RFF+ROI:123456", $edi32->write());
    }
}
