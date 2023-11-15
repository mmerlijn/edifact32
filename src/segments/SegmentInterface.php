<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Msg;

interface SegmentInterface
{
    public function read(string $line): self;

    //public function write(array $data):string;

    public function getMsg(Msg $msg): Msg;

    public function setMsg(Msg $msg): void;

    public function validate(): void;
}