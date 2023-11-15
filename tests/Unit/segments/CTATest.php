<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\CTA;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class CTATest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32->setMsg($msg);
        $this->assertStringContainsString("CTA+AFD+:Biometrie", $edi32->write());
    }
}
