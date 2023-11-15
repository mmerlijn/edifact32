<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\S02;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class S02Test extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("S02+1+N", $edi32->write());
    }
}
