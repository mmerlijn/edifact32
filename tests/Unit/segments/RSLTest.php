<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\RSL;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class RSLTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->addResult(new Result(type_of_value: "NV", value: 12, test_code: "TSTCODE2", test_name: "Test name2", units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE3", test_name: "Test name3", units: "mmol/l", reference_range: "4,0 10,0"));
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
        $msg->order->addResult(new Result(type_of_value: "ST", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);

        $edi32->segments[$edi32->findSegmentKey("RSL")]->validate();
        $this->assertTrue(Validator::fails());
        $this->assertContains('type_of_value in:AV,CV,NR,NV,TV failure @ RSL[1] set $result->type_of_value', Validator::getErrors());

    }
}
