<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\RND;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Enums\ValueTypeEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Observation;
use mmerlijn\msgRepo\Result;
use mmerlijn\msgRepo\TestCode;
use PHPUnit\Framework\TestCase;

class RNDTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 3, test: new TestCode(code: "TSTCODE", value: "Test name"), units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 12, test: new TestCode(code: "TSTCODE2", value: "Test name2"), units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 3, test: new TestCode(code: "TSTCODE3", value: "Test name3"), units: "mmol/l", reference_range: "4,0 10,0"));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("RND+RU+0.0+10.0", $edi32->write());
        $this->assertStringContainsString("RND+RU+4,0+10,0", $edi32->write());
    }
}
