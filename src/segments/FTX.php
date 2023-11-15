<?php

namespace mmerlijn\msgEdifact32\segments;

class FTX extends Segment implements SegmentInterface
{
    public function setText(array $text): self
    {
        for($i=0;$i<count($text);$i++){
            $this->setData($text[$i], 4, $i);
        }
        return $this;
    }

}