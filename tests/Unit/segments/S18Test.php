<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\S18;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class S18Test extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addResult(new Result(type_of_value: "ST", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->addResult(new Result(type_of_value: "ST", value: 3, test_code: "TSTCODE2", test_name: "Test name2", units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("S18+1+G", $edi32->write());
        $this->assertStringContainsString("S18+2+G", $edi32->write());
    }
}
