<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\BGM;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class BGMTest extends TestCase
{

    public function test_labnr_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->order->lab_nr= "7654321";
        $edi32->setMsg($msg);
        $this->assertStringContainsString("BGM+LRP:MF:ITN+7654321+9+NA", $edi32->write());
    }
}
