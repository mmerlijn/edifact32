<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgEdifact32\segments\Segment;
use mmerlijn\msgEdifact32\segments\SegmentInterface;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Observation;
use mmerlijn\msgRepo\Result;

class RSL extends Segment implements SegmentInterface
{

    public function setResult(Observation $observation): self
    {
        /*
 AV Alphanumerical value
 CV Coded value
 NR Numerical value range
 NV Numerical value
 TV Text value

 HI Above high reference limit
 LO Below low reference limit
 UN Abnormal
 */

        if($observation->reference_range and $observation->abnormal_flag==ResultFlagEnum::EMPTY){
            $parts = explode(" ", $observation->reference_range);
            $lo = (int)str_replace(",", ".", $parts[0]);
            $hi = (int)str_replace(",", ".", $parts[1]);
            if($observation->value < $lo) {
                $observation->abnormal_flag = ResultFlagEnum::LOW;
            }elseif($observation->value > $hi){
                $observation->abnormal_flag = ResultFlagEnum::HIGH;
            }
        }
        $this->setData($observation->type->toEdifact(), 1)
            ->setData($observation->value, 2)
            ->setData($observation->reference_range, 3)
            ->setData($observation->units, 4)
            ->setData(match($observation->abnormal_flag){
                ResultFlagEnum::HIGH => "HI",
                ResultFlagEnum::LOW => "LO",
                default => ""
            }, 5);
        return $this;
    }
    public function validate(): void
    {
        Validator::validate([
            "type_of_value" => $this->data[1],
            "normind" => $this->data[5],
        ], [
            "type_of_value" => 'required|in:AV,CV,NR,NV,TV',
            "normind" => 'in:HI,LO,UN,',

        ], [
            "type_of_value" => '@ RSL[1] set $result->type_of_value',
            "normind" => '@ RSL[5] set $result->abnormal_flag',
        ]);
    }
}