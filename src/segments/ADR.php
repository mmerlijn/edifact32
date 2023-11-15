<?php

namespace mmerlijn\msgEdifact32\segments;

use mmerlijn\msgRepo\Address;
use mmerlijn\msgRepo\Contact;
use mmerlijn\msgRepo\Msg;
use mmerlijn\msgRepo\Patient;

class ADR extends Segment implements SegmentInterface
{
    public function getMsg(Msg $msg): Msg
    {
        if($this->params['group']==6){ //patient
            $msg->patient->setAddress( new Address(
                postcode: $this->getData(4),
                city: $this->getData(3),
                street: $this->getData(2,2),
                building: $this->getData(2,3)
            ));
        }elseif($this->params['group']==1){ //sender
            $msg->sender->setAddress( new Address(
                postcode: $this->getData(4),
                city: $this->getData(3),
                street: $this->getData(2,2),
                building: $this->getData(2,3)
            ));
        }
        return $msg;
    }

    public function setMsg(Msg $msg): void
    {
        if($this->params['group']==6){ //patient
            $this->setAddress($msg->patient);
        }
        if($this->params['group']==1){ //sender
            $this->setAddress($msg->sender);
        }
    }
    public function setAddress(Contact|Patient $contact): self
    {
        $this->setData(substr($contact->address?->street ?? "",0,24),2,1)
            ->setData(substr($contact->address?->building_nr." ".$contact->address?->building_addition,0,9),2,2)
            ->setData(substr($contact->address?->city ?? "",0,24),3)
            ->setData($contact->address?->postcode,4);
        return $this;
    }

}