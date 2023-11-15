<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Name;

class PNA extends Segment implements SegmentInterface
{
    /*NAN Achternaam verkort
      NEA Achternaam echtgenoot verkort
      NEV Voorvoegsels echtgenoot
      NVN Eerste voornaam
      NVV Voorletters * voorvoegsels
    */
    public function getMsg(Msg $msg): Msg
    {
        $name_part = explode("*", $this->getData(6,2));
        $msg->patient->setName(new Name(
            initials: $name_part[0],
            lastname: $this->getData(8,1),
            prefix: $this->getData(9,1),
            own_lastname: $this->getData(5,1),
            own_prefix: $name_part[1] ?? "",
        ));
        return $msg;
    }
    public function setMsg(Msg $msg): void
    {
        $this->setData($msg->patient->bsn, 2,2);
        if($msg->patient->name->own_lastname){
            $this->setData("NAN", 5)
                ->setData($msg->patient->name->own_lastname, 5,1);
        }
        if($msg->patient->name->initials){
            $this->setData("NVV", 6)
                ->setData($msg->patient->name->initials.($msg->patient->name->own_prefix?'*'.$msg->patient->name->own_prefix:''), 6,1);
        }
        if($msg->patient->name->lastname){
            $this->setData("NEA", 8)
                ->setData($msg->patient->name->lastname, 8,1);
        }
        if($msg->patient->name->prefix){
            $this->setData("NEV", 9)
                ->setData($msg->patient->name->prefix, 9,1);
        }
    }

}