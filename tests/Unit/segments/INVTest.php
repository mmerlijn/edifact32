<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\INV;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Enums\ValueTypeEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Observation;
use mmerlijn\msgRepo\Result;
use mmerlijn\msgRepo\TestCode;
use PHPUnit\Framework\TestCase;

class INVTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 3, test: new TestCode(code: "TSTCODE", value: "Test name"), units: "mmol/l"));
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 12, test: new TestCode(code: "TSTCODE2", value: "Test name2"), units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        dd($msg->order->requests);
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("INV+1+TSTCODE:AMB:NHG:Test name", $edi32->write());
        $this->assertStringContainsString("INV+1+TSTCODE2:AMB:NHG:Test name2", $edi32->write());

    }
}
