<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Msg;

class FCA extends Segment implements SegmentInterface
{
    /*
010    4471 SETTLEMENT MEANS CODE                      M    1 an..3 => 10=particulier

020    C878 CHARGE/ALLOWANCE ACCOUNT                   C    1
   3434  Institution branch identifier             M      an..17
   1131  Code list identification code             C      an..17 => CI=verzekeringsmaatschappij
   3055  Code list responsible agency code         C      an..3  => VEK=vektis
   3194  Account holder identifier                 C      an..35
   6345  Currency identification code              C      an..3
*/
    public function getMsg(Msg $msg): Msg
    {
        $msg->patient->insurance->uzovi = $this->getData(2);
        $msg->patient->insurance->policy_nr = $this->getData(2, 3);
        return $msg;
    }
    public function setMsg(Msg $msg): void
    {
        $this->setData($msg->patient->insurance?->uzovi, 2)
            ->setData($msg->patient->insurance?->policy_nr, 2, 3);
    }

}