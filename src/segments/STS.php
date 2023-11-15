<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Enums\OrderStatusEnum;
use mmerlijn\msgRepo\Msg;

class STS extends Segment implements SegmentInterface
{
    /*
010    4405  Status description code                   M      an..3
020    1131  Code list identification code             C      an..17
030    3055  Code list responsible agency code         C      an..3
040    4404  Status description                        C      an..35

G=comleet
P=gedeeltelijk
S=aanvullend
GU=gewijzigd
    */
    public function getMsg(Msg $msg): Msg
    {

        $msg->order->order_status = match ($this->getData(1)) {
            'P' => OrderStatusEnum::EMPTY,
            'S', 'GU' => OrderStatusEnum::CORRECTION,
            default => OrderStatusEnum::FINAL,
        };
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        if($msg->order->order_status){
            $this->setData(match($msg->order->order_status){
                OrderStatusEnum::CORRECTION => 'GU',
                default => 'G',
            }, 2);
        }
    }
}