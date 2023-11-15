<?php

namespace mmerlijn\msgEdifact32\segments;

use Carbon\Carbon;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Phone;

class DTM extends Segment implements SegmentInterface
{
    /*
010    2005  Date or time or period function code
     qualifier                                 M      an..3 =>137=bericht aangemaakt, 187=oorspronkelijke datum document, 329=gbdatum, ISR=datum aanvraag
020    2380  Date or time or period text               C      an..35
030    2379  Date or time or period format code        C      an..3 =>102=CCYYMMDD, 201=YYMMDDHHMM, 203=CCYYMMDDHHMM
*/
    public function getMsg(Msg $msg): Msg
    {
        if($this->getData(1)=="137"){
            $msg->datetime = Carbon::createFromFormat("ymdHi",
                $this->getData(1,2)
            );
        }elseif($this->getData(1)=="329") {
            $msg->patient->dob = Carbon::createFromFormat("ymd",
                $this->getData(1, 2)
            );
        }elseif($this->getData(1)=="SCO") {
            $msg->order->dt_of_observation = Carbon::createFromFormat("ymdHi",
                $this->getData(1, 2)
            );
        }elseif($this->getData(1)=="ISO"){
            $msg->order->dt_of_observation = Carbon::createFromFormat("ymdHi",
                $this->getData(1, 2)
            );
        }elseif($this->getData(1)=="ISR"){
            $msg->order->dt_of_observation = Carbon::createFromFormat("ymdHi",
                $this->getData(1, 2)
            );
        }
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        if($this->getData(1)=="137"){ //verzenddatum
            $this->setData($msg->datetime?->format("YmdHi"), 1,1)
            ->setData('203', 1,2);
        }elseif($this->getData(1)=="329") { //geb datum
            $this->setData($msg->patient->dob?->format("Ymd"), 1, 1)
                ->setData('102', 1, 2);
        }elseif($this->getData(1)=="SCO") { //monster
            $this->setData($msg->order->dt_of_observation?->format("YmdHi"), 1, 1)
                ->setData('203', 1, 2);
        }elseif($this->getData(1)=="ISO"){ //onderzoeksdatum
            $this->setData($msg->order->dt_of_observation?->format("YmdHi"), 1, 1)
                ->setData('203', 1, 2);
        }elseif($this->getData(1)=="ISR"){ //labnr datum
            $this->setData($msg->order->dt_of_observation?->format("YmdHi"), 1, 1)
                ->setData('203', 1, 2);

        }
    }

    public function validate(): void
    {
   //     Validator::validate([
   //         "observation_datetime" => ($this->data[1][0] ?? "") . ($this->data[1][1] ?? "") . ($this->data[1][2] ?? ""),
   //     ], [
   //         "observation_datetime" => 'required|length:6',
   //     ], [
   //         "observation_datetime" => '@ DET[1][0] / DET[1][1] / DET[1][2] set/adjust $msg->order->dt_of_observation',
   //     ]);
    }
}