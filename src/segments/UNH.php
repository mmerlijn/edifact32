<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgEdifact32\segments\Segment;
use mmerlijn\msgEdifact32\segments\SegmentInterface;

use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Msg;

class UNH extends Segment implements SegmentInterface
{

    public function getMsg(Msg $msg): Msg
    {
        //message reference id
        $msg->id = $this->getData(1);

        //message type
        $msg->msgType->type = $this->getData(2);
        $msg->msgType->version = $this->getData(2, 1);

        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        $this
            //message reference nr (id)
            ->setData($msg->id, 1);
        //message type
        if ($msg->msgType->type) {
            $this->setData($msg->msgType->type, 2);
        }
        //message version
        if ($msg->msgType->version) {
            $this->setData($msg->msgType->version, 2, 1);
        }
    }

    public function validate(): void
    {
        Validator::validate([
            "reference_id" => $this->data[1][0] ?? "",
            "msg_type" => $this->data[1][1] ?? "",
            "msg_version" => $this->data[2][0] ?? "",
        ], [
            "reference_id" => 'required',
            "msg_type" => 'required',
            "msg_version" => 'required',
        ], [
            "reference_id" => '@ UNH[1][0] set $msg->id',
            "msg_type" => '@ UNH[2][0] set $msg->msgType->type',
            "msg_version" => '@ UNH[2][1] set $msg->msgType->version',
        ]);
    }
}