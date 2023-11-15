<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Phone;
use mmerlijn\msgRepo\Result;

class INV extends Segment implements SegmentInterface
{

    public function getMsg(Msg $msg): Msg
    {
        $msg->order->addResult(new Result(
            test_code: $this->getData(2),
            test_name: $this->getData(2,3),
        ));
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {


    }
    public function setResult(Result $result): self
    {
        $this->setData($result->test_code, 2)
            ->setData($result->test_name, 2, 3);
        return $this;
    }
}