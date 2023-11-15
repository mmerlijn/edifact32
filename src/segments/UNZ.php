<?php

namespace mmerlijn\msgEdifact32\segments;


use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Msg;

class UNZ extends Segment implements SegmentInterface
{
    public function getMsg(Msg $msg): Msg
    {
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        $this
            //always just one message
            ->setData("1", 1)
            //msg reference
            ->setData($msg->processing_id, 2);
    }

    public function validate(): void
    {
        Validator::validate([
            "processing_id" => $this->data[2][0] ?? "",
        ], [
            "processing_id" => 'required',
        ], [
            "processing_id" => '@ UNZ[2][0] set $msg->processing_id',
        ]);
    }
}