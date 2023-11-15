<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgEdifact32\segments\Segment;
use mmerlijn\msgEdifact32\segments\SegmentInterface;

class RND extends Segment implements SegmentInterface
{
    public function setRange(string $range): self
    {
        $parts = explode(" ", $range);
        $this->setData($parts[0], 2)
        ->setData($parts[1]??'', 3);
        return $this;
    }
}