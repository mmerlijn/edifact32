<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\INV;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class INVTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l"));
        $msg->order->addResult(new Result(type_of_value: "NV", value: 12, test_code: "TSTCODE2", test_name: "Test name2", units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("INV+1+TSTCODE:AMB:NHG:Test name", $edi32->write());
        $this->assertStringContainsString("INV+1+TSTCODE2:AMB:NHG:Test name2", $edi32->write());

    }
}
