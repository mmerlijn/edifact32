<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Msg;

class UNB extends Segment implements SegmentInterface
{

    public function getMsg(Msg $msg): Msg
    {
        //Agbcode sender
        $msg->sender->agbcode = $this->getData(2);

        //agbcode receiver
        $msg->receiver->agbcode = $this->getData(3);
        $msg->order->requester->agbcode = $this->getData(3);

        //datetime of message
        $msg->datetime = Carbon::createFromFormat("ymdHi",
            $this->getData(4) . $this->getData(4, 1)
        );
        //msg reference
        $msg->processing_id = $this->getData(5);
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {

        $this
            //agbcode sender
            ->setData($msg->sender->agbcode, 2)

            //agbcode reveiver
            ->setData($msg->receiver->agbcode ?: $msg->order->requester->agbcode, 3)

            //datetime of message
            ->setData($msg->datetime->format("ymd"), 4)
            ->setData($msg->datetime->format("Hi"), 4, 1)

            //msg reference
            ->setData($msg->processing_id, 5);

    }

    public function validate(): void
    {
        Validator::validate([
            "synthax_identifier" => $this->data[1][0] ?? "",
            "synthax_version" => $this->data[1][1] ?? "",
            "sender_agbcode" => $this->data[2][0] ?? "",
            "receiver_agbcode" => $this->data[3][0] ?? "",
            "msg_date" => $this->data[4][0] ?? "",
            "msg_time" => $this->data[4][1] ?? "",
            "processing_id" => $this->data[5][0] ?? "",
        ], [
            "synthax_identifier" => "required",
            "synthax_version" => 'required',
            "sender_agbcode" => "required",
            "receiver_agbcode" => "required",
            "msg_date" => "required",
            "msg_time" => "required",
            "processing_id" => "required",
        ], [
            "synthax_identifier" => '@ UNB[1][0]',
            "synthax_version" => '@ UNB[1][1]',
            "sender_agbcode" => '@ UNB[2][0] set $msg->sender->agbcode',
            "receiver_agbcode" => '@ UNB[3][0] set $msg->receiver->agbcode',
            "msg_date" => '@ UNB[4][0] set $msg->datetime',
            "msg_time" => '@ UNB[4][1] set $msg->datetime',
            "processing_id" => '@ UNB[5][0] set $msg->processing_id',
        ]);
    }
}