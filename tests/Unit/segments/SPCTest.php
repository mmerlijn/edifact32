<?php

namespace mmerlijn\msgEdifact32\tests\Unit\segments;

use mmerlijn\msgEdifact32\Edifact32;
use mmerlijn\msgEdifact32\segments\SPC;
use mmerlijn\msgRepo\Msg;
use PHPUnit\Framework\TestCase;

class SPCTest extends TestCase
{
    public function test_setter(){
        $msg = new Msg();
        $edi32 = new Edifact32();
        $edi32->setMsg($msg);
        $this->assertStringContainsString("SPC+TSP", $edi32->write());
    }
}
