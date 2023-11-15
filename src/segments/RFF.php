<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Msg;

class RFF extends Segment implements SegmentInterface
{
    public function getMsg(Msg $msg): Msg
    {
        if($this->getData(1)=="SRI" or $this->getData(1)=="ROI") //labnr
        {
            $msg->order->lab_nr = $this->getData(1,1);
        }elseif($this->getData(1)=="LZB") //bsn
        {
            $msg->patient->bsn = $this->getData(1, 1);
        }
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        if($this->getData(1)=="SRI" or $this->getData(1)=="ROI") //labnr
        {
            $this->setData($msg->order->lab_nr, 1,1);
        }elseif($this->getData(1)=="LZB") //bsn
        {
            $this->setData($msg->patient->bsn, 1, 1);
        }
    }
}