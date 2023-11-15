<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Enums\PatientSexEnum;
use mmerlijn\msgRepo\Msg;

class PDI extends Segment implements SegmentInterface
{
    public function getMsg(Msg $msg): Msg
    {
        $msg->patient->setSex(match ($this->getData(1)) {
            "1" => PatientSexEnum::MALE,
            "2" => PatientSexEnum::FEMALE,
            "9" => PatientSexEnum::OTHER,
            default => PatientSexEnum::EMPTY}
            );
         return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        $this->setData(match ($msg->patient->sex) {
            PatientSexEnum::MALE => 1,
            PatientSexEnum::FEMALE => 2,
            PatientSexEnum::OTHER, PatientSexEnum::EMPTY => 9,
        }, 1);
    }
}