<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\RND;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class RNDTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l", reference_range: "0.0 10.0", abnormal_flag: ResultFlagEnum::EMPTY));
        $msg->order->addResult(new Result(type_of_value: "NV", value: 12, test_code: "TSTCODE2", test_name: "Test name2", units: "mmol/l", reference_range: "0.0 10.0"));
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE3", test_name: "Test name3", units: "mmol/l", reference_range: "4,0 10,0"));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("RND+RU+0.0+10.0", $edi32->write());
        $this->assertStringContainsString("RND+RU+4,0+10,0", $edi32->write());
    }
}
