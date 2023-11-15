<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Msg;

class BGM extends Segment implements SegmentInterface
{
    /*
010    C002 DOCUMENT/MESSAGE NAME                      C    1
   1001  Document name code                        C      an..3  =>LRP
   1131  Code list identification code             C      an..17 =>MF
   3055  Code list responsible agency code         C      an..3  =>ITN
   1000  Document name                             C      an..35

020    C106 DOCUMENT/MESSAGE IDENTIFICATION            C    1
   1004  Document identifier                       C      an..70 =>berichtrefnr
   1056  Version identifier                        C      an..9
   1060  Revision identifier                       C      an..6

030    1225 MESSAGE FUNCTION CODE                      C    1 an..3  (2 addition, 7 duplicate, 9 original)

040    4343 RESPONSE TYPE CODE                         C    1 an..3 NA (No acknowledgement needed)
    */
    public function getMsg(Msg $msg): Msg
    {
        //Agbcode sender
        $msg->order->lab_nr = $this->getData(2);

        return $msg;
    }

    public function setMsg(Msg $msg): void
    {

        $this
            //agbcode sender
            ->setData($msg->order->lab_nr, 2);

    }

    public function validate(): void
    {
        Validator::validate([
            "labnr" => $this->data[2][0] ?? "",
        ], [
            "labnr" => "required",
        ], [
            "labnr" => '@ BGM[2][0]',
        ]);
    }
}