<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\RSL;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Enums\ValueTypeEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Observation;
use mmerlijn\msgRepo\Result;
use mmerlijn\msgRepo\TestCode;
use PHPUnit\Framework\TestCase;

class RSLTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::NM, value: 3, test: new TestCode(code: "TSTCODE", value: "Test name"), units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::NM, value: 12, test: new TestCode(code: "TSTCODE2", value: "Test name2"), units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::NM, value: 3, test: new TestCode(code: "TSTCODE3", value: "Test name3"), units: "mmol/l", reference_range: "4,0 10,0"));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("RSL+NV+3+0.0 10.0+mmol/l", $edi32->write());
        $this->assertStringContainsString("RSL+NV+12+0.0 10.0+mmol/l+HI", $edi32->write());
        $this->assertStringContainsString("RSL+NV+3+4,0 10,0+mmol/l+LO", $edi32->write());
    }
    public function test_validator()
    {
        $msg = new Msg();
        $msg->order->addObservation(new Observation(type: ValueTypeEnum::ST, value: 3, test: new TestCode(code: "TSTCODE", value: "Test name"), units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);

        $edi32->segments[$edi32->findSegmentKey("RSL")]->validate();
        $this->assertTrue(Validator::fails());
        $this->assertContains('type_of_value in:AV,CV,NR,NV,TV failure @ RSL[1] set $result->type_of_value', Validator::getErrors());

    }
}
