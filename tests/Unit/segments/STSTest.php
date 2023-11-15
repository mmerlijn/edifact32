<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\STS;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class STSTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32->setMsg($msg);
        $this->assertStringContainsString("STS++G", $edi32->write());
    }
}
