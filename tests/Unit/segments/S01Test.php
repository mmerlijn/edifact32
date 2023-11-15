<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\S01;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class S01Test extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("S01+1", $edi32->write());
        $this->assertStringContainsString("S01+2", $edi32->write());
    }
}
