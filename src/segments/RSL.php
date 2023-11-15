<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgEdifact32\segments\Segment;
use mmerlijn\msgEdifact32\segments\SegmentInterface;
use mmerlijn\msgEdifact32\validation\Validator;
use mmerlijn\msgRepo\Enums\ResultFlagEnum;
use mmerlijn\msgRepo\Result;

class RSL extends Segment implements SegmentInterface
{

    public function setResult(Result $result): self
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

        if($result->reference_range and $result->abnormal_flag==ResultFlagEnum::EMPTY){
            $parts = explode(" ", $result->reference_range);
            $lo = (int)str_replace(",", ".", $parts[0]);
            $hi = (int)str_replace(",", ".", $parts[1]);
            if($result->value < $lo) {
                $result->abnormal_flag = ResultFlagEnum::LOW;
            }elseif($result->value > $hi){
                $result->abnormal_flag = ResultFlagEnum::HIGH;
            }
        }

        $this->setData($result->type_of_value, 1)
            ->setData($result->value, 2)
            ->setData($result->reference_range, 3)
            ->setData($result->units, 4)
            ->setData(match($result->abnormal_flag){
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