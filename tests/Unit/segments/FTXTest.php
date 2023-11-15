<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\FTX;
use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Result;
use PHPUnit\Framework\TestCase;

class FTXTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $msg->order->addResult(new Result(type_of_value: "NV", value: 3, test_code: "TSTCODE", test_name: "Test name", units: "mmol/l"));
        $msg->order->addResult((new Result(type_of_value: "NV", value: 12, test_code: "TSTCODE2", test_name: "Test name2", units: "mmol/l", reference_range: "0.0 10.0"))->addComment("Dit is een commentaar, de tekst moet extra lang zijn zodat het over meerdere regels moet worden verdeeld."));
        $msg->order->order_status = OrderStatusEnum::FINAL;
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("FTX+UIT+++Dit is een commentaar, de tekst moet extra lang zijn zodat het over:meerdere regels moet worden verdeeld.", $edi32->write());

    }
}
