<?php

namespace mmerlijn\msgEdifact32\tests;

use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Amsterdam');
    }

}