<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Msg;

class COM extends Segment implements SegmentInterface
{

    public function getMsg(Msg $msg): Msg
    {
        if($this->params['type']=='sender') {
            $msg->sender->setPhone($this->getData(1));
        }elseif($this->params['type']=='receiver') {
            $msg->receiver->setPhone($this->getData(1));
        }elseif($this->params['type']=='patient') {
            $msg->patient->addPhone($this->getData(1));
        }
            return $msg;
    }
    public function setMsg(Msg $msg): void
    {
        if($this->params['type']=='sender') {
            $this->setData($msg->sender->phone?->number, 1)
            ->setData("TE", 1,1);
            if(!$msg->sender->phone?->number or $msg->sender->phone?->number == 'nb'){
                $this->empty = true;
            }
        }elseif($this->params['type']=='receiver') {
            $this->setData($msg->receiver->phone?->number, 1)->setData("TE", 1,1);
            if(!$msg->receiver->phone?->number or $msg->receiver->phone?->number == 'nb'){
                $this->empty = true;
            }
        }elseif($this->params['type']=='patient') {
            $this->setData($msg->patient->getPhone(), 1)->setData("TE", 1,1);
            if(!$msg->patient->getPhone() or $msg->patient->getPhone() == 'nb'){
                $this->empty = true;
            }
        }
    }
}