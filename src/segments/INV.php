<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Observation;
use mmerlijn\msgRepo\Phone;
use mmerlijn\msgRepo\Result;
use mmerlijn\msgRepo\TestCode;

class INV extends Segment implements SegmentInterface
{

    public function getMsg(Msg $msg): Msg
    {
        //$msg->order->addResult(new Result(
        //    test_code: $this->getData(2),
        //    test_name: $this->getData(2,3),
        //));
        $msg->order->addObservation(new Observation(
            test: new TestCode(
                code: $this->getData(2),
                value: $this->getData(2,3),
            )
        ));
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {


    }
    public function setResult(Observation $observation): self
    {
        $this->setData($observation->test->code, 2)
            ->setData($observation->test->value, 2, 3);
        return $this;
    }
}